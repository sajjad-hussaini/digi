<div class="col-sm-12" id="template-preview" data-url="{{ route('templates.download', $template->id) }}" style="margin-top:24px">
    <h3>Template preview</h3>
    <p>Type in Find text and Replace with to see each change before saving.
        <mark style="background:#fff0a8">Yellow</mark> marks matching text;
        <del style="background:#ffe0e0">red</del> shows removed text and
        <ins style="background:#d7f5df">green</ins> shows new text.</p>
    <div class="form-group">
        <label for="template-preview-mode">View:</label>
        <select id="template-preview-mode" class="form-control" style="display:inline-block;width:auto">
            <option value="changes">Show changes</option>
            <option value="original">Original document</option>
            <option value="result">After replacement</option>
        </select>
        <button type="button" class="btn btn-default" id="template-preview-next" disabled>Next match</button>
        <button type="button" class="btn btn-default" id="template-preview-retry">Reload preview</button>
    </div>
    <p id="template-preview-status" role="status" aria-live="polite">Loading document preview...</p>
    <p class="text-muted">Preview only; changes are saved with Save Changes. Word pagination may differ. Repeated headers can appear on multiple pages.</p>
    <div id="template-preview-pages" style="max-height:750px;overflow:auto;background:#e8ebef;border:1px solid #ccd1d8"></div>
</div>
