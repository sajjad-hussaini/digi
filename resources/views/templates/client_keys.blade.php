<section class="client-keys-panel" aria-labelledby="client-keys-title">
    <div class="client-keys-heading">
        <span class="client-keys-symbol"><i class="fa fa-code" aria-hidden="true"></i></span>
        <div>
            <h3 id="client-keys-title">Supported client keys</h3>
            <p>Click a key to copy it, then paste it wherever you need a client value.</p>
        </div>
        <span class="client-keys-badge">15 keys</span>
    </div>
    <div class="client-keys-grid">
        @foreach ([
            'CLIENT_FIRST_NAME' => 'First name', 'CLIENT_SURNAME' => 'Surname',
            'SALUTATION' => 'Salutation', 'REFERENCE_NUMBER' => 'Reference number',
            'ADDRESS_1' => 'Address line 1', 'ADDRESS_2' => 'Address line 2',
            'CITY' => 'City', 'CLIENT_EMAIL' => 'Email address', 'CLIENT_PHONE' => 'Phone number',
            'CLIENT_DOB' => 'Date of birth', 'CLIENT_GENDER' => 'Gender',
            'CLIENT_PASSPORT_NO' => 'Passport number', 'NATIONALITY' => 'Nationality',
            'COUNTRY' => 'Country', 'DATE' => 'Current date',
        ] as $key => $label)
            <button type="button" class="client-key-card" data-copy-key="[{{ $key }}]" aria-label="Copy [{{ $key }}]" title="Copy [{{ $key }}]">
                <span class="client-key-text"><span class="client-key-label">{{ $label }}</span><code>[{{ $key }}]</code></span>
                <i class="fa fa-copy client-key-icon" aria-hidden="true"></i>
            </button>
        @endforeach
    </div>
    <p class="client-keys-feedback" role="status" aria-live="polite">Client details fill these keys automatically when a document is generated.</p>
</section>
