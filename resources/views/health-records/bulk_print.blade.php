<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Medical Reports</title>
    <style>
        @page { size: A4; margin: 10mm 12mm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9.5px; line-height: 1.3; color: #111; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2px solid #222; padding-bottom: 5px; margin-bottom: 8px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; color: #000; letter-spacing: 0.5px; }
        .header p { margin: 1px 0; font-weight: bold; font-size: 9px; color: #333; }
        .report-title { text-align: center; background: #f1f3f5; padding: 5px; margin-bottom: 8px; border: 1px solid #ced4da; border-radius: 4px; }
        .report-title h2 { margin: 0; font-size: 12px; text-transform: uppercase; color: #111; font-weight: 800; }
        .section { margin-bottom: 8px; }
        .section-title { background: #e9ecef; padding: 2px 10px; font-weight: bold; font-size: 9.5px; border-left: 4px solid #111; margin-bottom: 4px; text-transform: uppercase; color: #000; letter-spacing: 0.2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 4px; table-layout: fixed; }
        table th, table td { border: 1px solid #adb5bd; padding: 3px 6px; text-align: left; vertical-align: top; }
        table th { background-color: #f8f9fa; width: 18%; color: #343a40; font-weight: 700; font-size: 9px; }
        table td { color: #212529; overflow-wrap: break-word; word-wrap: break-word; font-size: 9.5px; }
        .footer { margin-top: 15px; position: relative; }
        .signature-table { width: 100%; margin-top: 25px; border: none; }
        .signature-table td { border: none; text-align: center; vertical-align: bottom; padding: 0; }
        .signature-line { border-top: 1.5px solid #000; width: 85%; margin: 0 auto; padding-top: 4px; font-weight: bold; font-size: 9.5px; }
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 70px; color: rgba(0,0,0,0.02); z-index: -1; white-space: nowrap; pointer-events: none; }
        .page-break { page-break-after: always; }
        .fit-badge { display: inline-block; padding: 1px 6px; border-radius: 3px; font-weight: bold; text-transform: uppercase; font-size: 8.5px; }
        .fit-badge-fit { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .fit-badge-unfit { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body>
    @php 
        $formatDate = function($date) {
            if (!$date) return 'N/A';
            if ($date instanceof \Carbon\Carbon) return $date->format('d/m/Y');
            try { return \Carbon\Carbon::parse($date)->format('d/m/Y'); } catch (\Exception $e) { return $date; }
        };
        $getAge = function($date) {
            if (!$date) return 'N/A';
            try { return (int) \Carbon\Carbon::parse($date)->age; } catch (\Exception $e) { return 'N/A'; }
        };
    @endphp

    @foreach($records as $record)
        @php 
            $num = 1; 
            $sectionNum = 1;
        @endphp
        <div class="watermark">MEDICAL CERTIFICATE</div>

        <div class="header">
            <h1>{{ config('enterprise-ui.workspace_name', 'Divit Hospital') }}</h1>
            <p>Occupational Health & Medical Services</p>
            <p style="font-size: 8px; font-weight: normal; color: #555;">Reg No: 12345/OH/2024 | Contact: +91 98765 43210</p>
        </div>

        <div class="report-title">
            <h2>Periodic Medical Examination Report</h2>
        </div>

        <div class="section">
            <div class="section-title">{{ $sectionNum++ }}. Employee Information</div>
            <table>
                <tr>
                    <th>{{ $num++ }}. Employee No</th><td>{{ $record->employee->employee_id ?? 'N/A' }}</td>
                    <th>{{ $num++ }}. Date of Exam</th><td>{{ $formatDate($record->examination_date) }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. Full Name</th><td><strong>{{ strtoupper($record->full_name) }}</strong></td>
                    <th>{{ $num++ }}. Father’s Name</th><td>{{ $record->father_name }}</td>
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

        <div class="section">
            <div class="section-title">{{ $sectionNum++ }}. Occupational Details</div>
            <table>
                <tr>
                    <th>{{ $num++ }}. Hazardous Process</th><td>{{ $record->hazardous_process ?? 'N/A' }}</td>
                    <th>{{ $num++ }}. Dangerous Operation</th><td>{{ $record->dangerous_operation ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. Materials Exposed</th><td>{{ $record->materials_exposed ?? 'N/A' }}</td>
                    <th>{{ $num++ }}. Previous Occ. History</th><td>{{ $record->prev_occ_history }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">{{ $sectionNum++ }}. Physical Examination</div>
            <table>
                <tr>
                    <th>{{ $num++ }}. Temp</th><td>{{ $record->temperature }} °F</td>
                    <th>{{ $num++ }}. Height</th><td>{{ $record->height }} cm</td>
                    <th>{{ $num++ }}. Weight</th><td>{{ $record->weight }} kg</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. BMI</th><td>{{ $record->bmi }}</td>
                    <th>{{ $num++ }}. Chest (Before)</th><td>{{ $record->chest_before }} cm</td>
                    <th>{{ $num++ }}. Chest (After)</th><td>{{ $record->chest_after }} cm</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. Systolic BP</th><td>{{ $record->bp_systolic }} mmHg</td>
                    <th>{{ $num++ }}. Diastolic BP</th><td>{{ $record->bp_diastolic }} mmHg</td>
                    <th>{{ $num++ }}. Pulse Rate</th><td>{{ $record->heart_rate }} bpm</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. SpO₂</th><td>{{ $record->spo2 }} %</td>
                    <th>{{ $num++ }}. Resp. Rate</th><td>{{ $record->respiration_rate }}</td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </div>

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

        <div class="page-break"></div>

        <div class="section">
            <div class="section-title">{{ $sectionNum++ }}. Local Examination</div>
            <table>
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
                    <th>{{ $num++ }}. PEFR</th><td>{{ $record->pefr }}</td>
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

        <div class="section">
            <div class="section-title">{{ $sectionNum++ }}. Medical History</div>
            <table>
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
                <tr>
                    <th>{{ $num++ }}. Heart Disease</th><td>{{ $record->heart_disease }}</td>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. Past History</th><td colspan="5">{{ $record->past_history }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. Complaint</th><td colspan="5">{{ $record->present_complain }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
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

        <div class="section">
            <div class="section-title">{{ $sectionNum++ }}. Laboratory Tests</div>
            <table>
                <tr>
                    <th>{{ $num++ }}. HB</th><td>{{ $record->hb }}</td>
                    <th>{{ $num++ }}. WBC TC</th><td>{{ $record->wbc_tc }}</td>
                    <th>{{ $num++ }}. Parasite (MP)</th><td>{{ $record->parasite_dc }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. RBC</th><td>{{ $record->rbc }}</td>
                    <th>{{ $num++ }}. Platelet</th><td>{{ $record->platelet }}</td>
                    <th>{{ $num++ }}. ESR</th><td>{{ $record->esr }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. FBS</th><td>{{ $record->fbs }}</td>
                    <th>{{ $num++ }}. PP2BS</th><td>{{ $record->pp2bs }}</td>
                    <th>{{ $num++ }}. SGPT</th><td>{{ $record->sgpt }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. Creatinine</th><td>{{ $record->s_creatinine }}</td>
                    <th>{{ $num++ }}. RBS</th><td>{{ $record->rbs }}</td>
                    <th>{{ $num++ }}. Cholesterol</th><td>{{ $record->s_chol }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. TRG</th><td>{{ $record->s_trg }}</td>
                    <th>{{ $num++ }}. HDL</th><td>{{ $record->s_hdl }}</td>
                    <th>{{ $num++ }}. LDL</th><td>{{ $record->s_ldl }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. C/H Ratio</th><td colspan="5">{{ $record->ch_ratio }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">{{ $sectionNum++ }}. Urine Report</div>
            <table>
                <tr>
                    <th>{{ $num++ }}. Colour</th><td>{{ $record->urine_colour }}</td>
                    <th>{{ $num++ }}. Reaction (pH)</th><td>{{ $record->urine_reaction }}</td>
                    <th>{{ $num++ }}. Albumin</th><td>{{ $record->urine_albumin }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. Sugar</th><td>{{ $record->urine_sugar }}</td>
                    <th>{{ $num++ }}. Pus Cells</th><td>{{ $record->urine_pus_cell }}</td>
                    <th>{{ $num++ }}. RBC</th><td>{{ $record->urine_rbc }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. EpiCell</th><td>{{ $record->urine_epi_cell }}</td>
                    <th>{{ $num++ }}. Crystals</th><td>{{ $record->urine_crystal }}</td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">{{ $sectionNum++ }}. Final Assessment</div>
            <table>
                <tr>
                    <th>{{ $num++ }}. Health Status</th>
                    <td><span class="fit-badge {{ strtolower($record->health_status) == 'fit' ? 'fit-badge-fit' : 'fit-badge-unfit' }}">{{ $record->health_status }}</span></td>
                    <th>{{ $num++ }}. Reviewed By</th><td>{{ $record->reviewed_by }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. Doctor Name</th><td>Dr. {{ $record->doctor_name }}</td>
                    <th>{{ $num++ }}. Qualification</th><td>{{ $record->doctor_qualification }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. Job Restriction</th><td colspan="3">{{ $record->job_restriction ?? 'None' }}</td>
                </tr>
                <tr>
                    <th>{{ $num++ }}. Doctor Remarks</th><td colspan="3" style="min-height: 25px;">{{ $record->doctor_remarks }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <table class="signature-table">
                <tr>
                    <td><div class="signature-line">Employee Signature</div></td>
                    <td>
                        <div class="signature-line">Medical Officer Seal & Signature</div>
                        <div style="font-size: 8px; margin-top: 3px;">Dr. {{ $record->doctor_name }} | {{ $record->doctor_seal }}</div>
                    </td>
                </tr>
            </table>
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
