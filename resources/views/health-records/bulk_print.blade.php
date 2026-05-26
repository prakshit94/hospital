<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Medical Reports</title>
    <style>
        @page { size: A4; margin: 8mm 10mm; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.25;
            color: #111;
            margin: 0;
            padding: 0;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .header h1 { margin: 0; font-size: 17px; text-transform: uppercase; color: #000; letter-spacing: 0.5px; }
        .header p  { margin: 1px 0; font-weight: bold; font-size: 8.5px; color: #333; }

        /* ── Report title ── */
        .report-title {
            text-align: center;
            background: #f1f3f5;
            padding: 4px;
            margin-bottom: 6px;
            border: 1px solid #ced4da;
            border-radius: 3px;
        }
        .report-title h2 { margin: 0; font-size: 11px; text-transform: uppercase; color: #111; font-weight: 800; }

        /* ── Section wrapper — no page-break-inside:avoid so content fills pages naturally ── */
        .section { margin-bottom: 6px; }

        .section-title {
            background: #e9ecef;
            padding: 2px 8px;
            font-weight: bold;
            font-size: 9px;
            border-left: 4px solid #111;
            margin-bottom: 3px;
            text-transform: uppercase;
            color: #000;
            letter-spacing: 0.2px;
            break-after: avoid;
            page-break-after: avoid;
        }

        /* ── Tables ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }
        table th, table td {
            border: 1px solid #adb5bd;
            padding: 2.5px 5px;
            text-align: left;
            vertical-align: top;
        }
        /* Default 2-pair layout: th=19% td=31% th=19% td=31% */
        table th {
            background-color: #f8f9fa;
            color: #343a40;
            font-weight: 700;
            font-size: 8.5px;
            width: 19%;
            white-space: nowrap;
        }
        table td {
            color: #212529;
            overflow-wrap: break-word;
            word-wrap: break-word;
            font-size: 9px;
        }

        /* 3-pair rows: th=14% td=19.33% × 3 ≈ 100% */
        table.cols-6 th { width: 14%; }
        table.cols-6 td { width: 19.33%; }

        /* prevent a row splitting across pages */
        tr { break-inside: avoid; page-break-inside: avoid; }

        /* ── Fit / Unfit badge ── */
        .fit-badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
        }
        .fit-badge-fit   { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .fit-badge-unfit { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Footer / signatures ── */
        .footer { margin-top: 12px; }
        .signature-table { width: 100%; border: none; margin-top: 22px; }
        .signature-table td { border: none; text-align: center; vertical-align: bottom; padding: 0; }
        .signature-line {
            border-top: 1.5px solid #000;
            width: 85%;
            margin: 0 auto;
            padding-top: 3px;
            font-weight: bold;
            font-size: 9px;
        }

        /* ── Watermark ── */
        .watermark {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 70px;
            color: rgba(0,0,0,0.02);
            z-index: -1;
            white-space: nowrap;
            pointer-events: none;
        }

        /* ── Each new record starts on a fresh page (except the very first) ── */
        .record-start {
            break-before: page;
            page-break-before: always;
        }

        /* ── Within each record: section 7 (Family History) starts page 2 ── */
        .page-two-start {
            break-before: page;
            page-break-before: always;
        }
    </style>
</head>
<body>
    @php
        $formatDate = function($date) {
            if (!$date) return 'N/A';
            if ($date instanceof \Carbon\Carbon) return $date->format('d/m/Y');
            try { return \Carbon\Carbon::parse($date)->format('d/m/Y'); }
            catch (\Exception $e) { return $date; }
        };
        $getAge = function($date) {
            if (!$date) return 'N/A';
            try { return (int) \Carbon\Carbon::parse($date)->age; }
            catch (\Exception $e) { return 'N/A'; }
        };
    @endphp

    @foreach($records as $record)
        @php
            $num        = 1;
            $sectionNum = 1;
        @endphp

        {{-- record-start forces a new page for every record except the first --}}
        <div class="{{ $loop->first ? '' : 'record-start' }}">

            <div class="watermark">MEDICAL CERTIFICATE</div>

            {{-- ══ HEADER ══ --}}
            <div class="header">
                <h1>{{ config('enterprise-ui.workspace_name', 'Divit Hospital') }}</h1>
                <p>Occupational Health &amp; Medical Services</p>
                <p style="font-size:8px;font-weight:normal;color:#555;">Reg No: 12345/OH/2024 | Contact: +91 98765 43210</p>
            </div>

            <div class="report-title">
                <h2>Periodic Medical Examination Report</h2>
            </div>

            {{-- ══════════════ PAGE 1 CONTENT ══════════════ --}}

            {{-- 1. Employee Information --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Employee Information</div>
                <table>
                    <tr>
                        <th>{{ $num++ }}. Employee No</th><td>{{ $record->employee->employee_id ?? 'N/A' }}</td>
                        <th>{{ $num++ }}. Date of Exam</th><td>{{ $formatDate($record->examination_date) }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Full Name</th><td><strong>{{ strtoupper($record->full_name) }}</strong></td>
                        <th>{{ $num++ }}. Father's Name</th><td>{{ $record->father_name }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. DOB</th><td>{{ $formatDate($record->dob) }}</td>
                        <th>{{ $num++ }}. Age</th><td>{{ $getAge($record->dob) }} Years</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Gender</th><td>{{ ucfirst($record->gender) }}</td>
                        <th>{{ $num++ }}. Marital Status</th><td>{{ $record->marital_status }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Blood Group</th><td>{{ $record->blood_group }}</td>
                        <th>{{ $num++ }}. Mobile</th><td>{{ $record->mobile }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Company</th><td>{{ $record->company_name }}</td>
                        <th>{{ $num++ }}. Department</th><td>{{ $record->department }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Designation</th><td>{{ $record->designation }}</td>
                        <th>{{ $num++ }}. Joining Date</th><td>{{ $formatDate($record->joining_date) }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Husband Name</th><td>{{ $record->husband_name ?? 'N/A' }}</td>
                        <th>{{ $num++ }}. Dependents</th><td>{{ $record->dependent }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Habit</th><td>{{ $record->habits }}</td>
                        <th>{{ $num++ }}. Identification</th><td>{{ $record->identification_mark }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Address</th><td colspan="3">{{ $record->address }}</td>
                    </tr>
                </table>
            </div>

            {{-- 2. Occupational Details --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Occupational Details</div>
                <table>
                    <tr>
                        <th>{{ $num++ }}. Hazardous Process</th><td>{{ $record->hazardous_process ?? 'N/A' }}</td>
                        <th>{{ $num++ }}. Dangerous Operation</th><td>{{ $record->dangerous_operation ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Materials Exposed</th><td>{{ $record->materials_exposed ?? 'N/A' }}</td>
                        <th>{{ $num++ }}. Prev. Occ. History</th><td>{{ $record->prev_occ_history }}</td>
                    </tr>
                </table>
            </div>

            {{-- 3. Physical Examination --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Physical Examination</div>
                <table class="cols-6">
                    <tr>
                        <th>{{ $num++ }}. Temp</th><td>{{ $record->temperature }} °F</td>
                        <th>{{ $num++ }}. Height</th><td>{{ $record->height }} cm</td>
                        <th>{{ $num++ }}. Weight</th><td>{{ $record->weight }} kg</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. BMI</th><td>{{ $record->bmi }} kg/m²</td>
                        <th>{{ $num++ }}. Chest (Before)</th><td>{{ $record->chest_before }} cm</td>
                        <th>{{ $num++ }}. Chest (After)</th><td>{{ $record->chest_after }} cm</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Systolic BP</th><td>{{ $record->bp_systolic }} mmHg</td>
                        <th>{{ $num++ }}. Diastolic BP</th><td>{{ $record->bp_diastolic }} mmHg</td>
                        <th>{{ $num++ }}. Pulse Rate</th><td>{{ $record->heart_rate }} bpm</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. SpO<sub>2</sub></th><td>{{ $record->spo2 }} %</td>
                        <th>{{ $num++ }}. Resp. Rate</th><td>{{ $record->respiration_rate }} breaths/min</td>
                        <th></th><td></td>
                    </tr>
                </table>
            </div>

            {{-- 4. Vision Examination --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Vision Examination</div>
                <table>
                    <tr>
                        <th>{{ $num++ }}. Near Vision (R)</th><td>{{ $record->near_vision_right }}</td>
                        <th>{{ $num++ }}. Near Vision (L)</th><td>{{ $record->near_vision_left }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Distant Vision (R)</th><td>{{ $record->distant_vision_right }}</td>
                        <th>{{ $num++ }}. Distant Vision (L)</th><td>{{ $record->distant_vision_left }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Specs (R)</th><td>{{ $record->right_eye_specs }}</td>
                        <th>{{ $num++ }}. Specs (L)</th><td>{{ $record->left_eye_specs }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Colour Vision</th><td colspan="3">{{ $record->colour_vision }}</td>
                    </tr>
                </table>
            </div>

            {{-- 5. Local Examination --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Local Examination</div>
                <table class="cols-6">
                    <tr>
                        <th>{{ $num++ }}. Eye</th><td>{{ $record->eye }}</td>
                        <th>{{ $num++ }}. Nose</th><td>{{ $record->nose }}</td>
                        <th>{{ $num++ }}. Ear</th><td>{{ $record->ear }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Conjunctiva</th><td>{{ $record->conjunctiva }}</td>
                        <th>{{ $num++ }}. Tongue</th><td>{{ $record->tongue }}</td>
                        <th>{{ $num++ }}. Nails</th><td>{{ $record->nails }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Throat</th><td>{{ $record->throat }}</td>
                        <th>{{ $num++ }}. Skin</th><td>{{ $record->skin }}</td>
                        <th>{{ $num++ }}. Teeth</th><td>{{ $record->teeth }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. PEFR</th><td>{{ $record->pefr }} L/min</td>
                        <th>{{ $num++ }}. Eczema</th><td>{{ $record->eczema }}</td>
                        <th>{{ $num++ }}. Cyanosis</th><td>{{ $record->cyanosis }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Jaundice</th><td>{{ $record->jaundice }}</td>
                        <th>{{ $num++ }}. Anaemia</th><td>{{ $record->anaemia }}</td>
                        <th>{{ $num++ }}. Oedema</th><td>{{ $record->oedema }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Clubbing</th><td>{{ $record->clubbing }}</td>
                        <th>{{ $num++ }}. Allergy Status</th><td>{{ $record->allergy_status }}</td>
                        <th>{{ $num++ }}. Lymphnode</th><td>{{ $record->lymphnode ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            {{-- 6. Medical History --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Medical History</div>
                <table class="cols-6">
                    <tr>
                        <th>{{ $num++ }}. Hypertension</th><td>{{ $record->hypertension }}</td>
                        <th>{{ $num++ }}. Diabetes</th><td>{{ $record->diabetes }}</td>
                        <th>{{ $num++ }}. Dyslipidemia</th><td>{{ $record->dyslipidemia }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Radiation</th><td>{{ $record->radiation_effect }}</td>
                        <th>{{ $num++ }}. Vertigo</th><td>{{ $record->vertigo }}</td>
                        <th>{{ $num++ }}. Tuberculosis</th><td>{{ $record->tuberculosis }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Thyroid</th><td>{{ $record->thyroid_disorder }}</td>
                        <th>{{ $num++ }}. Epilepsy</th><td>{{ $record->epilepsy }}</td>
                        <th>{{ $num++ }}. Br. Asthma</th><td>{{ $record->asthma }}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th>{{ $num++ }}. Heart Disease</th><td colspan="3">{{ $record->heart_disease }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Past History</th><td colspan="3">{{ $record->past_history }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Complaint</th><td colspan="3">{{ $record->present_complain }}</td>
                    </tr>
                </table>
            </div>

            {{-- ══════════════ PAGE 2 CONTENT ══════════════ --}}

            {{-- 7. Family History — forces page 2 within this record --}}
            <div class="section page-two-start">
                <div class="section-title">{{ $sectionNum++ }}. Family History</div>
                <table>
                    <tr>
                        <th>{{ $num++ }}. Father</th><td>{{ $record->family_father }}</td>
                        <th>{{ $num++ }}. Mother</th><td>{{ $record->family_mother }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Brother</th><td>{{ $record->family_brother }}</td>
                        <th>{{ $num++ }}. Sister</th><td>{{ $record->family_sister }}</td>
                    </tr>
                </table>
            </div>

            {{-- 8. Systemic Examination --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Systemic Examination</div>
                <table>
                    <tr>
                        <th>{{ $num++ }}. Respiratory</th><td>{{ $record->resp_system }}</td>
                        <th>{{ $num++ }}. Genito Urinary</th><td>{{ $record->genito_urinary }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. CVS</th><td>{{ $record->cvs }}</td>
                        <th>{{ $num++ }}. CNS</th><td>{{ $record->cns }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Per Abdomen</th><td>{{ $record->per_abdomen }}</td>
                        <th>{{ $num++ }}. ENT</th><td>{{ $record->ent }}</td>
                    </tr>
                </table>
            </div>

            {{-- 9. Investigations --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Investigations</div>
                <table>
                    <tr>
                        <th>{{ $num++ }}. PFT</th><td>{{ $record->pft }}</td>
                        <th>{{ $num++ }}. X-Ray Chest</th><td>{{ $record->xray_chest }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Vertigo Test</th><td>{{ $record->vertigo_test }}</td>
                        <th>{{ $num++ }}. Audiometry</th><td>{{ $record->audiometry }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. ECG</th><td colspan="3">{{ $record->ecg }}</td>
                    </tr>
                </table>
            </div>

            {{-- 10. Laboratory Tests --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Laboratory Tests</div>
                <table class="cols-6">
                    <tr>
                        <th>{{ $num++ }}. HB</th><td>{{ $record->hb }} g/dL</td>
                        <th>{{ $num++ }}. WBC TC</th><td>{{ $record->wbc_tc }} cells/uL</td>
                        <th>{{ $num++ }}. Parasite (MP)</th><td>{{ $record->parasite_dc }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. RBC</th><td>{{ $record->rbc }} million/uL</td>
                        <th>{{ $num++ }}. Platelet</th><td>{{ $record->platelet }} lakh/uL</td>
                        <th>{{ $num++ }}. ESR</th><td>{{ $record->esr }} mm/hr</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. FBS</th><td>{{ $record->fbs }} mg/dL</td>
                        <th>{{ $num++ }}. PP2BS</th><td>{{ $record->pp2bs }} mg/dL</td>
                        <th>{{ $num++ }}. SGPT</th><td>{{ $record->sgpt }} U/L</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Creatinine</th><td>{{ $record->s_creatinine }} mg/dL</td>
                        <th>{{ $num++ }}. RBS</th><td>{{ $record->rbs }} mg/dL</td>
                        <th>{{ $num++ }}. Cholesterol</th><td>{{ $record->s_chol }} mg/dL</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. TRG</th><td>{{ $record->s_trg }} mg/dL</td>
                        <th>{{ $num++ }}. HDL</th><td>{{ $record->s_hdl }} mg/dL</td>
                        <th>{{ $num++ }}. LDL</th><td>{{ $record->s_ldl }} mg/dL</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th>{{ $num++ }}. C/H Ratio</th><td colspan="3">{{ $record->ch_ratio }}</td>
                    </tr>
                </table>
            </div>

            {{-- 11. Urine Report --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Urine Report</div>
                <table class="cols-6">
                    <tr>
                        <th>{{ $num++ }}. Colour</th><td>{{ $record->urine_colour }}</td>
                        <th>{{ $num++ }}. Reaction (pH)</th><td>{{ $record->urine_reaction }}</td>
                        <th>{{ $num++ }}. Albumin</th><td>{{ $record->urine_albumin }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Sugar</th><td>{{ $record->urine_sugar }}</td>
                        <th>{{ $num++ }}. Pus Cells</th><td>{{ $record->urine_pus_cell }} cells/hpf</td>
                        <th>{{ $num++ }}. RBC</th><td>{{ $record->urine_rbc }} cells/hpf</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. EpiCell</th><td>{{ $record->urine_epi_cell }} cells/hpf</td>
                        <th>{{ $num++ }}. Crystals</th><td>{{ $record->urine_crystal }}</td>
                        <th></th><td></td>
                    </tr>
                </table>
            </div>

            {{-- 12. Final Assessment --}}
            <div class="section">
                <div class="section-title">{{ $sectionNum++ }}. Final Assessment</div>
                <table>
                    <tr>
                        <th>{{ $num++ }}. Health Status</th>
                        <td>
                            <span class="fit-badge {{ strtolower($record->health_status) == 'fit' ? 'fit-badge-fit' : 'fit-badge-unfit' }}">
                                {{ $record->health_status }}
                            </span>
                        </td>
                        <th>{{ $num++ }}. Reviewed By</th>
                        <td>{{ $record->reviewed_by }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Doctor Name</th><td>Dr. {{ $record->doctor_name }}</td>
                        <th>{{ $num++ }}. Qualification</th><td>{{ $record->doctor_qualification }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Job Restriction</th><td colspan="3">{{ $record->job_restriction ?? 'None' }}</td>
                    </tr>
                    <tr>
                        <th>{{ $num++ }}. Doctor Remarks</th>
                        <td colspan="3" style="min-height:22px;">{{ $record->doctor_remarks }}</td>
                    </tr>
                </table>
            </div>

            {{-- Footer / Signatures --}}
            <div class="footer">
                <table class="signature-table">
                    <tr>
                        <td>
                            <div class="signature-line">Employee Signature</div>
                        </td>
                        <td>
                            <div class="signature-line">Medical Officer Seal &amp; Signature</div>
                            <div style="font-size:8px;margin-top:3px;">Dr. {{ $record->doctor_name }} | {{ $record->doctor_seal }}</div>
                        </td>
                    </tr>
                </table>
            </div>

        </div>{{-- end record wrapper --}}
    @endforeach
</body>
</html>
