<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Initial Instructions and Advice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            background-color: #fff;
        }
        .header-table {
            font-size: 13px;
        }
        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 30px;
        }
        .content-list {
            padding-left: 20px;
        }
    </style>
</head>
<body>

<div class="container my-5">

    <!-- Header Table -->
    <h4 class="text-center mb-4 text-uppercase">Client Initial Instructions and Advice</h4>

    <table class="table table-bordered header-table">
        <tr>
            <td><strong>Applicants Forename:</strong></td>
            <td>{{ $client->first_name ?? 'Ali' }}</td>
            <td><strong>Date:</strong></td>
            <td>{{ $date ?? date('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Applicants Surname:</strong></td>
            <td>{{ $client->sir_name ?? '' }}</td>
            <td><strong>Phone:</strong></td>
            <td>{{ $client->phone ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>D.O.B:</strong></td>
            <td>{{ $client->dob ?? '' }}</td>
            <td><strong>Email:</strong></td>
            <td>{{ $client->email ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Gender:</strong></td>
            <td>{{ $client->gender ?? 'Female' }}</td>
            <td><strong>English Speaking:</strong></td>
            <td>{{ $englishSpeaking ?? 'Yes' }}</td>
        </tr>
        <tr>
            <td><strong>Marital Status:</strong></td>
            <td>{{ $maritalStatus ?? 'Married' }}</td>
            <td><strong>First Language:</strong></td>
            <td>{{ $firstLanguage ?? 'Urdu' }}</td>
        </tr>
        <tr>
            <td><strong>Applicant client->country:</strong></td>
            <td>{{ $client->country ?? 'Pakistan' }}</td>
            <td><strong>NI No:</strong></td>
            <td>{{ $niNo ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Date of UK entry:</strong></td>
            <td>{{ $ukEntryDate ?? '' }}</td>
            <td><strong>Disability:</strong></td>
            <td>{{ $disability ?? 'None' }}</td>
        </tr>
        <tr>
            <td><strong>Address:</strong></td>
            <td colspan="3">{{ $client->address ?? 'D/T. Mandi Bahauddin, Pakistan' }}</td>
        </tr>
        <tr>
            <td><strong>Matter Description:</strong></td>
            <td colspan="3">{{ $matterDescription ?? 'Apply to enter the UK as a partner (Entry Clearance)' }}</td>
        </tr>
        <tr>
            <td><strong>Key Contact:</strong></td>
            <td colspan="3">{{ $keyContact ?? 'Mr. Hussain' }}</td>
        </tr>
    </table>

    <!-- Immigration History -->
    <div class="section-title">Immigration History:</div>
    <p>You have informed me that you are a <strong>{{ $client->country ?? 'Pakistani' }}</strong> national and you want to apply for an Entry Clearance visa for yourself to join your husband in the UK and your husband completely supports this application.</p>
    <ul class="content-list">
        <li>You never travelled to the UK before.</li>
        <li>No previous UK visa applications were granted.</li>
        <li>You have no history of overstaying, illegal entry, or breaches of UK immigration laws.</li>
        <li>You never travelled to any other countries in the past 10 years.</li>
        <li>You have no records of visa applications, refusals, or immigration issues outside the UK.</li>
        <li>You have passed your IELTS Life Skills English test level A1 Speaking and Listening.</li>
    </ul>

    <!-- Work and Family Information -->
    <div class="section-title">Work and Family Information:</div>
    <ul class="content-list">
        <li>You are not working in {{ $client->country == 'Pakistan' ? 'Pakistan' : 'your home country' }}.</li>
        <li>You are living with your parents in {{ $client->country == 'Pakistan' ? 'Pakistan' : 'your home country' }} and the house is owned by your parents.</li>
        <li>Your husband is <strong>{{ $husbandclient->country ?? 'Spanish' }}</strong> national and holding EU Settled Status in the UK.</li>
        <li>Your husband is living with his parents and other siblings in the UK and the house is rented by your parents in law.</li>
        <li>You met your husband on <strong>{{ $meetingDate ?? '15 December 2022' }}</strong> when he came to {{ $client->country == 'Pakistan' ? 'Pakistan' : 'your country' }} to marry you.</li>
        <li>You legally married him on <strong>{{ $marriageDate ?? '31 December 2022' }}</strong> in <strong>{{ $marriageLocation ?? 'Mandi Bahauddin, Pakistan' }}</strong> and you have no children.</li>
        <li>Your husband is working in the UK permanently as <strong>{{ $husbandJob ?? 'Sterile Service Technician 1' }}</strong> in a company and earning above £29,000 per annum.</li>
    </ul>

    <!-- Initial Instruction -->
    <div class="section-title">Initial Instruction:</div>
    <p><strong>You have instructed me to apply Entry Clearance visa for you to enter the UK as a partner.</strong></p>

    <!-- Advice -->
    <div class="section-title">Advice:</div>
    <p>I confirmed that, based on the information you provided, I would be happy to act on your proposed application for the UK Entry Clearance visa (to enter the UK as a partner). I advised you that based on what you told me, under <strong>Appendix FM</strong>, you (applying as a partner) are eligible to apply for Entry Clearance visa to enter the UK. I will submit the online application to the Home Office and appointment will be booked at any nearest location to provide fingerprints in due course.</p>

</div>

</body>
</html>