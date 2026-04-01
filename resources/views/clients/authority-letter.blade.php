<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form of Authority</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            padding: 40px; 
            background: white; 
            color: #000; 
        }
        .logo-top-right { 
            position: absolute; 
            top: 20px; 
            right: 40px; 
            width: 70px; 
        }
        .letter-wrap { 
            max-width: 800px; 
            margin: 0 auto; 
            line-height: 1.8; 
        }
        .title { 
            text-align: center; 
            font-size: 24px; 
            font-weight: bold; 
            margin-bottom: 40px; 
            text-transform: uppercase; 
        }
        .lead-line { 
            font-size: 16px; 
            margin-bottom: 20px; 
            text-align: justify; 
        }
        .highlight { 
            font-weight: bold; 
        }
        .signed-area { 
            margin-top: 60px; 
        }

        /* Footer Styling - Exactly like screenshot */
        .footer-container {
            margin-top: 200px;
            text-align: center;
            font-size: 12px;
            position: relative;
        }
        .footer-line {
            border-top: 1px solid #000;
        }
        .footer-firm-name {
            font-weight: bold;
            font-size: 14px;
        }
        .footer-contact {
            margin-bottom: 10px;
        }
        .footer-logo {
            margin-left: 600px;
            transform: translateY(-100%);
            width: 80px; 
        }
    </style>
</head>
<body>
    <!-- Top Right Logo -->
    <img src="{{ public_path('images/logo_imigration_law.png') }}" alt="Company Logo" class="logo-top-right">

    <div class="letter-wrap">
        <h1 class="title">FORM OF AUTHORITY</h1>

        <p class="lead-line">
            I, <span class="highlight">{{ $clientFullName }}</span>, 
            date of birth <span class="highlight">{{ $dob }}</span>, 
            national <span class="highlight">{{ $nationality }}</span>, 
            currently residing at <span class="highlight">{{ $address }}</span> 
            hereby authorise and instruct <span class="highlight">{{ $lawFirm }}</span>, 
            <span class="highlight">{{ $lawFirmAddress }}</span> 
            in relation to my <strong>{{$visaType ?? 'Other'}}</strong>.
        </p>

        <p class="lead-line">
            I further authorise and request that all relevant third parties, including but not limited to the Home Office, 
            UK Visas and Immigration (UKVI), and any other government departments or agencies, 
            disclose and communicate any necessary information directly with 
            <strong>{{ $lawFirm }}</strong> in relation to this matter.
        </p>

        <div class="signed-area">
            <div class="row">
                <div class="col-6">
                    <p><strong>Signed</strong> :</p>
                    <div style="height:80px; border-bottom:1px solid #000; margin-top:10px;"></div>
                    <p style="margin-top:10px; font-size:14px;">(Client's Signature)</p>
                </div>
                <div class="col-6">
                    <p><strong>Print Name</strong> : <span class="highlight">{{ $clientFullName }}</span></p>
                    <p><strong>Dated</strong> : {{ $today }}</p>
                </div>
            </div>
        </div>

        <!-- Exact Footer as per Screenshot -->
        <div class="footer-container">
            <div class="footer-line" style="margin-bottom: 1px"></div>
            <div class="footer-line"></div>
            
            <div class="footer-firm-name">
                <strong>{{ $lawFirm }}</strong>
            </div>
            <div class="footer-contact">
                {{ $lawFirmAddress }}, Ph. {{ $phone }}, Email: {{ $email }}
            </div>

            <!-- Right side logo with "Immigration Advice Authority" -->
            <img src="{{ public_path('images/footer.jpg') }}" alt="Immigration Advice Authority" class="footer-logo">
            <!-- Agar image mein text included nahi hai to neeche text bhi add kar sakte ho -->
            <!-- <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); font-size: 10px; color: #666;">
                Immigration<br>Advice Authority
            </div> -->
        </div>
    </div>
</body>
</html>