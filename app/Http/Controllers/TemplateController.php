<?php

namespace App\Http\Controllers;

use App\DataTables\TemplateDataTable;
use App\Repositories\TemplateRepository;
use App\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    protected $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(TemplateDataTable $templateDataTable)
    {
         $this->authorize('viewAny', Template::class);
        return $templateDataTable->render('templates.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $request->validate([
            'title' => 'required|string|max:255',
            'doc_file' => 'required|file|mimes:docx',
        ]);

        $filePath = $request->file('doc_file')->getRealPath();
        $content = file_get_contents($filePath);

        $template = new Template();
        $template->title = $request->title;
        $template->type = $request->type;
        $template->content = $content;
        $template->save();

        return redirect()->route('templates.index', $template)->with('success', 'Template saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $template = Template::findOrFail($id);
        return view('templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
   // Get template content for editing (AJAX)
    public function getContent($id)
    {
        $template = Template::findOrFail($id);
        
        $content = $template->content;
        if (is_resource($content)) {
            $content = stream_get_contents($content);
        }
        
        return response()->json([
            'id' => $template->id,
            'title' => $template->title,
            'content' => base64_encode($content)
        ]);
    }

    // Update template
    public function update(Request $request, Template $template)
    {

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $template->title = $request->title;

        // Check if new file uploaded
        if ($request->hasFile('doc_file')) {
            $request->validate([
                'doc_file' => 'required|file|mimes:docx',
            ]);

            $filePath = $request->file('doc_file')->getRealPath();
            $template->content = file_get_contents($filePath);
        } 
        // Check if edited HTML content exists
        elseif ($request->filled('edited_html')) {
            // Convert HTML back to DOCX
            $template->content = $this->htmlToDocx($request->edited_html);
        }

        $template->save();

        return redirect()->route('templates.index')->with('success', 'Template updated successfully');
    }

    // Helper: Convert HTML to DOCX binary
    private function htmlToDocx($html)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
        
        $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        $content = file_get_contents($tempFile);
        unlink($tempFile);
        
        return $content;
    }

    // Delete template
    public function destroy(Template $template)
    {
        $template->delete();
        return redirect()->route('templates.index')->with('success', 'Template deleted successfully');
    }
}
