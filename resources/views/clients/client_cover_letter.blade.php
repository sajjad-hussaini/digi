<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Covering Letter – EU Settled Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            line-height: 1.7;
            background: #fff;
        }
        .top-box{
            border: 1px solid #000;
            padding: 8px 12px;
            margin-bottom: 25px;
        }
        .underline{
            text-decoration: underline;
        }
        .sign-block{
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container my-4">

    <!-- TOP LEFT BOX -->
    <div class="top-box d-inline-block">
        <strong>Our Ref:</strong> {{ $ourRef ?? '' }}<br>
        <strong>UAN:</strong> {{ $uan ?? '' }}<br>
        <strong>Date:</strong> {{ $letterDate ?? '19th November 2025' }}<br>
        <strong>Please ask for:</strong> {{ $askFor ?? '' }}
    </div>

    <!-- LOGO (placeholder) -->
    <div class="text-end mb-3">
        <img src="{{ asset('img/logo.png') }}" alt="UK Immigration Law" height="80">
    </div>

    <!-- ADDRESSEE -->
    <p>
        <strong>EU Settlement Scheme</strong><br>
        PO Box 2076<br>
        Liverpool<br>
        L69 3PG
    </p>

    <!-- SUBJECT -->
    <p class="mt-4">
        <strong>RE: <span class="underline">{{ $clientName ?? 'Mr. Ahmed, D.O.B: 03-09-1999, Pakistan' }}</span></strong><br>
        <strong><span class="underline">{{ $subject ?? 'Application for EU Settled Status.' }}</span></strong>
    </p>

    <!-- BODY -->
    <p>We are writing in relation to the aforementioned client, whom we represent in his immigration matter.</p>

    <p>We would like to inform you that our client is a Pakistani national currently residing in the United Kingdom with his wife. His wife, a Portuguese national, sponsored him to join her in the UK. He subsequently entered the country through Manchester Airport on <strong>{{ $entryDate ?? '11 July 2020' }}</strong> on an EEA Family Permit. <strong>Since his arrival, he has continued to reside in the UK, with the exception of one visit to Pakistan between {{ $absencePeriod ?? '5 January 2022 and 3 March 2022' }}</strong>.</p>

    <p>Following his entry into the UK, our client applied for a National Insurance Number. However, due to the Covid-19 pandemic lockdown, he was unable to open a bank account or commence employment. As a result, he is unable to provide proof of address for the period between {{ $gapPeriod ?? 'September 2020 and May 2021' }}. Nevertheless, <strong>his passport entry and exit stamps clearly demonstrate his presence in the UK and confirm that he has not been absent from the country for more than six months during the relevant qualifying period</strong>.</p>

    <p>Our client currently holds Pre-Settled Status in the UK. His wife is a Portuguese national with EU Settled Status and has been exercising her treaty rights by living and working in the UK. The couple met in Pakistan and were married on <strong>{{ $marriageDate ?? '30 August 2018' }}</strong> in {{ $marriagePlace ?? 'Pakistan' }}. They currently reside together at <strong>{{ $currentAddress ?? '43 G Street, Bolton BL2 2HN' }}</strong>, in a privately rented property. Our client is now applying for EU Settled Status in order to permanently reside in the UK with his wife.</p>

    <!-- ENCLOSURES -->
    <p class="section">Please find enclosed the following supporting documents for your kind consideration:</p>
    <ul>
        <li>Valid Passport of the Applicant</li>
        <li>Biometric Residence Card</li>
        <li>EU Pre-Settled Status</li>
        <li>Wife's Portuguese Passport (Sponsor)</li>
        <li>Wife's Settled Status</li>
        <li>Marriage Certificate</li>
        <li>Proof of address since 2020 to 2025</li>
    </ul>

    <p>Our client lives a happy, safe and peaceful life with his wife, who fully supports this application.</p>

    <p>In light of the above circumstances, we respectfully request that our client be granted Settled Status in the United Kingdom. We thank you in advance for your time and cooperation.</p>

    <p>If you require any further information, please do not hesitate to contact us.</p>

    <!-- SIGN-OFF -->
    <div class="sign-block">
        <p>Yours faithfully,</p>
        <p><strong>UK Immigration Law</strong></p>
    </div>

</div><!-- /container -->

</body>
</html>