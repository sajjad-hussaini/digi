<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Client Cover Letter</title>

  <!-- Bootstrap 5 (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* A4 print friendly layout */
    @page { size: A4; margin: 20mm; }
    body {
      font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      background: #f8f9fa;
      padding: 20px;
    }
    .sheet {
      max-width: 800px;
      margin: 0 auto;
      background: #fff;
      padding: 36px 44px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.06);
      border-radius: 6px;
    }
    .small-line { font-size: 14px; color: #333; }
    .address-block { white-space: pre-line; margin-top: 18px; font-size: 15px; }
    .subject { margin-top: 18px; font-weight:700; }
    ul.doc-list { margin-left: 1.1rem; }
    .signature { margin-top: 36px; }
    .footer {
      border-top: 1px solid #e9ecef;
      padding-top: 12px;
      margin-top: 40px;
      font-size: 14px;
      color: #444;
    }

    /* Print adjustments */
    @media print {
      body { background: #fff; padding: 0; }
      .sheet { box-shadow: none; border-radius: 0; margin: 0; }
    }
  </style>
</head>
<body>
  <div class="sheet">

    <div class="row">
      <div class="col-6 small-line"><strong>Our Ref:</strong> {{ $ref ?? '' }}</div>
      <div class="col-6 small-line text-end"><strong>Date:</strong> {{ $date ?? now()->format('jS F Y') }}</div>
    </div>

    <p class="small-line"><strong>Please ask for:</strong> {{ $staff ?? '' }}</p>

    <div class="address-block">
{{ $client->first_name }} {{ $client->sir_name }}
{{ $client->street ?? '' }}
{{ $client->city ?? '' }}
{{ $client->postcode ?? '' }}
    </div>

    <br>

    <p>Dear {{ $client->first_name }} {{ $client->sir_name }},</p>

    <p class="subject">Re: Replace of your ILR vignette sticker with a BRP card/e-Visa / Immigration Matter</p>

    <p>
      We write to inform you of the outcome of your ILR vignette sticker with a BRP card/e-Visa. We are glad to inform you that your application for a No Time Limit endorsement has been successful and the Home Office has granted you e-Visa to live permanently in the United Kingdom.
    </p>

    <p>
      As it was an online application, we submitted the scanned documents listed below to the Home Office. All the original supporting documents were returned to you before the biometric appointment in Manchester.
    </p>

    <p><strong>List of Original Documents Returned to You:</strong></p>
    <ul class="doc-list">
      <li>Your valid Pakistani passport</li>
      <li>All expired passports since entering the UK</li>
      <li>Proof of addresses</li>
      <li>Bank statements</li>
    </ul>

    <p>
      Further, we confirm that we do not hold any original documents and only retain scanned copies in our file. Our accounts show that all fees have been paid in full and there are no outstanding balances. We will now proceed to close your file.
    </p>

    <p><strong>Storage of your file</strong></p>
    <p>
      Your case file will be stored for a minimum of six years from the date above, after which the file will be securely shredded. If you object to the destruction of your file after this period please notify us; otherwise the file will be destroyed automatically on the stated date.
    </p>

    <p>
      If you need any additional copies from your scanned files while the file is archived, we will photocopy them for you free of charge.
    </p>

    <p>
      Should you require any further assistance, please do not hesitate to contact us.
    </p>

    <div class="signature">
      <p>Yours sincerely,</p>
      <p><strong>Mohamad Salim Kureshi</strong><br>UK Immigration Law</p>
    </div>

    <div class="footer">
      <strong>UK Immigration Law</strong><br>
      1st Floor, 236 ST. Helens Road, Bolton BL3 4EB<br>
      Ph: 07777328028 &nbsp;|&nbsp; Email: qureshisalim@yahoo.com
    </div>

  </div>
</body>
</html>
