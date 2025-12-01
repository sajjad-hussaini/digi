<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Client Closure Letter</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; padding:35px; font-family:"Segoe UI",sans-serif; }
    .letter-box { max-width:800px; margin:auto; background:#fff; padding:45px; border-radius:6px; box-shadow:0 5px 18px rgba(0,0,0,0.08); }
    .ref-line { font-size:15px; margin-bottom:3px; }
    .address-block { margin-top:25px; white-space:pre-line; font-size:16px; }
    .footer { border-top:1px solid #ddd; margin-top:60px; padding-top:12px; font-size:14px; }
  </style>
</head>
<body>
<div class="letter-box">

  <p class="ref-line"><strong>Our Ref:</strong> {{ $client->id ?? '2537' }}</p>
  <p class="ref-line"><strong>Date:</strong> {{ $client->date }}</p>
  <p class="ref-line"><strong>Please ask for:</strong> {{ $staff ?? '' }}</p>

  <div class="address-block">
{{ $client->first_name }}
{{ $client->address }}
{{ $client->postcode ?? 'BL3 2HN' }}
  </div>

  <br>

  <p>Dear {{ $client->first_name }},</p>

  <p><strong>Re: Replace of your ILR vignette sticker with a BRP card/e‑Visa / Immigration Matter</strong></p>

  <p>
    We write to inform you of the outcome of your ILR vignette sticker with a BRP card/e‑Visa. We are glad to inform you that your application for a No Time Limit endorsement has been successful and the Home Office has granted you e‑Visa to live permanently in the United Kingdom.
  </p>

  <p>
    As it was an online application, we submitted the below‑mentioned scanned documents to the Home Office, and all the original supporting documents were returned to you before the biometric fingerprint appointment in Manchester, UK.
  </p>

  <p><strong>List of Original Documents Returned to You:</strong></p>
  <ul>
    <li>Your valid Pakistani passport</li>
    <li>All expired passports since entering the UK</li>
    <li>Your proof of addresses</li>
    <li>Bank statements</li>
  </ul>

  <p>
    Further, I would like to inform you that we do not hold any original documents; we only kept scanned copies of your file. Our accounts show that all fees have been fully received from you, and there are no pending payments. We are now proceeding to close your file.
  </p>

  <p>
    We hope that the service you received from our firm was satisfactory and met your expectations. Should you require any further assistance, please do not hesitate to contact us.
  </p>

  <p><strong>Storage of Your File</strong></p>

  <p>
    As your matter has concluded, we are closing your case file and storing all information and documents related to your case for a minimum of six years. After six years, the file will be shredded.
  </p>

  <p>
    If you object to the file being shredded after six years, please inform us. If we do not hear from you, the file will be shredded automatically on the date mentioned.
  </p>

  <p>
    During the storage period, if you require copies of any digitally scanned documents, we will provide photocopies free of cost.
  </p>

  <p>
    Should you require our services in the future, please do not hesitate to contact us.
  </p>

  <p>Yours sincerely,<br><br><strong>Mohamad Salim Kureshi</strong><br>UK Immigration Law</p>

  <div class="footer">
    <strong>UK Immigration Law</strong><br>
    1st Floor, 236 ST. Helens Road, Bolton BL3 4EB<br>
    Ph: 07777328028 &nbsp;|&nbsp; Email: qureshisalim@yahoo.com
  </div>

</div>
</body>
</html>
