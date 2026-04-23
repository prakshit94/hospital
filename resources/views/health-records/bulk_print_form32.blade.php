<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Form 32</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        th {
            font-weight: bold;
            text-align: center;
            background-color: #f2f2f2;
        }

        .no-border {
            border: none;
        }

        .text-center {
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }

        .info-table td {
            border: none;
            padding: 2px 5px;
        }

        .label {
            font-weight: bold;
        }

        .main-table thead th {
            font-size: 8pt;
        }

        .main-table tbody td {
            font-size: 8.5pt;
        }

        .vision-table {
            margin: 5px 0;
        }

        .vision-table th, .vision-table td {
            padding: 2px;
            font-size: 8pt;
            text-align: center;
        }

        .footer-section {
            margin-top: 40px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 45%;
            text-align: center;
            margin-top: 30px;
            font-weight: bold;
            white-space: nowrap;
        }

        .note {
            font-size: 8pt;
            margin-top: 10px;
        }

        .w-full { width: 100%; }

        .page-break { page-break-after: always; }

        @media print {
            @page {
                size: A4 landscape;
                margin: 6mm;
            }

            table, tr, td, th {
                page-break-inside: avoid !important;
            }

            .main-table,
            .footer-section,
            .note {
                page-break-inside: avoid;
            }

            th, td {
                padding: 3px !important;
            }

            .header {
                margin-bottom: 8px;
            }

            .footer-section {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    @foreach($records as $record)
        @php $num = 1; @endphp
        <div class="header">
            <h1>Form No. 32</h1>
            <p>(Prescribed under Rule 68-T and 102)</p>
            <h2>HEALTH REGISTER</h2>
        </div>

        <table class="info-table">
        <tr>
            <td>
                <span class="label">{{ $num++ }}. Serial Number:</span>
                {{ $record->employee_id }}
            </td>
            <td>
                <span class="label">{{ $num++ }}. Name:</span>
                {{ $record->full_name }}
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">{{ $num++ }}. Sex:</span>
                {{ $record->gender ?? '-' }}
            </td>
            <td>
                <span class="label">{{ $num++ }}. Date Of Birth:</span>
                {{ $record->dob ? $record->dob->format('d-m-Y') : 'NA' }}
                @if($record->dob)
                    (Age: {{ (int) $record->dob->diffInYears(now()) }} yrs)
                @endif
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">{{ $num++ }}. Company:</span>
                {{ $record->company_name }}
            </td>
            <td>
                <span class="label">{{ $num++ }}. Address:</span>
                {{ $record->address ?? '-' }}
            </td>
        </tr>
        </table>

        <table class="main-table">
            <thead>
                <tr>
                    <th rowspan="2">Dept /<br>Works</th>
                    <th rowspan="2">Hazardous<br>Process</th>
                    <th rowspan="2">Dangerous<br>Operation</th>
                    <th rowspan="2">Job<br>Nature</th>
                    <th rowspan="2">Raw Materials /<br>By-products</th>
                    <th rowspan="2">Date of<br>Posting</th>
                    <th rowspan="2">Date of<br>Leave /<br>Transfer</th>
                    <th rowspan="2">Reasons<br>for<br>Discharge</th>
                    <th colspan="4">Medical Examination &amp; Results</th>
                    <th colspan="4">If Declared Unfit</th>
                    <th rowspan="2">Signature &amp; Date<br>Med. Officer /<br>Certifying Surgeon</th>
                </tr>
                <tr>
                    <th>Exam<br>Date</th>
                    <th>Signs &amp; Symptoms Observed</th>
                    <th>Nature of Tests &amp; Results</th>
                    <th>Fit /<br>Unfit</th>
                    <th>Period<br>Temp.<br>Withdrawal</th>
                    <th>Reasons<br>Withdrawal</th>
                    <th>Date<br>Declared<br>Unfit</th>
                    <th>Date<br>Fitness<br>Cert.</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $examDate    = $record->examination_date ? $record->examination_date->format('d-m-Y') : 'NA';
                    $joiningDate = $record->joining_date     ? $record->joining_date->format('d-m-Y')     : 'NA';
                    $status      = strtoupper($record->health_status ?? '');
                @endphp
                <tr>
                    <td>{{ $record->department ?? $record->designation ?? '-' }}</td>
                    <td>{{ $record->hazardous_process ?? '-' }}</td>
                    <td>{{ $record->dangerous_operation ?? '-' }}</td>
                    <td>{{ $record->designation ?? '-' }}</td>
                    <td>{{ $record->materials_exposed ?? '-' }}</td>
                    <td class="text-center">{{ $joiningDate }}</td>
                    <td class="text-center">—</td>
                    <td class="text-center">—</td>
                    <td class="text-center">{{ $examDate }}</td>

                    <td>
                        <div>
                            <span class="label">Complaint:</span> {{ $record->present_complain ?? '-' }}
                        </div>

                        <div style="margin-top: 5px; border-top: 0.5px solid #000; padding-top: 5px;">
                            <div><span class="label">Height:</span> {{ $record->height ?? '-' }} cm</div>
                            <div><span class="label">Weight:</span> {{ $record->weight ?? '-' }} kg</div>
                            <div><span class="label">Chest (Before/After):</span> {{ $record->chest_before ?? '-' }} / {{ $record->chest_after ?? '-' }}</div>
                            <div><span class="label">Temperature:</span> {{ $record->temperature ?? '-' }}°F</div>
                            <div><span class="label">Pulse:</span> {{ $record->heart_rate ?? '-' }} bpm</div>
                            <div><span class="label">Blood Pressure:</span> {{ isset($record->bp_systolic) ? $record->bp_systolic.'/'.$record->bp_diastolic : '-' }}</div>
                        </div>

                        <table class="vision-table">
                            <tr><th>Vision</th><th>Right</th><th>Left</th></tr>
                            <tr><td>Specs</td><td>{{ $record->right_eye_specs ?? '-' }}</td><td>{{ $record->left_eye_specs ?? '-' }}</td></tr>
                            <tr><td>Near</td><td>{{ $record->near_vision_right ?? '-' }}</td><td>{{ $record->near_vision_left ?? '-' }}</td></tr>
                            <tr><td>Distant</td><td>{{ $record->distant_vision_right ?? '-' }}</td><td>{{ $record->distant_vision_left ?? '-' }}</td></tr>
                            <tr><td>Colour</td><td colspan="2">{{ $record->colour_vision ?? '-' }}</td></tr>
                        </table>

                        <div style="margin-top: 3px; border-top: 0.5px solid #000; padding-top: 3px; font-size: 7pt; line-height: 1.1;">
                            <table style="width: 100%; border: none; border-collapse: collapse;">
                                <tr>
                                    <td style="border: none; padding: 0; width: 33%; vertical-align: top;">
                                        <b>Ear:</b> {{ $record->ear ?? '-' }}<br>
                                        <b>Throat:</b> {{ $record->throat ?? '-' }}<br>
                                        <b>Nose:</b> {{ $record->nose ?? '-' }}<br>
                                        <b>Eye:</b> {{ $record->eye ?? '-' }}<br>
                                        <b>Conjunctiva:</b> {{ $record->conjunctiva ?? '-' }}
                                    </td>
                                    <td style="border: none; padding: 0; width: 33%; vertical-align: top;">
                                        <b>Skin:</b> {{ $record->skin ?? '-' }}<br>
                                        <b>Tongue:</b> {{ $record->tongue ?? '-' }}<br>
                                        <b>Nails:</b> {{ $record->nails ?? '-' }}<br>
                                        <b>Teeth:</b> {{ $record->teeth ?? '-' }}<br>
                                        <b>Lymph:</b> {{ $record->lymphnode ?? '-' }}
                                    </td>
                                    <td style="border: none; padding: 0; width: 33%; vertical-align: top;">
                                        <b>Cyanosis:</b> {{ $record->cyanosis ?? '-' }}<br>
                                        <b>Jaundice:</b> {{ $record->jaundice ?? '-' }}<br>
                                        <b>Anaemia:</b> {{ $record->anaemia ?? '-' }}<br>
                                        <b>Oedema:</b> {{ $record->oedema ?? '-' }}<br>
                                        <b>Clubbing:</b> {{ $record->clubbing ?? '-' }}
                                    </td>
                                </tr>
                            </table>

                            <div style="margin-top: 2px;">
                                <b>Allergy:</b> {{ $record->allergy_status ?? '-' }} |
                                <b>PEFR:</b> {{ $record->pefr ?? '-' }} |
                                <b>Eczema:</b> {{ $record->eczema ?? '-' }}
                            </div>

                            <div style="margin-top: 2px;">
                                <b>CVS:</b> {{ $record->cvs ?? '-' }} |
                                <b>CNS:</b> {{ $record->cns ?? '-' }} |
                                <b>Abdomen:</b> {{ $record->per_abdomen ?? '-' }} |
                                <b>Resp:</b> {{ $record->resp_system ?? '-' }} |
                                <b>GU:</b> {{ $record->genito_urinary ?? '-' }}
                            </div>
                        </div>
                    </td>

                    <td>
                        <span class="label">HB:</span> {{ $record->hb ?? '-' }}<br>
                        <span class="label">FBS:</span> {{ $record->fbs ?? '-' }}<br>
                        <span class="label">U. Albumin:</span> {{ $record->urine_albumin ?? '-' }}<br>
                        <span class="label">U. Sugar:</span> {{ $record->urine_sugar ?? '-' }}<br>
                        <span class="label">X-Ray:</span> {{ $record->xray_chest ?? '-' }}
                    </td>

                    <td class="text-center">
                        <span class="label">{{ $status ?: '-' }}</span>
                    </td>

                    <td class="text-center">NA</td>
                    <td class="text-center">NA</td>
                    <td class="text-center">NA</td>
                    <td class="text-center">NA</td>

                    <td class="text-center">
                        <div style="margin-top: 20px; font-weight: bold;">{{ $examDate }}</div>
                        <div style="font-size: 8pt;">{{ $record->doctor_name ?? '-' }}</div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="note">
            Note: 1. A separate page must be maintained for each worker.  2. A fresh entry must be made for each examination.
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
