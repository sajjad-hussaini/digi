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
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use setasign\Fpdi\Fpdi;

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
        $clientFullName = $clientName ?: '__________________'; // if name not available, blank line

        $data = [
            'client'         => $client,
            'clientName'     => $clientName,
            'clientFullName' => $clientFullName,
            'dob'            => $client->dob ? $client->dob : '__________________',
            'nationality'    => $client->country ?? '__________________',
            'address'        => $client->address ?? '________________________________________________________________',
            'lawFirm'        => $lawFirm,
            'lawFirmAddress' => $lawFirmAddress,
            'phone'          => $phone,
            'email'          => $email,
            'city'          =>  $client->city ?? ' ',
            'address2'      =>  $client->color ?? ' ',
            'national'      =>  $client->national ?? ' ',
            'visaType'       =>  $client->visa_type,
            'today'          => $today,
        ];

        $pdf = Pdf::loadView('clients.authority-letter', $data)
            ->setPaper('a4', 'portrait');

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

        $editedHtml = $request->edited_html;
        $format = $request->format;

        try {
            if ($format === 'docx') {
                return $this->generateDocx($editedHtml, $client);
            } else {
                return $this->generatePdf($editedHtml, $client);
            }
        } catch (\Exception $e) {
            Log::error('Document Generation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Generation failed'], 500);
        }
    }

    private function generateDocx($htmlContent, $client)
    {
        $phpWord = new PhpWord();
        [$docxHtml, $temporaryImages] = $this->prepareDocxHtml($htmlContent);

        $section = $phpWord->addSection([
            'pageSizeW' => 11906,
            'pageSizeH' => 16838,
            'marginLeft' => 1440,
            'marginRight' => 1440,
            'marginTop' => 1440,
            'marginBottom' => 1440,
        ]);

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            $section,
            $docxHtml,
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

    private function generatePdf($htmlContent, $client)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                @page { size: A4; margin: 18mm 18mm 18mm 18mm; }
                body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.5; margin: 0; }
                p { margin: 10px 0; }
                img:first-of-type { display: block; float: right; margin-left: auto; margin-right: 0; max-width: 140px; height: auto; }
            </style>
        </head>
        <body>
            ' . $this->prepareLetterHtml($htmlContent) . '
        </body>
        </html>';

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        return $pdf->download('Initial_Instruction_' . $client->first_name . '.pdf');
    }

    private function prepareLetterHtml(string $html): string
    {
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
            $html = $matches[1];
        }

        $footerAdded = false;
        if (stripos($html, 'qureshisalim@yahoo.com') === false) {
            $footerAdded = true;
            $footerLogo = public_path('images/footer.jpg');
            $footerLogoHtml = is_file($footerLogo)
                ? '<img src="' . $footerLogo . '" width="85" style="width:85px; max-width:85px; height:auto;">'
                : '';

            $html .= '<table width="100%" style="border-top:1px solid #999; margin-top:24px;">'
                . '<tr><td align="center">'
                . '<strong>UK Immigration Law</strong><br>'
                . '1st Floor, 236 ST. Helens Road, Bolton BL3 4EB, Ph. 07777328028, Email: qureshisalim@yahoo.com'
                . '</td><td width="100" align="right">' . $footerLogoHtml . '</td></tr>'
                . '</table>';
        }

        $imageCount = preg_match_all('/<img\b[^>]*>/i', $html);
        $imageIndex = 0;

        $html = preg_replace_callback('/<img\b([^>]*)>/i', function ($matches) use (&$imageIndex) {
            $imageIndex++;
            $attributes = $matches[1];
            $width = $imageIndex === 1 ? '70px' : '85px';
            $style = 'float:right; display:block; margin-left:auto; margin-right:0; width:' . $width . '; max-width:' . $width . '; height:auto;';

            if (preg_match('/\sstyle=["\']([^"\']*)["\']/i', $attributes, $styleMatch)) {
                $style .= ' ' . $styleMatch[1];
                $attributes = preg_replace('/\sstyle=["\'][^"\']*["\']/i', '', $attributes, 1);
            }

            $attributes = preg_replace('/\s(width|height)=["\'][^"\']*["\']/i', '', $attributes);
            $attributes .= ' width="' . ($imageIndex === 1 ? '70' : '85') . '"';

            return '<img' . $attributes . ' style="' . $style . '">';
        }, $html);

        if (!$footerAdded && $imageCount < 2) {
            $footerLogo = public_path('images/footer.jpg');
            if (is_file($footerLogo)) {
                $html .= '<p align="right"><img src="' . $footerLogo . '" width="85" style="width:85px; max-width:85px; height:auto;"></p>';
            }
        }

        return $html;
    }

    private function prepareDocxHtml(string $html): array
    {
        $temporaryImages = [];
        $html = $this->prepareLetterHtml($html);

        $html = preg_replace_callback('/<img\b([^>]*)>/i', function ($matches) use (&$temporaryImages) {
            $attributes = $matches[1];

            if (!preg_match('/\ssrc=["\']data:([^;]+);base64,([^"\']+)["\']/i', $attributes, $imageMatch)) {
                return $matches[0];
            }

            $extension = explode('/', strtolower($imageMatch[1]))[1] ?? 'png';
            $temporaryImage = tempnam(sys_get_temp_dir(), 'letter_image_');
            @unlink($temporaryImage);
            $temporaryImage .= '.' . preg_replace('/[^a-z0-9]/', '', $extension);
            file_put_contents($temporaryImage, base64_decode($imageMatch[2], true));
            $temporaryImages[] = $temporaryImage;

            $attributes = preg_replace(
                '/\ssrc=["\']data:[^;]+;base64,[^"\']+["\']/i',
                ' src="' . $temporaryImage . '"',
                $attributes,
                1
            );

            return '<img' . $attributes . '>';
        }, $html);

        $html = preg_replace_callback('/<img\b[^>]*>/i', function ($matches) {
            return '<p align="right">' . $matches[0] . '</p>';
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
        $client->ref_number = $this->generateRandomRefNumber();
        $client->is_permanent = true;
        $client->save();

        return redirect()->route('clients.show', $client->id)->with('success', 'Client marked as permanent.');
    }

    public function generateRandomRefNumber()
    {
        $characters = '0123456789';
        $refNumber = '';
        for ($i = 0; $i < 6; $i++) {
            $refNumber .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $refNumber;
    }

    public function getReferenceNumber($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['ref_number' => $client->ref_number]);
    }
}
