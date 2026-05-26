<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Form 32</title>
    <style>
        /* ===== BASE STYLES (Screen) ===== */
        * { box-sizing: border-box; }

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
        .no-border { border: none; }
        .text-center { text-align: center; }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 13pt;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 2px 0 0;
            font-size: 11pt;
        }
        .header p {
            margin: 2px 0;
            font-size: 9pt;
        }
        .info-table td {
            border: none;
            padding: 2px 5px;
            font-size: 8.5pt;
        }
        .label { font-weight: bold; }

        .main-table thead th { font-size: 7.5pt; }
        .main-table tbody td { font-size: 8pt; }

        .vision-table { margin: 4px 0; }
        .vision-table th, .vision-table td {
            padding: 1px;
            font-size: 7pt;
            text-align: center;
        }
        .note {
            font-size: 7.5pt;
            margin-top: 8px;
        }

        .w-full { width: 100%; }
        .page-break { page-break-after: always; }

        /* ===== PRINT STYLES ===== */
        @media print {
            @page {
                size: A4 landscape;
                margin: 6mm;
            }

            html, body {
                margin: 0;
                padding: 0;
            }

            body {
                zoom: 0.72;
                font-size: 6.5pt;
                line-height: 1.1;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Header tightening */
            .header { margin-bottom: 3px; }
            .header h1 { font-size: 9pt; margin: 0; }
            .header h2 { font-size: 8pt; margin: 0; }
            .header p  { font-size: 6pt; margin: 0; }

            /* Info table */
            .info-table { margin-bottom: 3px; width: 100%; }
            .info-table td { padding: 0 4px !important; font-size: 6.5pt !important; }

            /* ── MAIN TABLE ─────────────────────────────────────────────── */
            .main-table {
                width: 100% !important;
                border-collapse: collapse !important;
                table-layout: fixed !important;
                margin: 0 !important;
            }

            /* All cells */
            .main-table th,
            .main-table td {
                padding: 1px !important;
                border: 1px solid #000 !important;
                word-break: break-all !important;
                overflow-wrap: anywhere !important;
                white-space: normal !important;
                overflow: hidden !important;
                vertical-align: top !important;
                font-size: 6pt !important;
                line-height: 1.0 !important;
            }

            .main-table thead th {
                font-size: 5.5pt !important;
                background-color: #f2f2f2 !important;
                text-align: center !important;
                line-height: 1.0 !important;
            }

            /* ── VISION TABLE (nested) ──────────────────────────────────── */
            .vision-table {
                margin: 0 !important;
                width: 100% !important;
                border-collapse: collapse !important;
            }
            .vision-table th, .vision-table td {
                font-size: 5pt !important;
                padding: 0px !important;
                border: 1px solid #000 !important;
                line-height: 1.0 !important;
            }

            /* ── NESTED DETAIL TABLE (inside symptoms cell) ─────────────── */
            .main-table td table {
                border-collapse: collapse !important;
                margin: 0 !important;
            }
            .main-table td table td {
                border: none !important;
                padding: 0 !important;
                font-size: 5pt !important;
                line-height: 1.0 !important;
            }

            /* Override inline styles on divs inside cells */
            .main-table td div {
                margin-top: 1px !important;
                padding-top: 0px !important;
                font-size: 5.5pt !important;
            }

            /* ── NOTE ───────────────────────────────────────────────────── */
            .note { margin-top: 2px; font-size: 5.5pt; page-break-before: avoid; }

            /* Prevent page breaks inside table rows */
            table, tr, td, th { page-break-inside: avoid !important; }

            /* Label font inside print */
            .label { font-size: 5.5pt !important; }

            /* Page break between records */
            .page-break { page-break-after: always; }
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
                {{ $record->employee->employee_id ?? 'N/A' }}
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
            <colgroup>
                <col style="width:4%">   {{-- 1  Department --}}
                <col style="width:5%">   {{-- 2  Hazardous Process --}}
                <col style="width:4%">   {{-- 3  Dangerous Operation --}}
                <col style="width:4%">   {{-- 4  Job / Occupation --}}
                <col style="width:5%">   {{-- 5  Raw Materials --}}
                <col style="width:4%">   {{-- 6  Date Posting --}}
                <col style="width:4%">   {{-- 7  Date Leaving --}}
                <col style="width:4%">   {{-- 8  Reasons Discharge --}}
                <col style="width:4%">   {{-- 9  Exam Date --}}
                <col style="width:24%">  {{-- 10 Signs & Symptoms (biggest) --}}
                <col style="width:8%">   {{-- 11 Tests & Results --}}
                <col style="width:4%">   {{-- 12 Fit / Unfit --}}
                <col style="width:5%">   {{-- 13 Period Withdrawal --}}
                <col style="width:5%">   {{-- 14 Reasons Withdrawal --}}
                <col style="width:5%">   {{-- 15 Date Declared Unfit --}}
                <col style="width:5%">   {{-- 16 Date Fitness Cert --}}
                <col style="width:6%">   {{-- 17 Signature --}}
            </colgroup>
            <thead>
                <tr>
                    <th rowspan="2">Department /
                        Works</th>
                    <th rowspan="2">Name Of<br>Hazardous Process</th>
                    <th rowspan="2">Dangerous<br>Process /<br>Operation</th>
                    <th rowspan="2">Nature of Job or<br>Occupation</th>
                    <th rowspan="2">Raw<br>Materials,<br>Products or<br>By-products<br>likely to be<br>exposed to</th>
                    <th rowspan="2">Date of Posting</th>
                    <th rowspan="2">Date of leaving<br>/ transfer to or<br>transfer</th>
                    <th rowspan="2">Reasons for<br>Discharge/<br>leaving or<br>transfer</th>
                    <th colspan="4">Medical examination Results therefore</th>
                    <th colspan="4">If declared unfit for work</th>
                    <th rowspan="2">Signature<br>with date<br>of the<br>factory<br>Medical<br>Officer /<br>the<br>Certifying<br>Surgeon</th>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Signs and<br>Symptoms<br>observed<br>during<br>Examination</th>
                    <th>Nature of<br>Tests &amp;<br>Results<br>thereof</th>
                    <th>Result Fit /<br>Unfit</th>
                    <th>Period of<br>Temporary<br>Withdrawal from<br>that work</th>
                    <th>Reasons for<br>such<br>withdrawal</th>
                    <th>Date of<br>Declaring<br>him Unfit<br>for that<br>work</th>
                    <th>Date of<br>issuing<br>Fitness<br>Certificate</th>
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
