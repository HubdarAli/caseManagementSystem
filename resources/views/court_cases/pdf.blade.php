<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 11px; }
        h2 {
            border: 1px solid #000;
            margin-top: 2px;
            padding: 10px;
            text-align: center;
        }

        h3 {
            text-decoration: underline;
            margin-top: 5px;
            padding: 10px;
            text-align: center;
        }

        p {
            margin: 5px 0;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div style="text-align:center;margin-bottom:10px"><img src="{{ public_path('assets/media/photos/logo-new.jpg') }}"  width="380" class="img-fluid" alt="" /></div>
    {{-- <h1 style="text-align:center;">Kayani & Masood<br>Advocates | Barristers | Legal Consultants</h1> --}}
    <h3> Daily Cause List, 
        {{ \Carbon\Carbon::parse($from)->format('l, d F Y') }}
        -
        {{ \Carbon\Carbon::parse($to)->format('l, d F Y') }}
     </h3>
    @foreach($groupedCases as $region => $cases)
        <h2>{{ strtoupper($region) }}</h2>

        @foreach($cases as $index => $case)
            <h3> {{ $case->court->name }} (File#{{ $case->id }}) </h3>
            <p>
                {{ $index + 1 }}. {{ $case->case_number }} {{ $case->applicant }} vs {{ $case->respondent }} <br>
            </p>
            <p style="text-decoration: underline;">
                ({{ $case->notes }})
            </p>
        @endforeach
    @endforeach
</body>
</html>
