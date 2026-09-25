<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form of Authority</title>
    <style>
        @page {
            size: A4 portrait;
            margin-top: 12mm;
            margin-right: 17.5mm;
            margin-bottom: 22mm;
            margin-left: 17.5mm;
        }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            margin: 0;
            padding: 0;
            background: #fff; 
            color: #000; 
        }
        .header-logo { 
            position: absolute; 
            top: 0; 
            right: 5px; 
            width: 80px; 
            text-align: right;
        }
        .header-logo img {
            width: 80px;
            height: auto;
        }
        .letter-wrap { 
            width: 100%; 
            line-height: 2; 
        }
        .title { 
            text-align: center; 
            text-decoration: underline;
            font-size: 28px; 
            font-weight: bold; 
            margin-top: 140px; 
            margin-bottom: 25px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }
        .lead-line { 
            font-size: 18px; 
            margin-bottom: 16px; 
            text-align: justify; 
            line-height: 1.65; 
        }
        .highlight { 
            font-weight: bold; 
        }
        .signature-table { 
            width: 100%; 
            margin-top: 30px; 
            border-collapse: collapse; 
            page-break-inside: avoid;
        }
        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -19.87mm;
            width: 100%;
            text-align: center;
        }
        .footer-rules {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            height: 1px;
            margin: 0 0 2.3px;
        }
        .footer-firm {
            font-weight: bold;
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            line-height: 14px;
            margin: 0;
        }
        .footer-address {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11.83px;
            line-height: 14px;
            color: #000;
            margin-top: 1.35px;
            white-space: nowrap;
        }
        .footer-logo {
            position: absolute;
            top: -28.2px;
            right: 0;
            width: 70px;
            text-align: right;
        }
        .top-line{
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <!-- Top Right Logo -->
    <div class="header-logo">
        @if(is_file(public_path('images/logo_imigration_law.png')))
            <img src="{{ public_path('images/logo_imigration_law.png') }}" alt="Company Logo">
        @endif
    </div>

    <!-- Fixed Footer -->
    <div class="footer">
        <div class="footer-rules"></div>
        <div class="footer-firm">{{ $lawFirm }}</div>
        <div class="footer-address">{{ $lawFirmAddress }}, Ph. {{ $phone }}, Email: {{ $email }}</div>
        <div class="footer-logo">
            @if(is_file(public_path('images/footer.jpg')))
                <img src="{{ public_path('images/footer.jpg') }}" alt="Immigration Advice Authority" style="width: 69.6px; height: 58px; display: block;">
            @endif
        </div>
    </div>

    <div class="letter-wrap">
        <h1 class="title">FORM OF AUTHORITY</h1>

        <p class="lead-line top-line">
            I, <span class="highlight">{{ $clientFullName }}</span>, 
            date of birth <span class="highlight">{{ $dob }}</span>, 
            national <span class="highlight">{{ $nationality }}</span>, 
            currently residing at <span class="highlight">{{ $formattedAddress ?? trim($address . ' ' . $address2 . ' ' . $city . ' ' . $national) }}</span> 
            hereby authorise and instruct <span class="highlight">{{ $lawFirm }}</span>, 
            <span class="highlight">{{ $lawFirmAddress }}</span> 
            in relation to my <strong>{{ $visaType ?? 'Other' }}{{ ' / Immigration matter' }}</strong>.
        </p>

        <p class="lead-line">
            I further authorise and request that all relevant third parties, including but not limited to the Home Office, 
            UK Visas and Immigration (UKVI), and any other government departments or agencies, 
            disclose and communicate any necessary information directly with 
            <strong>{{ $lawFirm }}</strong> in relation to this matter.
        </p>

        <table class="signature-table" width="100%">
            <tr>
                <td style="padding-bottom: 16px;">
                    <p style="margin: 0 0 35px; font-size: 18px; ">Signed :</p>
                    <div style="border-bottom: 1px solid #000; width: 280px;"></div>
                    <p style="margin: 4px 0 0; font-size: 13px;">(Client's Signature)</p>
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 18px;">
                    <p style="margin: 0; font-size: 18px;">Print Name : <span class="highlight">{{ $clientFullName }}</span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="margin: 0; font-size: 18px;">Dated : {{ $today }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
