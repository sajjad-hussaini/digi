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
            'edited_html' => 'prohibited',
            'type' => 'required|in:Authority Letter,Initial Instruction,Client Care,Client Closure Letter,Covering Letter',
            'doc_file' => 'required|file|mimes:docx|max:10240',
            'visa_type' => 'required|in:Appeal,Work Visa,Student Visa,Spouse Visa,Visitor Visa,Settlement Visa',
        ]);

        $uploadedFile = $request->file('doc_file');
        if (!$uploadedFile || !$uploadedFile->isValid()) {
            $message = $uploadedFile
                ? $uploadedFile->getErrorMessage()
                : 'Please select a valid DOCX file.';

            return back()->withErrors(['doc_file' => $message])->withInput();
        }

        $content = $uploadedFile->get();

        $template = new Template();
        $template->title = $request->title;
        $template->type = $request->type;
        $template->matter_type = $request->visa_type;
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
    public function download(Template $template)
    {
        $content = $template->content;
        if (is_resource($content)) {
            $content = stream_get_contents($content);
        }

        return response($content)->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->header('Content-Disposition', 'attachment; filename="Template_' . $template->id . '.docx"');
    }

    public function update(Request $request, Template $template)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'edited_html' => 'prohibited',
            'type' => 'required|in:Authority Letter,Initial Instruction,Client Care,Client Closure Letter,Covering Letter',
            'matter_type' => 'required|in:Appeal,Work Visa,Student Visa,Spouse Visa,Visitor Visa,Settlement Visa',
        ]);

        $template->title = $request->title;

        // Check if new file uploaded
        if ($request->hasFile('doc_file')) {
            $request->validate([
                'doc_file' => 'required|file|mimes:docx|max:10240',
            ]);

            $uploadedFile = $request->file('doc_file');
            if (!$uploadedFile || !$uploadedFile->isValid()) {
                $message = $uploadedFile
                    ? $uploadedFile->getErrorMessage()
                    : 'Please select a valid DOCX file.';

                return back()->withErrors(['doc_file' => $message])->withInput();
            }

            $template->content = $uploadedFile->get();
        } 
        $template->type = $request->type;
        $template->matter_type = $request->matter_type;
        $template->save();

        return redirect()->route('templates.index')->with('success', 'Template updated successfully');
    }

    // Delete template
    public function destroy(Template $template)
    {
        $template->delete();
        return redirect()->route('templates.index')->with('success', 'Template deleted successfully');
    }
}
