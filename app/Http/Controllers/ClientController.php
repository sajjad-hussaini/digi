<?php

namespace App\Http\Controllers;

use App\Client;
use App\Company;
use App\CustomField;
// use Barryvdh\DomPDF\PDF;
use App\DataTables\ClientDataTable;
use App\Repositories\ClientRepository;
use App\Repositories\PermissionRepository;
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

    public function __construct(ClientRepository $clientRepo,
                                PermissionRepository $permissionRepository)
    {
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

        return view('clients.show', compact('client', 'customFields', 'client_matter_type'));
    }

    public function edit(Client $client)
    {
        $countries = include base_path('vendor/umpirsky/country-list/data/en/country.php');
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
        // Law firm details (agar database mein ho to wahan se lo, warna config ya hardcode)
        $lawFirm = config('app.law_firm_name', 'UK Immigration Law');
        $lawFirmAddress = config('app.law_firm_address', '1st floor, 236 St. Helens Road, Bolton BL3 4EB');
        $phone = config('app.law_firm_phone', '07777328028');
        $email = config('app.law_firm_email', 'qureshisalim@yahoo.com');

        // Today's date formatted
        $today = now()->format('jS F Y');

        // Client ke fields ko safely access karo
        $clientName = trim($client->first_name . ' ' . $client->sir_name);
        $clientFullName = $clientName ?: '__________________'; // agar name na ho to blank line

        $data = [
            'client'         => $client,
            'clientName'     => $clientName,
            'clientFullName' => $clientFullName,
            'dob'            => $client->dob ? \Carbon\Carbon::parse($client->dob)->format('d.m.Y') : '__________________',
            'nationality'    => $client->country ?? '__________________',
            'address'        => $client->address ?? '________________________________________________________________',
            'lawFirm'        => $lawFirm,
            'lawFirmAddress' => $lawFirmAddress,
            'phone'          => $phone,
            'email'          => $email,
            'visaType'          =>  $client->visa_type,
            'today'          => $today,
        ];

        $pdf = Pdf::loadView('clients.authority-letter', $data);

        return $pdf->stream('Authority_Letter_' . str_replace(' ', '_', $clientFullName) . '.pdf');
    }

    public function clientCareLetter(Client $client)
    {
        // Date format kar lo jaise letter mein hai (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Data pass kar rahe hain blade template ko
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // PDF generate karo
        $pdf = Pdf::loadView('clients.client_clouser_letter', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('care_Letter_'.$client->first_name.'.pdf');
        // ya ->download() kar sakte ho
    }

    public function initialInstructionLetter(Request $request, Client $client)
    {
        // dd($request->all());
        // Date format kar lo jaise letter mein hai (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Data pass kar rahe hain blade template ko
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // PDF generate karo
        $pdf = Pdf::loadView('clients.client_instruction', [
            'client' => $client,
            'request' => $request,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('care_Letter_'.$client->first_name.'.pdf');
        // ya ->download() kar sakte ho
    }

    public function eeCareLetter(Request $request, Client $client)
    {
        // Date format kar lo jaise letter mein hai (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Data pass kar rahe hain blade template ko
        $data = [
            'client' => $client,
            'request' => $request,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // PDF generate karo
        $pdf = Pdf::loadView('clients.client_eecare_letter', [
            'client' => $client,
            'request' => $request,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('care_Letter_'.$client->first_name.'.pdf');
        // ya ->download() kar sakte ho
    }

    public function coveringLetter(Client $client)
    {
        // Date format kar lo jaise letter mein hai (29th October 2025)
        $formattedDate = $client->created_at->format('jS F Y'); // 29th October 2025

        // Data pass kar rahe hain blade template ko
        $data = [
            'client' => $client,
            'formattedDate' => $formattedDate ?? now()->format('jS F Y'),
            'today' => now()->format('jS F Y'),
        ];

        // PDF generate karo
        $pdf = Pdf::loadView('clients.client_covering_letter', [
            'client' => $client,
            'today'  => now()->format('jS F Y')
        ]);

        // Download ya browser mein show karo
        return $pdf->stream('care_Letter_'.$client->first_name.'.pdf');
        // ya ->download() kar sakte ho
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
        
        // Add section
        $section = $phpWord->addSection([
            'marginLeft' => 1440,
            'marginRight' => 1440,
            'marginTop' => 1440,
            'marginBottom' => 1440,
        ]);

        // Convert HTML to Word (basic conversion)
        // Remove HTML tags and create paragraphs
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'));
        
        $paragraphs = $dom->getElementsByTagName('p');
        
        foreach ($paragraphs as $p) {
            $text = $p->textContent;
            if (!empty(trim($text))) {
                $section->addText($text, ['size' => 11, 'name' => 'Calibri']);
            }
        }

        // Save to temp file
        $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile, 'Initial_Instruction_' . $client->first_name . '.docx')
            ->deleteFileAfterSend(true);
    }

    private function generatePdf($htmlContent, $client)
    {
        // Clean HTML for PDF
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: Arial, sans-serif; font-size: 11pt; line-height: 1.5; }
                p { margin: 10px 0; }
            </style>
        </head>
        <body>
            ' . $htmlContent . '
        </body>
        </html>';

        $pdf = Pdf::loadHTML($html);
        
        return $pdf->download('Initial_Instruction_' . $client->first_name . '.pdf');
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

    // Templates list fetch karne ke liye
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
        
        // Agar resource/stream hai (MySQL LONGBLOB sometimes stream return karta hai)
        if (is_resource($content)) {
            $content = stream_get_contents($content);
        }
        
        // Check karo content valid hai
        if (empty($content)) {
            return response()->json(['error' => 'Template content empty'], 404);
        }
        
        return response()->json([
            'id'      => $template->id,
            'title'   => $template->title,
            'content' => base64_encode($content)
        ]);
    }
}
