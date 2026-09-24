<?php

namespace App\Http\Controllers;

use App\Client;
use App\Company;
use App\CustomField;
// use Barryvdh\DomPDF\PDF;
use App\DataTables\ClientDataTable;
use App\Repositories\ClientRepository;
use App\Repositories\PermissionRepository;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Template;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use setasign\Fpdi\Fpdi;
use ZipArchive;

class ClientController extends Controller
{
    /** @var  CompanyRepository */
    private $clientRepository;
    /** @var PermissionRepository */
    private $permissionRepository;

    public function __construct(
        ClientRepository $clientRepo,
        PermissionRepository $permissionRepository
    ) {
        $this->clientRepository = $clientRepo;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ClientDataTable $clientDataTable)
    {
        $clients = Client::latest()->paginate(10);
        return $clientDataTable->render('clients.index');
    }

    public function create()
    {
        $customFields = CustomField::where('model_type', 'clients')->get();
        $companies = Company::get();
        $selectedCompany = $companies->first()->id ?? null;
        $countries = include base_path('vendor/umpirsky/country-list/data/en/country.php');
        // Make full country name the key and value
        $countries = array_combine(
            array_values($countries),
            array_values($countries)
        );
        return view('clients.create', compact('customFields', 'companies', 'selectedCompany', 'countries'));
    }

    public function store(Request $request)
    {
        // store client
        $client = $this->clientRepository->store($request);
        return redirect()->route('clients.show', $client->id)->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        $customFields = CustomField::get();
        $client_matter_type = $client->visa_type;
        $client->load(['invoices.items', 'invoices.receipts']);

        $totalInvoiced = (float) $client->invoices->sum(function ($invoice) {
            return $invoice->total_due ?: $invoice->amount;
        });
        $totalPaid = (float) $client->invoices->flatMap->receipts->sum('amount_paid');
        $totalRemaining = max(0, round($totalInvoiced - $totalPaid, 2));
        $ledger = collect();

        foreach ($client->invoices as $invoice) {
            $ledger->push([
                'date' => $invoice->invoice_date ?: $invoice->created_at,
                'type' => 'Invoice',
                'reference' => $invoice->invoice_no,
                'description' => 'Invoice issued',
                'debit' => (float) ($invoice->total_due ?: $invoice->amount),
                'credit' => 0,
            ]);

            foreach ($invoice->receipts as $receipt) {
                $ledger->push([
                    'date' => $receipt->payment_date ?: $receipt->created_at,
                    'type' => 'Payment',
                    'reference' => $receipt->receipt_number,
                    'description' => ucfirst($receipt->payment_method) . ' payment',
                    'debit' => 0,
                    'credit' => (float) $receipt->amount_paid,
                ]);
            }
        }

        $runningBalance = 0;
        $ledger = $ledger->sortBy('date')->values()->map(function ($entry) use (&$runningBalance) {
            $runningBalance += $entry['debit'] - $entry['credit'];
            $entry['balance'] = round($runningBalance, 2);
            return $entry;
        });

        return view('clients.show', compact(
            'client', 'customFields', 'client_matter_type',
            'totalInvoiced', 'totalPaid', 'totalRemaining', 'ledger'
        ));
    }

    public function edit(Client $client)
    {
        $countries = include base_path('vendor/umpirsky/country-list/data/en/country.php');
          $countries = array_combine(
            array_values($countries),
            array_values($countries)
        );
        $companies = Company::select('id', 'company_name')->get();
        return view('clients.edit', compact('client', 'companies', 'countries'));
    }

    public function update(Request $request, Client $client)
    {
        // update client
        $client->update([
            'first_name' => $request->input('first_name'),
            'sir_name' => $request->input('sir_name'),
            'email' => $request->input('email'),
            'company_id' => $request->input('company_id') ?? 1,
            'phone' => $request->input('phone'),
            'passport_no' => $request->input('passport_no'),
            'visa_type' => $request->input('visa_type'),
            'visa_expiry_date' => $request->input('visa_expiry_date'),
            'dob' => $request->input('dob'),
            'country' => $request->input('country'),
            'national'          => $request->input('national') ?? null,
            'address' => $request->input('address'),
            'status' => $request->input('status'),
            'priority' => $request->input('priority'),
            'court_type' => $request->input('court_type'),
            'color' => $request->input('color'),
            'city' => $request->input('city'),
            'gender' => $request->input('gender'),
            'visa_issued_date' => $request->input('visa_issued_date'),
        ]);
        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('success', 'Client deleted.');
    }

    public function generateAuthorityLetter(Client $client)
    {
        // Law firm details (if in database, get from there, otherwise config or hardcode)
        $lawFirm = config('app.law_firm_name', 'UK Immigration Law');
        $lawFirmAddress = config('app.law_firm_address', '1st floor, 236 St. Helens Road, Bolton BL3 4EB');
        $phone = config('app.law_firm_phone', '07777328028');
        $email = config('app.law_firm_email', 'qureshisalim@yahoo.com');

        // Today's date formatted
        $today = now()->format('jS F Y');

        // Safely access client fields
        $clientName = trim($client->first_name . ' ' . $client->sir_name);
        $salutation = match (strtolower(trim((string) $client->gender))) {
            'female', 'f' => 'Mrs',
            'male', 'm' => 'Mr',
            default => '',
        };

        if ($clientName !== '') {
            if ($salutation && !preg_match('/^(mr|mrs|ms|miss)\b/i', $clientName)) {
                $clientFullName = $salutation . ' ' . $clientName;
            } else {
                $clientFullName = $clientName;
            }
        } else {
            $clientFullName = '__________________';
        }

        $addressParts = array_filter([
            $client->address,
            $client->color, // address line 2
            $client->city,
            $client->post_code,
            $client->national, // country
        ], fn($val) => !empty(trim((string)$val)));

        $formattedAddress = !empty($addressParts)
            ? implode(', ', $addressParts)
            : '________________________________________________________________';

        $data = [
            'client'           => $client,
            'salutation'       => $salutation,
            'clientName'       => $clientName,
            'clientFullName'   => $clientFullName,
            'dob'              => $client->dob ? $client->dob : '__________________',
            'nationality'      => $client->country ?? '__________________',
            'address'          => $client->address ?? '________________________________________________________________',
            'formattedAddress' => $formattedAddress,
            'lawFirm'          => $lawFirm,
            'lawFirmAddress'   => $lawFirmAddress,
            'phone'            => $phone,
            'email'            => $email,
            'city'             => $client->city ?? ' ',
            'address2'         => $client->color ?? ' ',
            'national'         => $client->national ?? ' ',
            'visaType'         => $client->visa_type,
            'today'            => $today,
        ];

        $pdf = Pdf::loadView('clients.authority-letter', $data)
            ->setPaper('letter', 'portrait');

        return $pdf->stream('Authority_Letter_' . str_replace(' ', '_', $clientFullName) . '.pdf');
    }

    public function clientCareLetter(Client $client)
    {
        // Format the date as in the letter (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Passing data to blade template
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('clients.client_clouser_letter', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ])->setPaper('a4', 'portrait');

        // Download or show in browser
        return $pdf->stream('care_Letter_' . $client->first_name . '.pdf');
        // or you can use ->download()
    }

    public function initialInstructionLetter(Request $request, Client $client)
    {
        // dd($request->all());
        // Format the date as in the letter (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Passing data to blade template
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('clients.client_instruction', [
            'client' => $client,
            'request' => $request,
            'today'  => now()->format('jS F Y')
        ])->setPaper('a4', 'portrait');

        // Download or show in browser
        return $pdf->stream('care_Letter_' . $client->first_name . '.pdf');
        // or you can use ->download()
    }

    public function eeCareLetter(Request $request, Client $client)
    {
        // Format the date as in the letter (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Passing data to blade template
        $data = [
            'client' => $client,
            'request' => $request,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('clients.client_eecare_letter', [
            'client' => $client,
            'request' => $request,
            'today'  => now()->format('jS F Y')
        ])->setPaper('a4', 'portrait');

        // Download or show in browser
        return $pdf->stream('care_Letter_' . $client->first_name . '.pdf');
        // or you can use ->download()
    }

    public function coveringLetter(Client $client)
    {
        // Format the date as in the letter (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Passing data to blade template
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('clients.client_covering_letter', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ])->setPaper('a4', 'portrait');

        // Download or show in browser
        return $pdf->stream('care_Letter_' . $client->first_name . '.pdf');
        // or you can use ->download()
    }

    public function generateDocument(Request $request, Client $client)
    {
        // $request->validate([
        //     'original_docx' => 'required|mimes:docx|max:10240',
        //     'edited_html' => 'required',
        //     'format' => 'required|in:docx,pdf'
        // ]);

        $request->validate([
            'edited_html' => 'required|string',
            'format' => 'required|in:docx,pdf',
            'template_id' => 'nullable|integer|exists:templates,id',
        ]);

        $editedHtml = $request->input('edited_html');
        $format = $request->input('format');
        $editedHtml = $this->replaceClientPlaceholders($editedHtml, $client);

        try {
            if ($format === 'docx') {
                if ($request->filled('template_id')) {
                    return $this->generateDocxFromTemplate(
                        Template::findOrFail($request->input('template_id')),
                        $client
                    );
                }

                return $this->generateDocx($editedHtml, $client);
            } else {
                if ($request->filled('template_id')) {
                    // Use the same cleaned editor HTML shown to the user so
                    // embedded template logos do not reappear in the PDF.
                    return $this->generatePdf($editedHtml, $client);
                }

                return $this->generatePdf($editedHtml, $client);
            }
        } catch (\Throwable $e) {
            Log::error('Document Generation Error: ' . $e->getMessage());
            return response()->json(['error' => 'DOCX/PDF generation failed: ' . $e->getMessage()], 500);
        }
    }

    private function generateDocx($htmlContent, $client)
    {
        $phpWord = new PhpWord();
        [$docxHtml, $temporaryImages] = $this->prepareDocxHtml($htmlContent);

        $section = $phpWord->addSection([
            'pageSizeW' => 11906,
            'pageSizeH' => 16838,
            'marginLeft' => 1020,
            'marginRight' => 1020,
            'marginTop' => 1020,
            'marginBottom' => 1020,
        ]);

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            $section,
            '<!DOCTYPE html><html><body>' . $docxHtml . '</body></html>',
            false,
            false
        );

        // Save to temp file
        $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        foreach ($temporaryImages as $temporaryImage) {
            @unlink($temporaryImage);
        }

        return response()->download($tempFile, 'Initial_Instruction_' . $client->first_name . '.docx')
            ->deleteFileAfterSend(true);
    }

    private function generateDocxFromTemplate(Template $template, Client $client)
    {
        $temporaryFile = tempnam(sys_get_temp_dir(), 'template_') . '.docx';
        file_put_contents($temporaryFile, $template->content);

        $zip = new ZipArchive();
        if ($zip->open($temporaryFile) !== true) {
            @unlink($temporaryFile);
            throw new \RuntimeException('Unable to open the DOCX template.');
        }

        $salutation = match (strtolower((string) $client->gender)) {
            'female', 'f' => 'Mrs',
            'male', 'm' => 'Mr',
            default => '',
        };
        $replacements = [
            '[REFERENCE_NUMBER]' => $client->ref_number ?? '',
            '{{ref_number}}' => $client->ref_number ?? '',
            '[SALUTATION]' => $salutation,
            '[CLIENT_FIRST_NAME]' => $client->first_name ?? '',
            '[CLIENT_SURNAME]' => $client->sir_name ?? '',
            '[CLIENT_GENDER]' => $client->gender ?? '',
            '[CLIENT_PASSPORT_NO]' => $client->passport_no ?? '',
            '[CITY]' => $client->city ?? '',
            '[CLIENT_EMAIL]' => $client->email ?? '',
            '[CLIENT_PHONE]' => $client->phone ?? '',
            '[CLIENT_DOB]' => $client->dob ?? '',
            '[ADDRESS_1]' => $client->address1 ?? '',
            '[ADDRESS_2]' => $client->color ?? '',
            '[NATIONALITY]' => $client->country ?? '',
            '[COUNTRY]' => $client->national ?? '',
            '[DATE]' => now()->format('jS F Y'),
        ];

        for ($index = 0; $index < $zip->numFiles; $index++) {
            $entryName = $zip->getNameIndex($index);
            if (!preg_match('#^word/(document|header\d+|footer\d+)\.xml$#', $entryName)) {
                continue;
            }

            $xml = $zip->getFromIndex($index);
            foreach ($replacements as $placeholder => $replacement) {
                $xml = str_replace(
                    htmlspecialchars($placeholder, ENT_XML1, 'UTF-8'),
                    htmlspecialchars($replacement, ENT_XML1, 'UTF-8'),
                    $xml
                );
            }
            $zip->addFromString($entryName, $xml);
        }

        $zip->close();

        return response()->download(
            $temporaryFile,
            'Initial_Instruction_' . $client->first_name . '.docx'
        )->deleteFileAfterSend(true);
    }

    private function generatePdf($htmlContent, $client)
    {
        // The browser editor cannot render a DOCX header/footer.  Keep those
        // elements outside the editable HTML so Dompdf repeats them on every
        // page and the document body keeps its own alignment.
        $htmlContent = $this->removeGeneratedDocumentChrome($htmlContent);
        $headerLogo = public_path('images/logo_imigration_law.png');
        $headerLogoHtml = is_file($headerLogo)
            ? '<img src="' . $headerLogo . '" style="width:30mm; height:auto;">'
            : '';
        $footerLogo = public_path('images/footer.jpg');
        $footerLogoHtml = is_file($footerLogo)
            ? '<img src="' . $footerLogo . '" style="width:69.6px; height:58px; display:block;">'
            : '';
        $documentHeader = '<table class="pdf-header" width="100%"><tr>'
            . '<td align="right">' . $headerLogoHtml . '</td>'
            . '</tr></table>';
        $documentFooter = '<div class="pdf-footer">'
            . '<div class="pdf-footer-rules"></div>'
            . '<div class="pdf-footer-firm">UK Immigration Law</div>'
            . '<div class="pdf-footer-address">1st floor, 236 ST. Helens Road, Bolton BL3 4EB, Ph. 07777328028, Email: qureshisalim@yahoo.com</div>'
            . '<div class="pdf-footer-logo">' . $footerLogoHtml . '</div>'
            . '</div>';

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                @page { size: letter portrait; margin-top: 30mm; margin-right: 17.5mm; margin-bottom: 22mm; margin-left: 17.5mm; }
                body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.15; margin: 0; }
                .document-page { width: auto; min-height: 0; box-sizing: border-box; padding: 0; overflow: visible; overflow-wrap: break-word; }
                .document-page h1,
                .document-page h2,
                .document-page h3,
                .document-page h4,
                .document-page h5,
                .document-page h6,
                .document-page p { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.15; margin: 0 0 8px; }
                .document-page h1,
                .document-page h2,
                .document-page h3,
                .document-page h4,
                .document-page h5,
                .document-page h6 { font-weight: bold; }
                .document-page table { max-width: 100%; }
                .document-page img { max-width: 100%; height: auto; }
                .pdf-header { position: absolute; left: 0; right: 0; top: -18mm; border-collapse: collapse; }
                .pdf-footer { position: fixed; left: 0; right: 0; bottom: -19.87mm; width: 100%; text-align: center; }
                .pdf-footer-rules { border-top: 1px solid #000; border-bottom: 1px solid #000; height: 1px; margin: 0 0 2.3px; }
                .pdf-footer-firm { margin: 0; font-family: "Times New Roman", Times, serif; font-size: 14px; font-weight: bold; line-height: 14px; }
                .pdf-footer-address { margin-top: 1.35px; color: #000; font-family: Arial, Helvetica, sans-serif; font-size: 11.83px; line-height: 14px; white-space: nowrap; }
                .pdf-footer-logo { position: absolute; top: -28.2px; right: 0; width: 69.6px; text-align: right; }
            </style>
        </head>
        <body>
            ' . $documentHeader . '
            ' . $documentFooter . '
            <div class="document-page">' . $this->prepareLetterHtml($htmlContent, false) . '</div>
        </body>
        </html>';

        $pdf = Pdf::loadHTML($html)->setPaper('letter', 'portrait');

        return $pdf->download('Initial_Instruction_' . $client->first_name . '.pdf');
    }

    private function generatePdfFromTemplate(Template $template, Client $client)
    {
        $temporaryFile = $this->createPersonalizedTemplateFile($template, $client);
        $pdfFile = tempnam(sys_get_temp_dir(), 'template_pdf_');
        @unlink($pdfFile);
        $pdfFile .= '.pdf';

        try {
            $this->convertDocxToPdfWithWord($temporaryFile, $pdfFile);

            return response()->download(
                $pdfFile,
                'Initial_Instruction_' . $client->first_name . '.pdf'
            )->deleteFileAfterSend(true);
        } finally {
            @unlink($temporaryFile);
        }
    }

    private function convertDocxToPdfWithWord(string $docxFile, string $pdfFile): void
    {
        if (DIRECTORY_SEPARATOR !== '\\') {
            throw new \RuntimeException('Original Word-layout PDF conversion is available only on the Windows document server.');
        }

        $wordExecutable = 'C:\\Program Files\\Microsoft Office\\Office16\\WINWORD.EXE';
        if (!is_file($wordExecutable)) {
            throw new \RuntimeException('Microsoft Word is required to create a PDF with the original template layout.');
        }

        $quotePowerShell = static function (string $path): string {
            return "'" . str_replace("'", "''", $path) . "'";
        };
        $input = $quotePowerShell($docxFile);
        $output = $quotePowerShell($pdfFile);
        $script = "\$ErrorActionPreference = 'Stop'; "
            . "\$word = New-Object -ComObject Word.Application; "
            . "\$word.Visible = \$false; \$word.DisplayAlerts = 0; "
            . "try { \$document = \$word.Documents.Open($input, \$false, \$true); "
            . "\$document.ExportAsFixedFormat($output, 17); \$document.Close(); } "
            . "finally { \$word.Quit(); [void][Runtime.InteropServices.Marshal]::ReleaseComObject(\$word); }";

        $command = 'powershell.exe -NoProfile -NonInteractive -ExecutionPolicy Bypass -Command '
            . escapeshellarg($script);
        $process = proc_open($command, [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes);

        if (!is_resource($process)) {
            throw new \RuntimeException('Unable to start Microsoft Word PDF conversion.');
        }

        $standardOutput = stream_get_contents($pipes[1]);
        $standardError = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        if ($exitCode !== 0 || !is_file($pdfFile)) {
            @unlink($pdfFile);
            throw new \RuntimeException('Word could not convert the original template to PDF. ' . trim($standardError ?: $standardOutput));
        }
    }

    private function createPersonalizedTemplateFile(Template $template, Client $client): string
    {
        $temporaryFile = tempnam(sys_get_temp_dir(), 'template_') . '.docx';
        file_put_contents($temporaryFile, $template->content);

        $zip = new ZipArchive();
        if ($zip->open($temporaryFile) !== true) {
            @unlink($temporaryFile);
            throw new \RuntimeException('Unable to open the DOCX template.');
        }

        $salutation = match (strtolower((string) $client->gender)) {
            'female', 'f' => 'Mrs',
            'male', 'm' => 'Mr',
            default => '',
        };
        $replacements = [
            '[REFERENCE_NUMBER]' => $client->ref_number ?? '',
            '{{ref_number}}' => $client->ref_number ?? '',
            '[SALUTATION]' => $salutation,
            '[CLIENT_FIRST_NAME]' => $client->first_name ?? '',
            '[CLIENT_SURNAME]' => $client->sir_name ?? '',
            '[CLIENT_GENDER]' => $client->gender ?? '',
            '[CLIENT_PASSPORT_NO]' => $client->passport_no ?? '',
            '[CITY]' => $client->city ?? '',
            '[CLIENT_EMAIL]' => $client->email ?? '',
            '[CLIENT_PHONE]' => $client->phone ?? '',
            '[CLIENT_DOB]' => $client->dob ?? '',
            '[ADDRESS_1]' => $client->address1 ?? '',
            '[ADDRESS_2]' => $client->color ?? '',
            '[NATIONALITY]' => $client->country ?? '',
            '[COUNTRY]' => $client->national ?? '',
            '[DATE]' => now()->format('jS F Y'),
        ];

        for ($index = 0; $index < $zip->numFiles; $index++) {
            $entryName = $zip->getNameIndex($index);
            if (!preg_match('#^word/(document|header\d+|footer\d+)\.xml$#', $entryName)) {
                continue;
            }

            $xml = $zip->getFromIndex($index);
            foreach ($replacements as $placeholder => $replacement) {
                $xml = str_replace(
                    htmlspecialchars($placeholder, ENT_XML1, 'UTF-8'),
                    htmlspecialchars($replacement, ENT_XML1, 'UTF-8'),
                    $xml
                );
            }
            $zip->addFromString($entryName, $xml);
        }

        $zip->close();

        return $temporaryFile;
    }

    private function replaceClientPlaceholders(string $html, Client $client): string
    {
        $salutation = match (strtolower((string) $client->gender)) {
            'female', 'f' => 'Mrs',
            'male', 'm' => 'Mr',
            default => '',
        };

        return str_replace(
            ['[REFERENCE_NUMBER]', '{{ref_number}}', '[SALUTATION]'],
            [$client->ref_number ?? '', $client->ref_number ?? '', $salutation],
            $html
        );
    }

    private function prepareLetterHtml(string $html, bool $addFooter = true): string
    {
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
            $html = $matches[1];
        }

        $footerAdded = false;
        if ($addFooter && stripos($html, 'qureshisalim@yahoo.com') === false) {
            $footerAdded = true;
            $footerLogo = public_path('images/footer.jpg');
            $footerLogoHtml = is_file($footerLogo)
                ? '<img src="' . $footerLogo . '" width="120" style="width:120px; max-width:100%; height:auto;">'
                : '';

            $html .= '<table width="100%" style="border-top:1px solid #999; margin-top:24px;">'
                . '<tr><td align="center">'
                . '<strong>UK Immigration Law</strong><br>'
                . '1st Floor, 236 ST. Helens Road, Bolton BL3 4EB, Ph. 07777328028, Email: qureshisalim@yahoo.com'
                . '</td><td width="100" align="right">' . $footerLogoHtml . '</td></tr>'
                . '</table>';
        }

        $imageCount = preg_match_all('/<img\b[^>]*>/i', $html);

        $html = preg_replace_callback('/<img\b([^>]*)>/i', function ($matches) {
            $attributes = $matches[1];
            $style = 'max-width:100%; height:auto;';

            if (preg_match('/\sstyle=["\']([^"\']*)["\']/i', $attributes, $styleMatch)) {
                $style .= ' ' . $styleMatch[1];
                $attributes = preg_replace('/\sstyle=["\'][^"\']*["\']/i', '', $attributes, 1);
            }

            return '<img' . $attributes . ' style="' . $style . '">';
        }, $html);

        if ($addFooter && !$footerAdded && $imageCount < 2) {
            $footerLogo = public_path('images/footer.jpg');
            if (is_file($footerLogo)) {
                $html .= '<p align="right"><img src="' . $footerLogo . '" width="120" style="width:120px; max-width:100%; height:auto;"></p>';
            }
        }

        return $html;
    }

    private function removeGeneratedDocumentChrome(string $html): string
    {
        // Header/footer shown by the web editor are previews only. They must
        // not stay in the HTML, otherwise a footer can appear in the document
        // flow in addition to the fixed PDF footer.
        $html = preg_replace('/<(table|div)\b[^>]*class=["\'][^"\']*document-(?:header|footer)[^"\']*["\'][^>]*>.*?<\/\1>/is', '', $html);
        $html = preg_replace('/<table\b[^>]*>[\s\S]*?qureshisalim@yahoo\.com[\s\S]*?<\/table>/is', '', $html);

        return $html ?? '';
    }

    private function prepareDocxHtml(string $html): array
    {
        $temporaryImages = [];
        $html = $this->prepareLetterHtml($html, false);
        $html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $html);
        $html = preg_replace('/<!--.*?-->/s', '', $html);

        $html = preg_replace_callback('/<img\b([^>]*)>/i', function ($matches) use (&$temporaryImages) {
            $attributes = $matches[1];
            $imagePath = null;
            $imageExtension = 'png';

            if (preg_match('/\ssrc=["\']data:([^;]+);base64,([^"\']+)["\']/i', $attributes, $imageMatch)) {
                $imageExtension = explode('/', strtolower($imageMatch[1]))[1] ?? 'png';
                $imagePath = tempnam(sys_get_temp_dir(), 'letter_source_');
                file_put_contents($imagePath, base64_decode($imageMatch[2], true));
            } elseif (preg_match('/\ssrc=["\']([^"\']+)["\']/i', $attributes, $sourceMatch)) {
                $relativePath = parse_url($sourceMatch[1], PHP_URL_PATH) ?: $sourceMatch[1];
                $candidatePath = public_path(ltrim(str_replace('/', DIRECTORY_SEPARATOR, $relativePath), DIRECTORY_SEPARATOR));
                if (!is_file($candidatePath)) {
                    $candidatePath = public_path('images' . DIRECTORY_SEPARATOR . basename($relativePath));
                }

                if (is_file($candidatePath)) {
                    $imageExtension = pathinfo($candidatePath, PATHINFO_EXTENSION) ?: 'png';
                    $imagePath = $candidatePath;
                }
            }

            if (!$imagePath) {
                return $matches[0];
            }

            $temporaryImage = tempnam(sys_get_temp_dir(), 'letter_image_');
            @unlink($temporaryImage);
            $temporaryImage .= '.' . preg_replace('/[^a-z0-9]/i', '', $imageExtension);
            copy($imagePath, $temporaryImage);
            $temporaryImages[] = $temporaryImage;

            $attributes = preg_replace(
                '/\ssrc=["\'][^"\']+["\']/i',
                ' src="' . $temporaryImage . '"',
                $attributes,
                1
            );

            return '<img' . $attributes . '>';
        }, $html);

        $html = preg_replace('/\s(?:class|id)=["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace_callback('/\sstyle=["\']([^"\']*)["\']/i', function ($matches) {
            $allowed = [];
            foreach (explode(';', $matches[1]) as $declaration) {
                [$property, $value] = array_pad(explode(':', $declaration, 2), 2, null);
                $property = strtolower(trim((string) $property));
                if ($value !== null && in_array($property, [
                    'text-align', 'font-family', 'font-size', 'font-weight', 'font-style',
                    'text-decoration', 'line-height', 'width', 'height', 'border', 'padding'
                ], true)) {
                    $allowed[] = $property . ':' . trim($value);
                }
            }

            return $allowed ? ' style="' . implode(';', $allowed) . '"' : '';
        }, $html);

        return [$html, $temporaryImages];
    }

    // Base template method (existing)
    public function initialInstructionBase(Client $client)
    {
        $pdf = Pdf::loadView('clients.client_instruction', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ]);

        return $pdf->stream('Initial_Instruction_' . $client->first_name . '.pdf');
    }

    public function initialInstructionCustom(Request $request, Client $client)
    {
        $request->validate([
            'template_pdf' => 'required|mimes:pdf|max:10240',
            'text_data' => 'required|json'
        ]);

        $uploadedPdf = $request->file('template_pdf');
        $textData = json_decode($request->text_data, true);

        try {
            $pdf = new Fpdi();
            $pdf->AddPage();

            // Import original PDF
            $pdf->setSourceFile($uploadedPdf->getRealPath());
            $tplId = $pdf->importPage(1);
            $pdf->useTemplate($tplId);

            // Process each text item
            foreach ($textData as $text) {
                if ($text['changed']) {
                    // Cover original text with white rectangle
                    $pdf->SetFillColor(255, 255, 255);

                    // Calculate rectangle dimensions
                    $rectX = $text['x'] / 1.5;
                    $rectY = $text['y'] / 1.5;
                    $rectWidth = $text['width'] / 1.5 + 2; // Add padding
                    $rectHeight = $text['fontSize'] / 1.5 + 1;

                    $pdf->Rect($rectX, $rectY, $rectWidth, $rectHeight, 'F');

                    // Add new text
                    $pdf->SetFont('Arial', '', $text['fontSize'] / 1.5);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetXY($rectX, $rectY);
                    $pdf->Write(0, $text['replacement']);
                }
            }

            $pdfContent = $pdf->Output('S');

            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="Initial_Instruction_' . $client->first_name . '.pdf"');
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage());
            return response()->json(['error' => 'PDF generation failed'], 500);
        }
    }

    // To fetch templates list
    public function getTemplates(Request $request)
    {
        $type = $request->query('type');
        $matter_type = $request->query('matter_type');
        $query = Template::query();
        if ($type) {
            $query->where('type', $type)->where('matter_type', $matter_type);   // or whatever column name you use

        }
        $templates = $query->get(['id', 'title', 'created_at']); // or whatever fields you need
        return response()->json($templates);
    }

    // Single template content fetch (BLOB to base64)
    public function getTemplateContent($id)
    {
        $template = Template::findOrFail($id);

        // LONGBLOB content
        $content = $template->content;

        // If it's a resource/stream (MySQL LONGBLOB sometimes returns stream)
        if (is_resource($content)) {
            $content = stream_get_contents($content);
        }

        // Check if content is valid
        if (empty($content)) {
            return response()->json(['error' => 'Template content empty'], 404);
        }

        return response()->json([
            'id'      => $template->id,
            'title'   => $template->title,
            'content' => base64_encode($content)
        ]);
    }

    public function makePermanent($id)
    {
        $client = Client::findOrFail($id);

        if (!$client->is_permanent || !$client->ref_number) {
            DB::transaction(function () use ($client) {
                if (!$client->ref_number) {
                    $client->ref_number = $this->generateRandomRefNumber();
                }

                $client->is_permanent = true;
                $client->save();
            });
        }

        return redirect()->route('clients.show', $client->id)->with('success', 'Client marked as permanent.');
    }

    public function generateRandomRefNumber()
    {
        $lastReference = Client::query()
            ->whereNotNull('ref_number')
            ->lockForUpdate()
            ->pluck('ref_number')
            ->map(fn ($reference) => (int) $reference)
            ->max() ?? 0;

        return str_pad((string) ($lastReference + 1), 6, '0', STR_PAD_LEFT);
    }

    public function getReferenceNumber($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['ref_number' => $client->ref_number]);
    }
}
