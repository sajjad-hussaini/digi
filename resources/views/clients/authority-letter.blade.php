<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form of Authority</title>
    <style>
        @page {
            size: A4 portrait;
            margin-top: 15mm;
            margin-right: 20mm;
            margin-bottom: 22mm;
            margin-left: 20mm;
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
            right: 0; 
            width: 70px; 
            text-align: right;
        }
        .header-logo img {
            width: 65px;
            height: auto;
        }
        .letter-wrap { 
            width: 100%; 
            line-height: 1.65; 
        }
        .title { 
            text-align: center; 
            font-size: 20px; 
            font-weight: bold; 
            margin-top: 15px; 
            margin-bottom: 25px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }
        .lead-line { 
            font-size: 14px; 
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
            bottom: -15mm;
            width: 100%;
        }
        .footer-line-top {
            border-top: 1px solid #000;
            margin-bottom: 2px;
        }
        .footer-line-bottom {
            border-top: 1px solid #000;
            margin-bottom: 5px;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-firm {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 2px;
        }
        .footer-address {
            font-size: 10px;
            color: #222;
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
        <div class="footer-line-top"></div>
        <div class="footer-line-bottom"></div>
        <table class="footer-table" width="100%">
            <tr>
                <td width="75" style="width: 75px;"></td>
                <td align="center" style="vertical-align: middle; text-align: center;">
                    <div class="footer-firm">{{ $lawFirm }}</div>
                    <div class="footer-address">{{ $lawFirmAddress }}, Ph. {{ $phone }}, Email: {{ $email }}</div>
                </td>
                <td width="75" align="right" style="width: 75px; vertical-align: middle; text-align: right;">
                    @if(is_file(public_path('images/footer.jpg')))
                        <img src="{{ public_path('images/footer.jpg') }}" alt="IAA Logo" style="width: 70px; height: auto; display: inline-block;">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="letter-wrap">
        <h1 class="title">FORM OF AUTHORITY</h1>

        <p class="lead-line">
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
                    <p style="margin: 0 0 35px; font-size: 14px; font-weight: bold;">Signed :</p>
                    <div style="border-bottom: 1px solid #000; width: 280px;"></div>
                    <p style="margin: 4px 0 0; font-size: 13px;">(Client's Signature)</p>
                </td>
            </tr>
            <tr>
                <td style="padding-bottom: 14px;">
                    <p style="margin: 0; font-size: 14px;"><strong>Print Name :</strong> <span class="highlight">{{ $clientFullName }}</span></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="margin: 0; font-size: 14px;"><strong>Dated :</strong> {{ $today }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>