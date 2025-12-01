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
    </style>
</head>
<body>

<div class="container my-4">

    <!-- TOP LEFT REFERENCE BOX -->
    <div class="letter-head">
        <strong>Our Ref:</strong> {{ $ourRef ?? '0099' }} &nbsp;&nbsp;&nbsp;
        <strong>Date:</strong> {{ $letterDate ?? '29th October 2025' }}<br>
        <strong>Please ask for:</strong> {{ $adviserName ?? 'Mohamad Salim Kureshi' }}
    </div>

    <!-- ADDRESS -->
    <p>
        <strong>{{ $clientName ?? 'Mr. XXXXXXXX' }}</strong><br>
        {{ $clientAddress ?? "goodgate Street\nBolton\nBL3" }}
    </p>

    <!-- SALUTATION -->
    <p><strong>Dar {{ $clientName ?? 'XXXXXXXX' }}</strong></p>

    <!-- SUBJECT -->
    <p><strong>Re: <span style="text-decoration: underline">Your EU Settled Status/Immigration Matter</span></strong></p>

    <!-- OPENING -->
    <p>Thank you very much for instructing UK Immigration Law. We are registered with the Immigration Advice Authority (IAA). We are authorised to provide immigration advice and services in the categories of Asylum &amp; Protection and Immigration. Our registration number is <strong>{{ $iaaNo ?? 'XXXXXXXX' }}</strong>.</p>

    <p>I am writing to confirm our recent discussion on <strong>{{ $discussionDate ?? '16th October 2025' }}</strong>. Please read this letter carefully to make sure that I have understood what you want and that you understand what is going to happen.</p>

    <!-- SECTION 1 -->
    <div class="section">Instructions Received</div>
    <p>You have instructed me to assist you with an application for an EU Settled Status in the UK.</p>
    <p>I have now opened a file under the above reference, which you should quote each time you contact our office.</p>

    <!-- SECTION 2 -->
    <div class="section">Your initial instructions to me are as follows:</div>
    <p>As per our recent discussion you have informed me that you are Pakistani national, living in the UK with your wife. Your wife sponsored you to join her in the UK and you entered the UK on an EEA Family permit on <strong>{{ $entryDate ?? '11.07.2020' }}</strong> at Manchester airport and since then you are continuously living in the UK and never been out of the UK for more than 6 months in the eligible period.</p>

    <p>You informed me that currently you have Pre-Settled Status in the UK which is valid till <strong>{{ $preSettledExpiry ?? '09th December 2025' }}</strong> and you also have a Biometric Residence Card which is also valid till <strong>{{ $brcExpiry ?? '09th December 2025' }}</strong>. Your wife is Portuguese national and also holds EU Settled Status, exercising her treaty rights by living and working in the UK. You met your wife in Pakistan and married on <strong>{{ $marriageDate ?? '05.07.2018' }}</strong> in {{ $marriagePlace ?? 'Gujarat, Pakistan' }}. You are currently residing with your wife at the above address and you are both working full-time. The property where you live is rented by you and you want to apply for EU Settled Status under the EU Settlement Scheme to live permanently in the UK with your wife and child.</p>

    <!-- SECTION 3 -->
    <div class="section">Advice given:</div>
    <p>Based on the information you provided, I confirm that I am happy to assist with your proposed application for EEA Settled Status as a close family member of an EEA or Swiss national under the EU Settlement Scheme, allowing you to live permanently in the UK with your wife.</p>

    <p>I advised you that, based on what you have told me, you meet the eligibility criteria to be issued Settled Status in the UK. As your Biometric Residence Card is valid, I will use this card as an identity document to apply for your Settled Status, so you do not need to provide your fingerprints. The Home Office has now replaced BRP/BRC with e-Visas, so you will not receive a new physical BRC card and you will get a digital copy of Settled Status.</p>

    <p>What you will need to show to succeed in getting your Settled Status in the UK is that you will need to meet certain requirements and prove certain facts. Firstly, you will need to show that your wife is an EEA national, and you have been granted Pre-Settled Status, but these will be shown by your Biometric Residence Card, Pre-Settled Status and your wife's passport and Settled Status, so no problem here. And of course, you will need to demonstrate certain facts about your relationship with your wife; this means you need to prove by your marriage certificate, and you will need to show that you are living in the UK with your partner.</p>

    <p>I also advised you that I will arrange for your application to be submitted online to the Home Office. I confirm that I will begin preparing your application upon receipt of the additional information requested and aim to complete the preparation within <strong>10 working days</strong> thereafter. I will review your file and advise you of any further documents or information required to finalise and submit your application.</p>

    <!-- SECTION 4 -->
    <div class="section">Please provide me below mentioned list of documents (scan copy) as soon as practical:</div>
    <ol>
        <li>Your valid Pakistani Passport</li>
        <li>Your Biometric Residence Card</li>
        <li>Your Pre-Settled Status outcome letter</li>
        <li>Your wife's valid EEA Passport</li>
        <li>Your wife's Settled Status outcome letter</li>
        <li>Your Marriage Certificate</li>
        <li>Your Proof of addresses for five years</li>
    </ol>

    <!-- SECTION 5 -->
    <div class="section">Care and conduct</div>
    <p>One of the main purposes of this letter is to explain how we operate:</p>
    <p>My name is <strong>{{ $adviserName ?? 'Mohamad Salim Kureshi' }}</strong>, level 1 Adviser regulated and approved by the Immigration Advice Authority (IAA), responsible for the conduct of your case. I can be contacted on <strong>{{ $contactPhone ?? '07777328028' }}</strong> / <strong>{{ $contactEmail ?? 'qureshisalim@yahoo.com' }}</strong> / WhatsApp. Whenever possible, I shall also be available to advise and assist you. I will keep you informed of the progress of your case and any developments as and when they arise. If you need to see me, you should telephone first for an appointment as otherwise you may not be seen.</p>

    <p>I am also required to tell you that the Immigration Advice Authority (IAA) may examine your file. I shall carry out most of the work on your case. We shall, of course, take great care with any original documents such as passports, which you may give to us for sending to the immigration authorities. These will be returned to you immediately once they are returned to us.</p>

    <p>If you are on a low income or receiving welfare benefits, you may qualify for Legal Help under the Community Legal Services Scheme to assist you with your case. Please note that my organisation does not represent clients free under that scheme. If therefore you would prefer to be represented by the Community Legal Services adviser, please let me know immediately and I shall tell you where you can get possible alternative representation.</p>

    <p>At UK Immigration Law, we try to provide the best possible service to our clients and, in order to do this, we need to know from you if you feel dissatisfied. Should you have any occasion to feel unhappy about our service, please let me know straightaway and I will discuss this with you and ensure you receive a response within 7 working days.</p>

    <!-- SECTION 6 -->
    <div class="section">Complaints procedure</div>
    <p>If at any stage you have any concerns regarding the conduct of your case, please raise them with me, preferably in writing. Please let me know if you would like full details of our complaint's procedure. If we are unable to resolve matters to your satisfaction or you wish to pursue your complaint through other channels, you are entitled to contact the Immigration Advice Authority (IAA) at any time. Their address is:</p>

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
    <p>As previously discussed, a fixed fee of <strong>£{{ $fee ?? 250 }}</strong> for you and there will be no extra cost to represent you in relation to your immigration matter. This fee includes your initial consultation. The fee also includes making representations to the Home Office, informing you of any developments as and when they arise and submission of all necessary documentation. There will not be VAT charged. I am glad to inform you that there is no Home Office fees for these applications and so you don't need to pay Home Office fees or any disbursement.</p>

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
        Date: {{ $signatureDate ?? '29th October 2025' }}
    </p>

</div><!-- /container -->

</body>
</html>