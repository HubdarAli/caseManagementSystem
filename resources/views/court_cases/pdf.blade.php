<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Times New Roman; font-size: 12px; font-weight: 200; margin: 0px; padding-left: 20px; padding-right: 20px; }
        h2 {
            border: 1px ridge rgb(0, 0, 0);
            margin: 0px;
            padding: 0px;
            text-align: center;
            background-color: #eaeaea;
            font-size: 16px;
        }

        h3 {
            text-decoration: underline;
            margin: 0px;
            margin-bottom: 1px;
            /* padding: 5px; */
            text-align: center;
            font-size: 14px;
        }
        
        h4 {
            /* text-decoration: underline; */
            margin: 0px;
            /* padding: 5px; */
            text-align: center;
        }

        p {
            margin: 0px 10px;
        }

        img {
            margin: 0px;
            max-width: 100%;
            height: auto;
        }

        ol {
            margin: 0;
        }

        ol li {
            margin-left: 35px;
        }
        
    </style>
</head>
<body>
    <div style="text-align:center;margin:0px"><img src="{{ public_path('assets/media/photos/logo-new.jpg') }}"  width="300" alt="" /></div>
    {{-- <h1 style="text-align:center;">Kayani & Masood<br>Advocates | Barristers | Legal Consultants</h1> --}}
    <h4> Daily Cause List, 
        {{ \Carbon\Carbon::parse($from)->format('l, d F Y') }}
        {{-- -
        {{ \Carbon\Carbon::parse($to)->format('l, d F Y') }} --}}
     </h4>
    <h3> District Courts </h3>
    <ol>
        @foreach($groupedCases as $region => $cases)
            <h2>{{ strtoupper($region) }}</h2>

            @foreach($cases as $index => $case)
                <h3> {{ $case->court?->name }} (File# {{ $case->file_no }}) </h3>
                <li>
                    <p style="text-decoration: underline;margin-bottom: 2px;">
                        ({{ $case->counsel }})
                    </p>

                    <p>
                        {{ $case->case_number }} {{ $case->applicant }} vs {{ $case->respondent }} <br>
                    </p>
                    <p style="text-decoration: underline;margin-bottom: 2px;">
                        {{-- {{ \Carbon\Carbon::parse($case->hearing_date)->format('d F Y') }} - {{ $case->case_type }} --}}
                        ({{ $case->notes }})
                    </p>
                </li>
            @endforeach
        @endforeach
    </ol>
</body>
</html>
