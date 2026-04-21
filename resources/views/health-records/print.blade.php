<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medical Report - {{ $record->full_name }}</title>
    <style>
        @page { size: A4; margin: 12mm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9.5px; line-height: 1.4; color: #111; margin: 0; padding: 0; }
        
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 15px; }
        .header h1 { margin: 0; font-size: 22px; text-transform: uppercase; color: #000; letter-spacing: 1px; }
        .header p { margin: 2px 0; font-weight: bold; font-size: 10px; color: #444; }
        
        .report-title { text-align: center; background: #f8f9fa; padding: 6px; margin-bottom: 15px; border: 1px solid #dee2e6; border-radius: 4px; }
        .report-title h2 { margin: 0; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #222; }
        
        .section { margin-bottom: 15px; }
        .section-title { background: #f1f3f5; padding: 4px 10px; font-weight: bold; font-size: 10px; border-left: 4px solid #1a1a1a; margin-bottom: 8px; text-transform: uppercase; color: #333; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
        table th, table td { border: 1px solid #cbd5e0; padding: 5px 8px; text-align: left; vertical-align: top; }
        table th { background-color: #f8fafc; width: 25%; color: #475569; font-weight: bold; font-size: 9px; }
        table td { color: #1e293b; }
        
        .footer { margin-top: 30px; position: relative; }
        .signature-table { width: 100%; margin-top: 50px; border: none; }
        .signature-table td { border: none; text-align: center; vertical-align: bottom; }
        .signature-line { border-top: 1.5px solid #1a1a1a; width: 80%; margin: 0 auto; padding-top: 8px; font-weight: bold; font-size: 10px; }
        
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 70px; color: rgba(0,0,0,0.03); z-index: -1; white-space: nowrap; pointer-events: none; }
        
        .page-break { page-break-after: always; }
        
        .fit-badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .fit-badge-fit { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .fit-badge-unfit { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body>
    @php 
        $num = 1; 
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
    <div class="watermark">MEDICAL CERTIFICATE</div>

    <div class="header">
        <h1>{{ config('enterprise-ui.workspace_name', 'LifeCare Hospital') }}</h1>
        <p>Occupational Health & Medical Services</p>
        <p style="font-size: 9px; font-weight: normal; color: #666;">Reg No: 12345/OH/2024 | Contact: +91 98765 43210</p>
    </div>

    <div class="report-title">
        <h2>Periodic Medical Examination Report</h2>
    </div>

    <!-- 2. Employee Information -->
    <div class="section">
        <div class="section-title">2. Employee Information</div>
        <table>
            <tr>
                <th>{{ $num++ }}. Employee No</th><td>{{ $record->employee_id }}</td>
                <th>{{ $num++ }}. Date of Examination</th><td>{{ $formatDate($record->examination_date) }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Employee Name</th><td><strong>{{ $record->full_name }}</strong></td>
                <th>{{ $num++ }}. Father’s Name</th><td>{{ $record->father_name }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Date of Birth</th><td>{{ $formatDate($record->dob) }}</td>
                <th>{{ $num++ }}. Age</th><td>{{ $getAge($record->dob) }} Years</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Department</th><td>{{ $record->department }}</td>
                <th>{{ $num++ }}. Sex</th><td>{{ ucfirst($record->gender) }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Joining Date</th><td>{{ $formatDate($record->joining_date) }}</td>
                <th>{{ $num++ }}. Identification Mark</th><td>{{ $record->identification_mark }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Habit (H/O Habit)</th><td>{{ $record->habits }}</td>
                <th>{{ $num++ }}. Marital Status</th><td>{{ $record->marital_status }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Designation</th><td>{{ $record->designation }}</td>
                <th>{{ $num++ }}. Husband’s Name</th><td>{{ $record->husband_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Company Name</th><td>{{ $record->company_name }}</td>
                <th>{{ $num++ }}. Dependents</th><td>{{ $record->dependent }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Previous Occupational History</th><td colspan="3">{{ $record->prev_occ_history }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Mobile Number</th><td>{{ $record->mobile }}</td>
                <th>{{ $num++ }}. Address</th><td>{{ $record->address }}</td>
            </tr>
        </table>
    </div>

    <!-- 3. Physical Examination -->
    <div class="section">
        <div class="section-title">3. Physical Examination</div>
        <table>
            <tr>
                <th>{{ $num++ }}. Temperature</th><td>{{ $record->temperature }} °F</td>
                <th>{{ $num++ }}. Height</th><td>{{ $record->height }} cm</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Chest Before Breathing</th><td>{{ $record->chest_before }} cm</td>
                <th>{{ $num++ }}. Pulse Rate</th><td>{{ $record->heart_rate }} bpm</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Weight</th><td>{{ $record->weight }} kg</td>
                <th>{{ $num++ }}. Chest After Breathing</th><td>{{ $record->chest_after }} cm</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Blood Pressure (BP)</th><td>{{ $record->bp_systolic }}/{{ $record->bp_diastolic }} mmHg</td>
                <th>{{ $num++ }}. BMI</th><td><strong>{{ $record->bmi }} kg/m²</strong></td>
            </tr>
            <tr>
                <th>{{ $num++ }}. SpO₂</th><td>{{ $record->spo2 }} %</td>
                <th>{{ $num++ }}. Respiration Rate</th><td>{{ $record->respiration_rate }}</td>
            </tr>
        </table>
    </div>

    <!-- 4. Vision Examination -->
    <div class="section">
        <div class="section-title">4. Vision Examination</div>
        <table>
            <tr>
                <th colspan="2" style="text-align: center; background: #edf2f7;">Right Eye</th>
                <th colspan="2" style="text-align: center; background: #edf2f7;">Left Eye</th>
            </tr>
            <tr>
                <th>Specs</th><td>{{ $record->right_eye_specs }}</td>
                <th>Specs</th><td>{{ $record->left_eye_specs }}</td>
            </tr>
            <tr>
                <th>Near Vision</th><td>{{ $record->near_vision_right }}</td>
                <th>Near Vision</th><td>{{ $record->near_vision_left }}</td>
            </tr>
            <tr>
                <th>Distant Vision</th><td>{{ $record->distant_vision_right }}</td>
                <th>Distant Vision</th><td>{{ $record->distant_vision_left }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Colour Vision</th><td colspan="3">{{ $record->colour_vision }}</td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- 5. Local Examination -->
    <div class="section">
        <div class="section-title">5. Local Examination</div>
        <table style="font-size: 8.5px;">
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
                <th>{{ $num++ }}. Allergy</th><td>{{ $record->allergy_status }}</td>
                <td colspan="2"></td>
            </tr>
        </table>
    </div>

    <!-- 6. Medical History Examination -->
    <div class="section">
        <div class="section-title">6. Medical History Examination</div>
        <table>
            <tr>
                <th>{{ $num++ }}. Hypertension</th><td>{{ $record->hypertension }}</td>
                <th>{{ $num++ }}. Diabetes</th><td>{{ $record->diabetes }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Dyslipidemia</th><td>{{ $record->dyslipidemia }}</td>
                <th>{{ $num++ }}. Radiation Effect</th><td>{{ $record->radiation_effect }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Vertigo</th><td>{{ $record->vertigo }}</td>
                <th>{{ $num++ }}. Tuberculosis</th><td>{{ $record->tuberculosis }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Thyroid Disorder</th><td>{{ $record->thyroid_disorder }}</td>
                <th>{{ $num++ }}. Epilepsy</th><td>{{ $record->epilepsy }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Br_Asthma</th><td>{{ $record->asthma }}</td>
                <th>{{ $num++ }}. Heart Disease</th><td>{{ $record->heart_disease }}</td>
            </tr>
        </table>
    </div>

    <!-- 7. History Details -->
    <div class="section">
        <div class="section-title">7. History Details</div>
        <table>
            <tr>
                <th>{{ $num++ }}. Past History</th><td colspan="3">{{ $record->past_history }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Present Complaint</th><td colspan="3">{{ $record->present_complain }}</td>
            </tr>
        </table>
    </div>

    <!-- 8. Family History -->
    <div class="section">
        <div class="section-title">8. Family History</div>
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

    <!-- 9. Systemic Examination -->
    <div class="section">
        <div class="section-title">9. Systemic Examination</div>
        <table>
            <tr>
                <th>{{ $num++ }}. Respiratory System</th><td>{{ $record->resp_system }}</td>
                <th>{{ $num++ }}. Genito Urinary System</th><td>{{ $record->genito_urinary }}</td>
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

    <!-- 10. Investigations -->
    <div class="section">
        <div class="section-title">10. Investigations</div>
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

    <!-- 11. Laboratory Tests -->
    <div class="section">
        <div class="section-title">11. Laboratory Tests</div>
        <table style="font-size: 8.5px;">
            <tr>
                <th>{{ $num++ }}. HB</th><td>{{ $record->hb }}</td>
                <th>{{ $num++ }}. WBC Count</th><td>{{ $record->wbc_tc }}</td>
                <th>{{ $num++ }}. Parasite (MP)</th><td>{{ $record->parasite_dc }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. RBC Count</th><td>{{ $record->rbc }}</td>
                <th>{{ $num++ }}. Platelet Count</th><td>{{ $record->platelet }}</td>
                <th>{{ $num++ }}. ESR</th><td>{{ $record->esr }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. FBS</th><td>{{ $record->fbs }}</td>
                <th>{{ $num++ }}. PP2BS</th><td>{{ $record->pp2bs }}</td>
                <th>{{ $num++ }}. SGPT</th><td>{{ $record->sgpt }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Serum Creatinine</th><td>{{ $record->s_creatinine }}</td>
                <th>{{ $num++ }}. RBS</th><td>{{ $record->rbs }}</td>
                <th>{{ $num++ }}. Serum Cholesterol</th><td>{{ $record->s_chol }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Serum TRG</th><td>{{ $record->s_trg }}</td>
                <th>{{ $num++ }}. Serum HDL</th><td>{{ $record->s_hdl }}</td>
                <th>{{ $num++ }}. Serum LDL</th><td>{{ $record->s_ldl }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. C/H Ratio</th><td colspan="5">{{ $record->ch_ratio }}</td>
            </tr>
        </table>
    </div>

    <!-- 12. Urine Report -->
    <div class="section">
        <div class="section-title">12. Urine Report</div>
        <table style="font-size: 8.5px;">
            <tr>
                <th>{{ $num++ }}. Colour</th><td>{{ $record->urine_colour }}</td>
                <th>{{ $num++ }}. Reaction (pH)</th><td>{{ $record->urine_reaction }}</td>
                <th>{{ $num++ }}. Albumin</th><td>{{ $record->urine_albumin }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Sugar</th><td>{{ $record->urine_sugar }}</td>
                <th>{{ $num++ }}. Pus Cells</th><td>{{ $record->urine_pus_cell }}</td>
                <th>{{ $num++ }}. Urine RBC</th><td>{{ $record->urine_rbc }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. EpiCell</th><td>{{ $record->urine_epi_cell }}</td>
                <th>{{ $num++ }}. Crystals</th><td>{{ $record->urine_crystal }}</td>
                <td colspan="2"></td>
            </tr>
        </table>
    </div>

    <!-- 13. Final Assessment -->
    <div class="section">
        <div class="section-title">13. Final Assessment</div>
        <table>
            <tr>
                <th style="width: 25%;">{{ $num++ }}. Health Status</th>
                <td>
                    <span class="fit-badge {{ strtolower($record->health_status) == 'fit' ? 'fit-badge-fit' : 'fit-badge-unfit' }}">
                        {{ $record->health_status }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- 14. Doctor Details -->
    <div class="section">
        <div class="section-title">14. Doctor Details</div>
        <table>
            <tr>
                <th>{{ $num++ }}. Doctor Name</th><td>Dr. {{ $record->doctor_name }}</td>
                <th>{{ $num++ }}. Qualification</th><td>{{ $record->doctor_qualification }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Doctor Signature</th><td>{{ $record->doctor_signature ? 'Signed' : 'Pending' }}</td>
                <th>{{ $num++ }}. Seal of Doctor</th><td>{{ $record->doctor_seal }}</td>
            </tr>
        </table>
    </div>

    <!-- 15. Job & Advice -->
    <div class="section">
        <div class="section-title">15. Job & Advice</div>
        <table>
            <tr>
                <th>{{ $num++ }}. Job Restriction</th><td colspan="3">{{ $record->job_restriction ?? 'None' }}</td>
            </tr>
            <tr>
                <th>{{ $num++ }}. Doctor Remarks</th><td colspan="3" style="min-height: 30px;">{{ $record->doctor_remarks }}</td>
            </tr>
        </table>
    </div>

    <!-- 16. Review Section -->
    <div class="section">
        <div class="section-title">16. Review Section</div>
        <table>
            <tr>
                <th style="width: 25%;">{{ $num++ }}. Reviewed By</th>
                <td>{{ $record->reviewed_by }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-line">Employee's Signature</div>
                </td>
                <td>
                    <div class="signature-line">Seal & Signature of Medical Officer</div>
                    <div style="font-size: 8px; margin-top: 4px;">Dr. {{ $record->doctor_name }} {{ $record->doctor_qualification ? "({$record->doctor_qualification})" : '' }}</div>
                    @if($record->doctor_seal)
                        <div style="font-size: 7px; color: #666; font-style: italic;">Seal: {{ $record->doctor_seal }}</div>
                    @endif
                </td>
            </tr>
        </table>
        <div style="text-align: center; margin-top: 15px; font-size: 7px; color: #94a3b8; border-top: 0.5px solid #e2e8f0; padding-top: 8px;">
            Report UID: {{ $record->uuid }} | Page 2 of 2 | Generated: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
