<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Form 33</title>
    <style>
        @page { size: A4; margin: 10mm; }
        body { font-family: "Times New Roman", serif; font-size: 12px; color: #000; }
        .page { width: 100%; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 4px; vertical-align: top; }
        .header { text-align: center; border: 1px solid #000; padding: 5px; margin-bottom: 10px; }
        .header h1 { font-size: 16px; }
        .subtitle { font-size: 13px; font-weight: bold; }
        .rule-ref, .issued-by { font-size: 11px; }
        .lbl { width: 55%; font-weight: bold; }
        .val { width: 45%; border-bottom: 1px dotted #000; }
        .sub-lbl { padding-left: 20px; font-weight: bold; }
        .divider { border-top: 1px solid #000; margin: 10px 0; }
        .cert-text { margin: 5px 0; text-align: justify; }
        .dline { border-bottom: 1px dotted #000; padding: 0 5px; }
        .fit-status { font-weight: bold; text-decoration: underline; }
        .sig-container { margin-top: 20px; }
        .sig-box { text-align: center; }
        .sig-space { height: 40px; }
        .sig-line { border-top: 1px solid #000; display: inline-block; width: 80%; }
        .ext-table th, .ext-table td { border: 1px solid #000; text-align: center; }
        .footer-note { font-size: 10px; text-align: center; margin-top: 10px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @foreach($records as $record)
        @php 
            $dob = $record->dob ? $record->dob->format('d-m-Y') : 'N/A'; 
            $age = $record->dob ? (int) $record->dob->diffInYears(now()) : 'N/A'; 
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
            <div class="cert-text"> I certify that I have personally examined the above named person whose identification marks are <span class="dline">{{ $record->identification_mark ?? '................................................' }}</span> and who is desirous of being employed in above mentioned process / operation and that his / her age, as can be ascertained from my examination, is <span class="dline">{{ $age }}</span> years. </div>
            <div class="cert-text"> In my opinion he / she is <span class="fit-status">{{ $isFit ? 'FIT' : 'UNFIT' }}</span>. </div>
            @if(!$isFit) 
                <div class="cert-text"> <strong>Reason:</strong> <span class="dline">{{ $record->doctor_remarks ?? '-' }}</span> </div>
            @endif
            <table class="sig-container">
                <tr>
                    <td class="sig-box">
                        <div class="sig-space"></div>
                        <div class="sig-line">Person Signature</div>
                    </td>
                    <td class="sig-box">
                        <div class="sig-space"></div>
                        <div class="sig-line">Doctor Signature</div>
                    </td>
                </tr>
            </table>
            <table class="ext-table">
                <thead>
                    <tr><th>Examined</th><th>Valid Till</th><th>Remarks</th><th>Sign</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $examDate }}</td>
                        <td>{{ $extensionDate }}</td>
                        <td>{{ $record->present_complain ?? '-' }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div class="footer-note"> Note: This certificate is not proof of age. </div>
        </div>
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
