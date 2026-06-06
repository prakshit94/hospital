<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Form No. 32</title>
      <style>
         /* ===== BASE STYLES (Screen) ===== */
         * { box-sizing: border-box; }

         body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 10px;
         }
         table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
         }
         th, td {
            border: 1px solid #000;
            padding: 3px;
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
            margin-bottom: 8px;
         }
         .header h1 {
            margin: 0;
            font-size: 12pt;
            text-transform: uppercase;
         }
         .header h2 {
            margin: 1px 0 0;
            font-size: 10pt;
         }
         .header p {
            margin: 1px 0;
            font-size: 8.5pt;
         }
         .info-table {
            width: 100%;
            table-layout: fixed;
         }
         .info-table td {
            border: none;
            padding: 1px 5px;
            font-size: 8pt;
            width: 50%;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            vertical-align: top;
         }
         .label { font-weight: bold; }

         .main-table thead th { font-size: 7pt; }
         .main-table tbody td  { font-size: 7.5pt; }

         .vision-table { margin: 3px 0; }
         .vision-table th, .vision-table td {
            padding: 1px;
            font-size: 6.5pt;
            text-align: center;
         }
         .note {
            font-size: 7pt;
            margin-top: 5px;
         }

         /* ===== PRINT STYLES ===== */
         @media print {
            /*
             * Strategy: set @page margin to 0, then use a #print-wrapper div
             * with explicit mm margins and CSS transform:scale() to shrink
             * all content to fit precisely within one A4 landscape page.
             * This is more reliable than body zoom because:
             *   - zoom is not standard CSS (fails in Firefox)
             *   - transform:scale on a wrapper gives predictable pixel-perfect results
             */
            @page {
               size: A4 landscape;
               margin: 0;
            }

            html, body {
               margin: 0;
               padding: 0;
               width: 297mm;
               height: 210mm;
               overflow: hidden;
               -webkit-print-color-adjust: exact;
               print-color-adjust: exact;
            }

            /*
             * #print-wrapper:
             *   - natural width = 277mm (297 - 10 - 10 margin)
             *   - transform: scale(0.76) shrinks it so the content height
             *     also fits within 210mm
             *   - transform-origin: top left keeps it aligned to the margin point
             */
            #print-wrapper {
               width: 277mm;
               margin: 10mm;
               transform-origin: top left;
               transform: scale(0.76);
            }

            /* Tables: remove bottom margin in print */
            table { margin-bottom: 2px !important; }

            /* Header */
            .header { margin-bottom: 2px !important; }
            .header h1 { font-size: 9pt !important; margin: 0 !important; }
            .header h2 { font-size: 8pt !important; margin: 0 !important; }
            .header p  { font-size: 6pt !important; margin: 0 !important; }

            /* Info table */
            .info-table { margin-bottom: 2px !important; width: 100% !important; table-layout: fixed !important; }
            .info-table td { padding: 0 3px !important; font-size: 6pt !important; width: 50% !important; word-wrap: break-word !important; word-break: break-word !important; white-space: normal !important; vertical-align: top !important; }

            /* ── MAIN TABLE ── */
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
               font-size: 5pt !important;
               background-color: #f2f2f2 !important;
               text-align: center !important;
               line-height: 1.0 !important;
            }

            /* ── VISION TABLE (nested) ── */
            .vision-table {
               margin: 0 !important;
               width: 100% !important;
               border-collapse: collapse !important;
            }
            .vision-table th, .vision-table td {
               font-size: 4.5pt !important;
               padding: 0 !important;
               border: 1px solid #000 !important;
               line-height: 1.0 !important;
            }

            /* ── NESTED DETAIL TABLE (inside symptoms cell) ── */
            .main-table td table {
               border-collapse: collapse !important;
               margin: 0 !important;
            }
            .main-table td table td {
               border: none !important;
               padding: 0 !important;
               font-size: 4.5pt !important;
               line-height: 1.0 !important;
               word-break: break-all !important;
            }

            /* Divs inside cells — override all inline margins */
            .main-table td div {
               margin: 0 !important;
               padding-top: 0 !important;
               padding-bottom: 0 !important;
               font-size: 5pt !important;
               line-height: 1.0 !important;
            }

            /* Label */
            .label { font-size: 5pt !important; }

            /* Note */
            .note {
               margin-top: 2px !important;
               font-size: 5pt !important;
               page-break-before: avoid !important;
            }

            /* No page breaks inside any element */
            * { page-break-inside: avoid !important; }
         }
      </style>
   </head>
   <body>
      <div id="print-wrapper">
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
                  @if(optional($record->employee->company)->address)
                      <br>{{ $record->employee->company->address }}
                  @endif
               </td>
               <td>
                  <span class="label">{{ $num++ }}. Address:</span>
                  {{ $record->address ?? '-' }}
               </td>
            </tr>
         </table>

         {{-- ===== MAIN FORM TABLE ===== --}}
         {{--
            17 leaf columns — widths in colgroup sum to 100%
            1  Department           4%
            2  Hazardous Process     5%
            3  Dangerous Operation   4%
            4  Job / Occupation      4%
            5  Raw Materials         5%
            6  Date Posting          4%
            7  Date Leaving          4%
            8  Reasons Discharge     4%
            9  Exam Date             4%
            10 Signs & Symptoms     24%  ← widest (holds vitals, vision, exam)
            11 Tests & Results       8%
            12 Fit / Unfit           4%
            13 Period Withdrawal     5%
            14 Reasons Withdrawal    5%
            15 Date Declared Unfit   5%
            16 Date Fitness Cert     5%
            17 Signature             6%
                              Total 100%
         --}}
         <table class="main-table">
            <colgroup>
               <col style="width:4%">
               <col style="width:5%">
               <col style="width:4%">
               <col style="width:4%">
               <col style="width:5%">
               <col style="width:4%">
               <col style="width:4%">
               <col style="width:4%">
               <col style="width:4%">
               <col style="width:24%">
               <col style="width:8%">
               <col style="width:4%">
               <col style="width:5%">
               <col style="width:5%">
               <col style="width:5%">
               <col style="width:5%">
               <col style="width:6%">
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

                  {{-- ── Symptoms / Vitals / Examination (col 10 — widest) ── --}}
                  <td>
                     <div>• <span class="label">PresentComplaint:</span> {{ $record->present_complain ?? '-' }}</div>
                     <div>• <span class="label">Height:</span> {{ $record->height ?? '-' }} cm</div>
                     <div>• <span class="label">Weight:</span> {{ $record->weight ?? '-' }} kg</div>
                     <div>• <span class="label">Chest (Before/After):</span> {{ $record->chest_before ?? '-' }} / {{ $record->chest_after ?? '-' }} cm</div>
                     <div>• <span class="label">Temperature:</span> {{ $record->temperature ?? '-' }} °F</div>
                     <div>• <span class="label">Pulse:</span> {{ $record->heart_rate ?? '-' }} bpm</div>
                     <div>• <span class="label">BP:</span> {{ isset($record->bp_systolic) ? $record->bp_systolic.'/'.$record->bp_diastolic : '-' }} mmHg</div>
                     
                     <table class="vision-table">
                        <tr><th rowspan="2">Vision</th><th colspan="2">With Specs</th><th colspan="2">Without Specs</th></tr>
                        <tr><th>R</th><th>L</th><th>R</th><th>L</th></tr>
                        <tr><td>Near</td><td>{{ $record->near_vision_right ?? '-' }}</td><td>{{ $record->near_vision_left ?? '-' }}</td><td>{{ $record->near_vision_right_without ?? '-' }}</td><td>{{ $record->near_vision_left_without ?? '-' }}</td></tr>
                        <tr><td>Dist</td><td>{{ $record->distant_vision_right ?? '-' }}</td><td>{{ $record->distant_vision_left ?? '-' }}</td><td>{{ $record->distant_vision_right_without ?? '-' }}</td><td>{{ $record->distant_vision_left_without ?? '-' }}</td></tr>
                        <tr><td>Colour</td><td colspan="4">{{ $record->colour_vision ?? '-' }}</td></tr>
                     </table>
                     
                     <div>• <span class="label">Ear:</span> {{ $record->ear ?? '-' }}</div>
                     <div>• <span class="label">Throat:</span> {{ $record->throat ?? '-' }}</div>
                     <div>• <span class="label">Nose:</span> {{ $record->nose ?? '-' }}</div>
                     <div>• <span class="label">Eye:</span> {{ $record->eye ?? '-' }}</div>
                     <div>• <span class="label">Conjunctiva:</span> {{ $record->conjunctiva ?? '-' }}</div>
                     <div>• <span class="label">Skin:</span> {{ $record->skin ?? '-' }}</div>
                     <div>• <span class="label">Tongue:</span> {{ $record->tongue ?? '-' }}</div>
                     <div>• <span class="label">Nails:</span> {{ $record->nails ?? '-' }}</div>
                     <div>• <span class="label">CVS:</span> {{ $record->cvs ?? '-' }}</div>
                     <div>• <span class="label">Abdomen:</span> {{ $record->per_abdomen ?? '-' }}</div>
                     <div>• <span class="label">CNS:</span> {{ $record->cns ?? '-' }}</div>
                     <div>• <span class="label">Urine Albumin:</span> {{ $record->urine_albumin ?? '-' }}</div>
                     <div>• <span class="label">Urine Sugar:</span> {{ $record->urine_sugar ?? '-' }}</div>
                  </td>

                  {{-- ── Tests & Results (col 11) ── --}}
                  <td class="text-center">
                     <span class="label">{{ $status ?: '-' }}</span>
                  </td>

                  {{-- ── Fit / Unfit (col 12) ── --}}
                  <td class="text-center">
                     <span class="label">{{ $status ?: '-' }}</span>
                  </td>

                  {{-- ── If declared unfit (cols 13–16) ── --}}
                  <td class="text-center">NA</td>
                  <td class="text-center">NA</td>
                  <td class="text-center">NA</td>
                  <td class="text-center">NA</td>

                  {{-- ── Signature (col 17) ── --}}
                  <td class="text-center">
                     <div style="font-weight:bold;">{{ $examDate }}</div>
                     <div>{{ $record->doctor_name ?? '-' }}</div>
                     <div>{{ $record->doctor_qualification ?? '' }}</div>
                  </td>
               </tr>
            </tbody>
         </table>

         <div class="note">
            Note: 1. A separate page must be maintained for each worker. &nbsp; 2. A fresh entry must be made for each examination.
         </div>
      </div>{{-- end #print-wrapper --}}
   </body>
</html>