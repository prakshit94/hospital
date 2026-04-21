<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form No. 33 — Certificate of Fitness</title>
    <style>
        /* 1. FIXING MARGINS: We define the page size and margins. 
           Most browsers add their own margins, so we use 'auto' or '0' in @page 
           and handle spacing inside the .page container.
        */
        @page {
            size: A4 portrait;
            margin: 5mm; /* Minimal physical margin */
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #cccccc;
            font-family: 'Times New Roman', Times, serif;
            color: #000000;
            line-height: 1.2;
            -webkit-print-color-adjust: exact;
        }

        /* Screen Preview Styling */
        @media screen {
            body {
                display: flex;
                justify-content: center;
                padding: 20px;
            }
            .page {
                width: 210mm;
                min-height: 297mm;
                background: #ffffff;
                padding: 10mm 15mm;
                box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            }
        }

        /* Print Styling - FIXES CROPPING AND PAGE COUNT */
        @media print {
            body {
                background: none;
            }
            .page {
                width: 100% !important; /* Fits to the printer's width */
                height: auto;
                margin: 0;
                padding: 5mm 10mm; /* Internal spacing for content safety */
                box-shadow: none;
                overflow: hidden; /* Prevents 2nd page spillover */
            }
            .no-print { display: none; }
        }

        /* HEADER SECTION */
        .header {
            text-align: center;
            border: 1.5px solid #000;
            padding: 4px;
            margin-bottom: 8px;
        }
        .header h1 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header .subtitle {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }
        .header .rule-ref, .header .issued-by {
            font-size: 8pt;
        }

        /* TABLE LOGIC: FIXED layout prevents right-side expansion */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; 
            margin-bottom: 5px;
        }
        
        td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 9pt;
            overflow: hidden;
        }

        .lbl { width: 55%; font-weight: bold; }
        .val { 
            width: 45%; 
            border-bottom: 1px dotted #000; 
            padding-left: 5px; 
            word-wrap: break-word;
        }
        .sub-lbl { padding-left: 20px; font-weight: bold; font-size: 8.5pt; }

        .divider {
            border: none;
            border-top: 1px solid #000;
            margin: 8px 0;
        }

        /* CONTENT */
        .cert-text {
            font-size: 9pt;
            margin: 5px 0;
            text-align: justify;
        }
        .dline {
            display: inline-block;
            border-bottom: 1px dotted #000;
            padding: 0 4px;
        }

        .fit-status {
            font-weight: bold;
            text-decoration: underline;
        }

        /* SIGNATURE AREA */
        .sig-container {
            width: 100%;
            margin-top: 15px;
        }
        .sig-box {
            text-align: center;
            font-size: 8.5pt;
        }
        .sig-space { height: 35px; } /* Slightly shorter to save vertical space */
        .sig-line {
            border-top: 1px solid #000;
            display: inline-block;
            width: 80%;
            padding-top: 3px;
        }

        /* EXTENSION TABLE */
        .ext-table th {
            border: 1px solid #000;
            background: #f0f0f0 !important;
            font-size: 8pt;
            padding: 4px;
            text-align: center;
        }
        .ext-table td {
            border: 1px solid #000;
            padding: 4px;
            height: 40px; /* Consistent height to fit 1 page */
            font-size: 8.5pt;
        }

        .footer-note {
            font-size: 7.5pt;
            font-style: italic;
            text-align: center;
            margin-top: 10px;
            color: #333;
        }
    </style>
</head>
<body>

@php
    $dob = $record->dob ? $record->dob->format('d-m-Y') : 'N/A';
    $age = $record->dob ? $record->dob->diffInYears(now()) : 'N/A';
    $examDate = $record->examination_date ? $record->examination_date->format('d/m/Y') : 'N/A';
    $extensionDate = $record->examination_date ? $record->examination_date->copy()->addMonths(6)->format('d/m/Y') : 'N/A';
    $isFit = strtoupper($record->health_status ?? '') === 'FIT';
@endphp

<div class="page">
    <div class="header">
        <h1>Form No. 33</h1>
        <div class="rule-ref">(Prescribed under Rule 68-T and 102)</div>
        <div class="subtitle">Certificate of Fitness of Employment in Hazardous Process and Operations</div>
        <div class="issued-by">(TO BE ISSUED BY FACTORY MEDICAL OFFICER)</div>
    </div>

    <table>
        <tr>
            <td class="lbl">1. Serial number in the register of adult workers:</td>
            <td class="val">{{ $record->employee_id }}</td>
        </tr>
        <tr>
            <td class="lbl">2. Name of the person examined:</td>
            <td class="val"><strong>{{ strtoupper($record->full_name) }}</strong></td>
        </tr>
        <tr>
            <td class="lbl">3. Father's Name:</td>
            <td class="val">{{ $record->father_name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="lbl">4. Sex:</td>
            <td class="val">{{ ucfirst($record->sex ?? $record->gender ?? '-') }}</td>
        </tr>
        <tr>
            <td class="lbl">5. Date of Birth / Age:</td>
            <td class="val">{{ $dob }} / {{ $age }} Years</td>
        </tr>
        <tr>
            <td class="lbl">6. Residence:</td>
            <td class="val">{{ $record->address ?? '-' }}</td>
        </tr>
        <tr>
            <td class="lbl">7. Name & address of the factory:</td>
            <td class="val">{{ $record->company_name }}</td>
        </tr>
        <tr>
            <td class="lbl">8. The worker is employed / proposed to be employed in:</td>
            <td class="val">{{ $record->department ?? $record->designation ?? '-' }}</td>
        </tr>
        <tr>
            <td class="sub-lbl">(a) Hazardous process:</td>
            <td class="val">{{ $record->hazardous_process ?? '-' }}</td>
        </tr>
        <tr>
            <td class="sub-lbl">(b) Dangerous operation:</td>
            <td class="val">{{ $record->dangerous_operation ?? '-' }}</td>
        </tr>
    </table>

    <hr class="divider">

    <div class="cert-text">
        I certify that I have personally examined the above named person whose identification marks are
        <span class="dline" style="min-width:200px">{{ $record->identification_mark ?? '................................................' }}</span>
        and who is desirous of being employed in above mentioned process / operation and that his / her age, 
        as can be ascertained from my examination, is <span class="dline" style="min-width:40px">{{ $age }}</span> years.
    </div>

    <div class="cert-text">
        In my opinion he / she is <span class="fit-status">{{ $isFit ? 'FIT' : 'UNFIT' }}</span> for employment in the said manufacturing process / operation.
    </div>

    @if(!$isFit)
        <div class="cert-text">
            <strong>Reason for unfitness:</strong> <span class="dline" style="min-width:200px">{{ $record->doctor_remarks ?? '-' }}</span>
        </div>
    @else
        <div class="cert-text" style="font-size: 8pt; font-style: italic; color: #444;">
            (If unfit, the reasons and the period for which the worker is considered unfit shall be stated below.)
        </div>
    @endif

    <div class="cert-text">
        The serial number of previous certificate is <span class="dline" style="min-width:150px">...................................</span>
    </div>

    <table class="sig-container">
        <tr>
            <td class="sig-box">
                <div class="sig-space"></div>
                <div class="sig-line">Signature / Left Thumb Impression<br>of Person Examined</div>
            </td>
            <td class="sig-box">
                <div class="sig-space"></div>
                <div class="sig-line">
                    Signature of the Factory Medical Officer<br>
                    <strong>Dr. {{ $record->doctor_name ?? '-' }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    <table class="ext-table">
        <thead>
            <tr>
                <th style="width:20%">Examined on</th>
                <th style="width:20%">Extended until</th>
                <th style="width:40%">Signs and symptoms observed</th>
                <th style="width:20%">Medical Officer Sign</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">{{ $examDate }}</td>
                <td style="text-align: center;">{{ $extensionDate }}</td>
                <td>{{ $record->present_complain ?? 'No significant symptoms observed.' }}</td>
                <td></td>
            </tr>
            <tr>
                <td></td><td></td><td></td><td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer-note">
        Note: Age & Date of Joining is as declared by the person. This certificate cannot be produced as proof of age.
    </div>
</div>

</body>
</html>