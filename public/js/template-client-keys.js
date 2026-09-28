(() => {
    'use strict';
    const panel = document.querySelector('.client-keys-panel');
    if (!panel) return;
    const feedback = panel.querySelector('.client-keys-feedback');
    const timers = new WeakMap();

    function fallbackCopy(text) {
        const previousFocus = document.activeElement;
        const input = document.createElement('textarea');
        input.value = text;
        input.readOnly = true;
        input.style.cssText = 'position:fixed;left:-9999px;top:0;';
        document.body.appendChild(input);
        input.select();
        let copied = false;
        try { copied = document.execCommand('copy'); }
        finally {
            input.remove();
            if (previousFocus) previousFocus.focus({ preventScroll: true });
        }
        if (!copied) throw new Error('Copy unavailable');
    }

    panel.addEventListener('click', async event => {
        const button = event.target.closest('[data-copy-key]');
        if (!button) return;
        const key = button.dataset.copyKey;
        try {
            try {
                if (!navigator.clipboard || !window.isSecureContext) throw new Error('Use fallback');
                await navigator.clipboard.writeText(key);
            } catch (_) {
                fallbackCopy(key);
            }
            clearTimeout(timers.get(button));
            button.classList.add('is-copied');
            button.querySelector('i').className = 'fa fa-check client-key-icon';
            feedback.textContent = key + ' copied! Paste it into your template or replacement field.';
            timers.set(button, setTimeout(() => {
                button.classList.remove('is-copied');
                button.querySelector('i').className = 'fa fa-copy client-key-icon';
            }, 2000));
        } catch (_) {
            feedback.textContent = 'Copy was blocked. Select and copy this key manually: ' + key;
        }
    });
})();
