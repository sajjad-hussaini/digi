<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Client Care Letter – EU Settlement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{ font-family: "Times New Roman", Times, serif; font-size: 14px; line-height: 1.6; }
        .letter-head   { border: 1px solid #000; padding: 10px 15px; margin-bottom: 25px; }
        .section       { font-weight: bold; text-decoration: underline; margin-top: 25px; margin-bottom: 10px; }
        .sign-block    { margin-top: 60px; }
        .footer-text   { font-size: 12px; }
        .logo-top-right {
            position: absolute;
            top: 15px;
            right: 20px;
            width: 70px;           /* Apne logo ke size ke hisab se adjust kar lena */
            height: auto;
            z-index: 10;
        }
    </style>
</head>
<body>
<!-- Logo Top Right Corner -->
  <img src="{{ public_path('images/logo_imigration_law.png') }}" alt="Company Logo" class="logo-top-right">
<div class="container my-4">
 
    <!-- TOP LEFT REFERENCE BOX -->
    <div class="letter-head">
        <strong>Our Ref:</strong> {{ $ourRef ?? '0099' }} &nbsp;&nbsp;&nbsp;
        <strong>Date:</strong> {{ $date ?? now()->format('jS F Y') }}<br>
        <strong>Please ask for:</strong> {{ $adviserName ?? 'Mohamad Salim Kureshi' }}
    </div>

    <!-- ADDRESS -->
    <p>
        <strong>{{ $client->first_name.' '.$client->sir_name ?? 'Mr. Waqas Ahmed' }}</strong><br>
        {{ $clientAddress ?? "goodgate Street\nBolton\nBL3" }}
    </p>

    <!-- SALUTATION -->
    <p><strong>Dar {{ $client->first_name.' '.$client->sir_name ?? 'Mr. Waqas Ahmed' }}</strong></p>

    <!-- SUBJECT -->
    <p><strong>Re: <span style="text-decoration: underline">Your EU Settled Status/Immigration Matter</span></strong></p>

    {!! $request->ILR_vignette_sticker ?? 'Your EU Settled Status/Immigration Matter' !!}

    <!-- SECTION 1 -->
    <div class="section">Instructions Received</div>

    {!! $request->Instructions_Received ?? 'You have instructed me to assist you with an application for an EU Settled Status in the UK.' !!}

    <!-- SECTION 2 -->
    <div class="section">Your initial instructions to me are as follows:</div>
    
    {!! $request->initial_instructions_to_me ?? 'You have informed me that you are a Pakistani national currently residing in the UK with Pre-Settled Status as a close family member of your EEA national wife who holds Settled Status in the UK. You have expressed your intention to apply for Settled Status under the EU Settlement Scheme to secure your permanent residence in the UK.' !!}
    <!-- SECTION 3 -->
    <div class="section">Advice given:</div>
    {!! $request->Advice_given ?? 'I have advised you that as a close family member of an EEA national with Settled Status, you are eligible to apply for Settled Status under the EU Settlement Scheme. I have explained the benefits of obtaining Settled Status, including the right to live and work in the UK without restrictions, access to public services, and the ability to apply for British citizenship in the future.' !!}
    <!-- SECTION 4 -->
    <div class="section">Please provide me below mentioned list of documents (scan copy) as soon as practical:</div>
    {!! $request->mentioned_list_of_documents ?? '<ul>
        <li>Valid passport or travel document</li>
        <li>Proof of relationship to your EEA national spouse (e.g., marriage certificate)</li>
        <li>Evidence of your EEA national spouse\'s Settled Status (e.g., copy of their Settled Status approval letter)</li>
        <li>Proof of residence in the UK (e.g., utility bills, tenancy agreements)</li>
        <li>Any other relevant documents supporting your application</li>
    </ul>' !!}

    <!-- SECTION 5 -->
    <div class="section">Care and conduct</div>
    {!! $request->Care_and_conduct ?? '<p>I am committed to providing you with a professional and efficient service throughout the duration of your case. I will keep you informed of any developments and respond promptly to any queries you may have. Please ensure that you provide me with accurate and complete information to enable me to represent you effectively.</p>' !!}
    <!-- SECTION 6 -->
    <div class="section">Complaints procedure</div>
    {!! $request->Complaints_procedure ?? '<p>If you are dissatisfied with any aspect of my service, please let me know in the first instance so that I can address your concerns. If you remain dissatisfied, you may contact the' !!}
    <p><strong>Immigration Advice Authority Complaints Team<br>
        IAA<br>
        PO Box 567<br>
        Dartford<br>
        DA1 9XW</strong><br>
        <strong>Ph. 0345 000 0046, email: <a href="mailto:complaints@immigrationadviceauthority.gov.uk">complaints@immigrationadviceauthority.gov.uk</a></strong>
    </p>

    <p>The IAA is the public body which regulates immigration advice and services within the UK. The IAA may review your file as part of their regulatory role.</p>

    <!-- SECTION 7 -->
    <div class="section">Your file</div>
    <p>The Immigration Advice Authority (IAA) requires us to keep a Copy/Digitally Scan Copy of your case file for up to 6 years after your case is closed. After that this may be destroyed, unless you make arrangements to collect it from us thereafter.</p>

    <!-- SECTION 8 -->
    <div class="section">Professional Fees</div>
    {!! $request->Professional_Fees ?? '<p>As discussed, my professional fees for handling your EU Settled Status application will be £500. This fee covers all aspects of the application process, including initial consultation, document review, application preparation, and submission. Please note that this fee does not include any government application fees or additional costs that may arise during the process.</p>' !!}
    <!-- SECTION 9 -->
    <div class="section">Office Opening times</div>
    <p>Please note that our office is open from <strong>10:00 am to 6:00 pm Monday to Friday</strong> excluding public holidays <strong>pre-booked appointment basis only</strong>.</p>

    <!-- IMPORTANT NOTICE -->
    <p class="text-danger fw-bold">THIS LETTER IS AN IMPORTANT DOCUMENT. PLEASE KEEP IT IN A SAFE PLACE FOR FUTURE REFERENCE.</p>

    <!-- CLOSING -->
    <p>We look forward to assisting you in this matter.</p>

    <div class="sign-block">
        <p>Yours faithfully,</p>
        <p>__________________________________<br>
            <strong>{{ $adviserName ?? 'Mohamad Salim Kureshi' }}</strong><br>
            UK Immigration Law
        </p>
    </div>

    <!-- CLIENT SIGNATURE BLOCK -->
    <p class="mt-4">Please sign, date this letter, and return it to us to indicate that you understand and agree to its contents.</p>
    <p>
        Client Signature: ___________________________ &nbsp;&nbsp;
        Date: {{ $date ?? now()->format('jS F Y') }}
    </p>

</div><!-- /container -->

</body>
</html>