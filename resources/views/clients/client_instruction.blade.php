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
            margin-top: 10px;
        }
        .content-list {
            padding-left: 20px;
        }
        .logo-top-right {
            position: absolute;
            top: 15px;
            right: 20px;
            width: 70px;
            height: auto;
            z-index: 10;
        }
    </style>
</head>
<body>

<div class="container my-5">
<!-- Logo Top Right Corner -->
  <img src="{{ public_path('images/logo_imigration_law.png') }}" alt="Company Logo" class="logo-top-right">
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
        {{ $request->immigration_history ?? 'You have not previously applied for a visa to the UK. You have no refusals or deportations from the UK or any other country.' }}

    <!-- Work and Family Information -->
    <div class="section-title">Work and Family Information:</div>
        {{ $request->work_family_info ?? 'You are currently employed as a Software Engineer at ABC Tech in Pakistan. You are married to Jane Doe, who is a British citizen. You have two children, aged 5 and 3.' }}

    <!-- Initial Instruction -->
    <div class="section-title">Initial Instruction:</div>
        {!! $request->initial_instruction ?? 'I have instructed you to apply for an Entry Clearance visa to enter the UK as a partner of a British citizen. You have provided all necessary documents and information required for the application.' !!}
    <!-- Advice -->
    <div class="section-title">Advice:</div>
    <p>I confirmed that, based on the information you provided, I would be happy to act on your proposed application for the UK Entry Clearance visa (to enter the UK as a partner). I advised you that based on what you told me, under <strong>Appendix FM</strong>, you (applying as a partner) are eligible to apply for Entry Clearance visa to enter the UK. I will submit the online application to the Home Office and appointment will be booked at any nearest location to provide fingerprints in due course.</p>

</div>

</body>
</html>