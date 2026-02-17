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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $template = Template::findOrFail($id);
        if($template)
        {
            $template->delete();
        }

        return redirect()->route('templates.index');
    }
}
