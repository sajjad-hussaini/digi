(() => {
    'use strict';
    const root = document.getElementById('template-preview');
    if (!root) return;
    const pages = document.getElementById('template-preview-pages');
    const status = document.getElementById('template-preview-status');
    const mode = document.getElementById('template-preview-mode');
    const next = document.getElementById('template-preview-next');
    const rows = document.getElementById('template-replacements');
    const add = document.getElementById('template-add-replacement');
    let rowId = Math.max(-1, ...Array.from(rows.querySelectorAll('.replacement-find'), input => Number(input.id.replace('find-', '')))) + 1;
    const upload = document.getElementById('doc_file');
    let baseline = null, version = 0, timer, matches = [], current = -1;

    function refresh() {
        if (!baseline) return;
        const scroll = pages.scrollTop;
        pages.replaceChildren(baseline.cloneNode(true));
        matches = [];
        current = -1;
        const rules = Array.from(rows.querySelectorAll('.replacement-row'), row => ({
            find: row.querySelector('.replacement-find').value,
            replacement: row.querySelector('.replacement-value').value,
            status: row.querySelector('.replacement-status'), count: 0
        }));
        const active = rules.filter(rule => rule.find !== '');
        if (active.length) {
            pages.querySelectorAll('p').forEach(paragraph => {
                const walker = document.createTreeWalker(paragraph, NodeFilter.SHOW_TEXT);
                const nodes = [];
                let text = '', node;
                while ((node = walker.nextNode())) {
                    if (node.parentElement.closest('p') !== paragraph) continue;
                    nodes.push({ node, start: text.length, length: node.textContent.length });
                    text += node.textContent;
                }
                const offsets = [];
                let cursor = 0;
                while (cursor < text.length) {
                    let match = null;
                    active.forEach(rule => {
                        const at = text.indexOf(rule.find, cursor);
                        if (at !== -1 && (!match || at < match.start)) match = { start: at, rule };
                    });
                    if (!match) break;
                    offsets.push(match);
                    match.rule.count++;
                    cursor = match.start + match.rule.find.length;
                }
                const paragraphMatches = [];
                offsets.reverse().forEach(({ start, rule }) => {
                    const end = start + rule.find.length;
                    let anchor;
                    // Reverse order keeps original text-node offsets valid.
                    [...nodes].reverse().forEach(entry => {
                        if (entry.start + entry.length <= start || entry.start >= end) return;
                        const from = Math.max(0, start - entry.start);
                        const to = Math.min(entry.length, end - entry.start);
                        const range = document.createRange();
                        range.setStart(entry.node, from);
                        range.setEnd(entry.node, to);
                        const old = document.createElement(mode.value === 'changes' ? 'del' : 'mark');
                        old.style.background = mode.value === 'changes' ? '#ffe0e0' : '#fff0a8';
                        old.style.color = '#222';
                        old.appendChild(range.extractContents());
                        range.insertNode(old);
                        anchor = old;
                        if (mode.value === 'result') old.hidden = true;
                        if (entry.start <= start && mode.value !== 'original') {
                            const added = document.createElement(mode.value === 'changes' ? 'ins' : 'mark');
                            added.textContent = rule.replacement || (mode.value === 'changes' ? ' [removed]' : '');
                            added.style.background = '#d7f5df';
                            added.style.color = '#153d20';
                            old.after(added);
                            anchor = added;
                        }
                    });
                    if (anchor) paragraphMatches.unshift(anchor);
                });
                matches.push(...paragraphMatches);
            });
        }
        next.disabled = matches.length === 0;
        const seen = new Set();
        rules.forEach(rule => {
            let message = '';
            if (!rule.find && rule.replacement) message = 'Enter the text or key to find.';
            else if (rule.find && seen.has(rule.find)) message = 'Duplicate Find text: use each key only once.';
            else if (rule.find) message = rule.count ? rule.count + ' match(es) in preview.' : 'No match in preview. Check spelling or overlapping rows.';
            rule.status.textContent = message;
            if (rule.find) seen.add(rule.find);
        });
        status.textContent = active.length
            ? (matches.length ? matches.length + ' highlighted match(es) in this preview.' : 'No matching text found. Check spelling and capitalization.')
            : 'Document ready. Enter Find text to highlight matching words.';
        pages.scrollTop = scroll;
    }

    async function load() {
        const requestVersion = ++version;
        baseline = null;
        matches = [];
        next.disabled = true;
        pages.replaceChildren();
        status.textContent = 'Loading document preview...';
        try {
            if (!window.docx || !window.JSZip) throw new Error('Preview library unavailable. Refresh the page to try again.');
            const file = upload.files[0];
            let blob;
            if (file) {
                if (!/\.docx$/i.test(file.name) || file.size > 10485760) throw new Error('Select a DOCX file no larger than 10 MB.');
                blob = file;
            } else {
                const response = await fetch(root.dataset.url, { credentials: 'same-origin' });
                if (!response.ok || !response.headers.get('content-type')?.includes('application/vnd.openxmlformats')) throw new Error('Unable to load the document. Refresh the page and check your session.');
                blob = await response.blob();
            }
            const container = document.createElement('div');
            await window.docx.renderAsync(blob, container, null, {
                className: 'template-docx', useBase64URL: true,
                renderAltChunks: false, renderHeaders: true, renderFooters: true,
                renderFootnotes: true, renderEndnotes: true
            });
            if (requestVersion !== version) return;
            // The preview is read-only; document links must not navigate the editor.
            container.querySelectorAll('a').forEach(link => link.removeAttribute('href'));
            baseline = container;
            refresh();
        } catch (error) {
            if (requestVersion !== version) return;
            status.textContent = 'Preview could not be loaded. ' + error.message + ' You can download the DOCX to review it.';
        }
    }
    rows.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(refresh, 200);
    });
    add.addEventListener('click', () => {
        clearTimeout(timer);
        mode.value = 'result';
        refresh();
        const emptyRow = Array.from(rows.querySelectorAll('.replacement-row')).find(row =>
            row.querySelector('.replacement-find').value === ''
        );
        if (emptyRow) {
            emptyRow.querySelector('.replacement-find').focus();
            return;
        }
        if (rows.children.length >= 100) return;
        const row = document.createElement('div');
        row.className = 'row replacement-row';
        row.style.marginBottom = '12px';
        row.innerHTML = `<div class="col-sm-5"><label for="find-${rowId}">Find text / key</label>
            <input id="find-${rowId}" name="replacements[${rowId}][find]" class="form-control replacement-find" maxlength="2000"></div>
            <div class="col-sm-5"><label for="replace-${rowId}">Replace with</label>
            <input id="replace-${rowId}" name="replacements[${rowId}][replace]" class="form-control replacement-value" maxlength="10000"></div>
            <div class="col-sm-2"><button type="button" class="btn btn-default replacement-remove" style="margin-top:25px">Remove row</button></div>
            <div class="col-sm-12"><small class="replacement-status"></small></div>`;
        rowId++;
        rows.appendChild(row);
        row.querySelector('input').focus();
        refresh();
    });
    rows.addEventListener('click', event => {
        if (!event.target.closest('.replacement-remove')) return;
        const row = event.target.closest('.replacement-row');
        if (rows.children.length === 1) row.querySelectorAll('input').forEach(input => input.value = '');
        else row.remove();
        add.disabled = false;
        refresh();
    });
    mode.addEventListener('change', refresh);
    upload.addEventListener('change', load);
    document.getElementById('template-preview-retry').addEventListener('click', load);
    next.addEventListener('click', () => {
        current = (current + 1) % matches.length;
        matches[current].scrollIntoView({ behavior: 'smooth', block: 'center' });
        status.textContent = 'Match ' + (current + 1) + ' of ' + matches.length + ' in this preview.';
    });
    load();
})();
