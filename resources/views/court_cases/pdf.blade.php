<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 14px; }
        h2 {
            border: 1px solid #000;
            margin-top: 20px;
            padding: 10px;
            text-align: center;
        }

        h3 {
            text-decoration: underline;
            margin-top: 20px;
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
    <div style="text-align:center;margin-bottom:10px"><img src="{{ asset('assets/media/photos/logo-new.jpg') }}"  width="380" class="img-fluid" alt="" /></div>
    <h3> Daily Cause List, {{ 
        // add Day , Date with th Month Year
        \Carbon\Carbon::now()->format('l, d F Y')
    }} </h3>
    @foreach($groupedCases as $region => $cases)
        <h2>{{ strtoupper($region) }}</h2>

        @foreach($cases as $index => $case)
            <h3> {{ $case->court->name }} (File#{{ $case->id }}) </h3>
            <p>
                {{ $index + 1 }}. {{ $case->title }} ({{ $case->case_number }})<br>
            </p>
        @endforeach
    @endforeach
</body>
</html>
