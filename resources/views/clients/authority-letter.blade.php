<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Form of Authority</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Paper-like styling */
    body {
      background: #f8f9fa;
      padding: 30px;
      font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .letter-wrap {
      max-width: 800px;
      margin: 0 auto;
      background: #fff;
      padding: 40px 48px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.06);
      border-radius: 6px;
    }
    .logo-top-right {
      position: absolute;
      top: 20px;
      right: 30px;
      width: 80px;           /* Apne logo ke size ke hisab se adjust kar lena */
      height: auto;
      z-index: 10;
    }
    h1.title {
      text-align: center;
      font-size: 22px;
      text-decoration: underline;
      margin-bottom: 24px;
      font-weight: 700;
    }
    .lead-line { line-height: 1.6; }
    /* .highlight { background: rgba(255,235,59,0.35); padding: 0 4px; border-radius: 2px; } */
    .footer-line { border-top: 1px solid #e9ecef; margin-top: 56px; padding-top: 10px; font-size: 13px; color: #6c757d; }
    .signed-area { margin-top: 36px; }
    @media print {
      body { background: #fff; padding: 0; }
      .letter-wrap { box-shadow: none; border-radius: 0; }
    }
  </style>
</head>
<body>
  <!-- Logo Top Right Corner -->
  <img src="{{ public_path('images/logo_imigration_law.png') }}" alt="Company Logo" class="logo-top-right">
  <div class="letter-wrap">
    <h1 class="title">FORM OF AUTHORITY</h1>

    <p class="lead-line">I, <span class="highlight">{{ $client->first_name.' '.$client->sir_name ?? 'Mr.Waqas' }}</span>, date of birth <span class="highlight">{{ $client->dob ?? '01.09.1997' }}</span>, national <span class="highlight">{{ $client->country ?? 'Pakistan' }}</span>, currently residing at <span class="highlight">{{ $client->address ?? '53 Woodgate Street, Bolton BL3 2HN' }}</span> hereby authorise and instruct <span class="highlight">{{ $lawFirm ?? 'UK Immigration Law' }}</span>, <span class="highlight">{{ $lawFirmAddress ?? '1st floor, 236 St. Helens Road, Bolton BL3 4EB' }}</span> in relation to my <strong>Settled Status application / Immigration matter</strong>.</p>

    <p class="lead-line">I further authorise and request that all relevant third parties, including but not limited to the Home Office, UK Visas and Immigration (UKVI), and any other government departments or agencies, disclose and communicate any necessary information directly with <strong>{{ $lawFirm ?? 'UK Immigration Law' }}</strong> in relation to this matter.</p>

    <div class="signed-area">
      <div class="row">
        <div class="col-6">
          <p><strong>Signed</strong> :</p>
          <div style="height:70px; border-bottom:1px dashed #ddd; margin-top:6px;"></div>
        </div>
        <div class="col-6">
          <p><strong>Print Name</strong> : <span class="highlight">{{ $client->first_name.' '.$client->sir_name ?? 'Mr. Waqas Ahmed' }}</span></p>
          <p><strong>Dated</strong> : {{ $date ?? now()->format('jS F Y') }}</p>
        </div>
      </div>
    </div>

    <div class="footer-line d-flex justify-content-between align-items-center">
      <div>
        <strong>{{ $lawFirm ?? 'UK Immigration Law' }}</strong><br>
        {{ $lawFirmAddress ?? '1st floor, 236 St. Helens Road, Bolton BL3 4EB' }}<br>
        Phone: {{ $phone ?? '07777328028' }} | Email: {{ $email ?? 'example@domain.com' }}
      </div>
      
      <div style="text-align:right; font-size:12px; color:#adb5bd;">Immigration Advice Authority</div>
    </div>
  </div>

  <!-- Optional JS for Bootstrap (not required for print) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
