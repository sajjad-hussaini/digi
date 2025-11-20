<h2>Authority Letter</h2>

<p>To Whom It May Concern,</p>

<p>
I, <strong>{{ $data->client->name }}</strong>, authorize 
<strong>{{ $data->solicitor_name }}</strong> of 
<strong>{{ $data->firm_name }}</strong> 
to act on my behalf in relation to:
<strong>{{ $data->purpose }}</strong>.
</p>

<p><strong>Client Details:</strong></p>
<p>Name: {{ $data->client->name }}<br>
Address: {{ $data->client_address }}<br>
Passport No: {{ $data->passport_no }}<br>
Date: {{ $data->date }}</p>

<p>Signed,<br>
{{ $data->client->name }}</p>
