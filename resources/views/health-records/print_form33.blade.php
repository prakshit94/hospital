<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form No. 33 — Certificate of Fitness</title>
<style>
@page {
    size: A4;
    margin: 8mm;
}

body {
    font-family: "Times New Roman", serif;
    font-size: 13px;
    color: #000;
    line-height: 1.25;
}

.page {
    width: 100%;
}

/* Header */
.header {
    text-align: center;
    margin-bottom: 6px;
}

.header h1 {
    font-size: 20px;
    margin: 0;
    text-transform: uppercase;
}

.rule-ref {
    font-size: 13px;
    margin-bottom: 2px;
}

.subtitle {
    font-size: 14px;
    font-weight: bold;
}

.issued-by {
    font-size: 13px;
    margin-top: 3px;
}

/* Table */
.details-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 4px;
}

.details-table td {
    padding: 2px 0;
    vertical-align: top;
    font-size: 13px;
}

.lbl {
    width: 45%;
}

.val {
    width: 55%;
    font-weight: bold;
    font-size: 13px;
}

/* Certificate Text */
.cert-text {
    margin: 6px 0;
    text-align: justify;
    font-size: 13px;
    line-height: 1.35;
}

/* Signature Section */
.sig-section {
    width: 100%;
    margin-top: 8px;
}

.sig-box {
    width: 50%;
    vertical-align: top;
    font-size: 13px;
}

.stamp-area {
    padding: 6px;
    min-height: 50px;
    width: 85%;
    font-size: 12px;
    margin-top: 4px;
}

/* Extension Table */
.ext-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 7px;
}

.ext-table th,
.ext-table td {
    border: 1px solid #000;
    padding: 4px;
    text-align: left;
    font-size: 13px;
}

.ext-table th {
    font-weight: normal;
    text-align: center;
}

/* Footer */
.footer-note {
    margin-top: 6px;
    font-size: 12px;
    line-height: 1.3;
}

/* Strike */
.strike {
    text-decoration: line-through;
    color: #666;
}

/* Prevent page break */
.page {
    page-break-after: avoid;
    page-break-before: avoid;
}

table, tr, td {
    page-break-inside: avoid;
}
</style>
</head>
<body>
    @php 
        $dob = $record->dob ? $record->dob->format('d-m-Y') : ' '; 
        $age = $record->dob ? (int) $record->dob->diffInYears(now()) : ' '; 
        $isFit = strtoupper($record->health_status ?? '') === 'FIT';
    @endphp

    <div class="page">
        <div class="header">
            <h1>FORM NO. 33</h1> 
            <div class="rule-ref">(Prescribed under Rule 68-T and 102)</div> 
            <div class="subtitle">Certificate of Fitness of employment in hazardous process and operations.</div> 
            <div class="issued-by">(TO BE ISSUED BY FACTORY MEDICAL OFFICER)</div> 
        </div>

        <table class="details-table">
    <tr>
        <td class="lbl">1. Serial number in the register of adult workers :</td> 
        <td class="val">: {{ $record->employee->employee_id ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="lbl">2. Name of the person examined :</td> 
        <td class="val">: {{ strtoupper($record->full_name) }}</td>
    </tr>
    <tr>
        <td class="lbl">3. Father's Name :</td> 
        <td class="val">: {{ $record->father_name ?? '-' }}</td>
    </tr>
    <tr>
        <td class="lbl">4. Sex :</td> 
        <td class="val">: {{ ucfirst($record->gender ?? '-') }}</td>
    </tr>
    
    <tr>
        <td class="lbl">6. Date of birth, if available :</td> 
        <td class="val">: {{ $dob }}</td>
    </tr>
    <tr>
        <td class="lbl">5. Residence :</td> 
        <td class="val">: {{ $record->address ?? '-' }}</td>
    </tr>

    <tr>
    <td class="lbl">7. Name & address of the factory :</td>
    <td class="val">
        : {{ optional($record->company)->name ?? $record->company_name }}<br>
        {{ optional($record->company)->address ?? '' }}
    </td>
</tr>

    <!-- Main Point 8 -->
    <tr>
        <td class="lbl">8. The worker is employed/proposed :</td> 
        <td class="val">: {{ $record->department ?? '-' }}</td>
    </tr>

    <!-- Sub-points under 8 -->
    <tr>
        <td class="lbl sub"> &nbsp;&nbsp;&nbsp;(a) Hazardous process :</td> 
        <td class="val">: {{ $record->hazardous_process ?? '-' }}</td>
    </tr>
    <tr>
        <td class="lbl sub"> &nbsp;&nbsp;&nbsp;(b) Dangerous operation :</td> 
        <td class="val">: {{ $record->dangerous_operation ?? '-' }}</td>
    </tr>
</table>

        <div class="cert-text">
            I certify that I have personally examined the above named person whose identification marks are <strong>{{ $record->identification_mark ?? '................................................' }}</strong> and who is desirous of being employed in above mentioned process/operation and that his/her, age, as can be ascertained from my examination, is <strong>{{ $age }}</strong> years. 
        </div>

        <div class="cert-text {{ !$isFit ? 'strike' : '' }}">
            In my opinion he/she is fit for employment in the Said manufacturing process/operation. 
        </div>

        <div class="cert-text {{ $isFit ? 'strike' : '' }}">
            In my opinion he/she is unfit for employment in the said manufacturing process/operation for the reason <strong>{{ !$isFit ? ($record->doctor_remarks ?? ' ') : ' ' }}</strong>. He/She is referred for further examination to the Certifying Surgeon. 
        </div>

        <div class="cert-text" style="text-align: center;">
    The serial number of previous certificate is 
    <strong>{{ $record->prev_cert_no ?? '........................................' }}</strong>.
</div>

        <table class="sig-section">
            <tr>
                <td class="sig-box">
                    Signature / left hand thumb<br>
                    impression of the person examined : 
                </td>
                <td class="sig-box">
                    Signature of the Factory Medical Officer : 
                    <div class="stamp-area">
                        Stamp of factory Medical Officer with<br>
                        Name of the Factory : 
                    </div>
                </td>
            </tr>
        </table>

        <table class="ext-table">
    <thead>
        <tr>
            <th width="15%">
                I certify that I examined the person mentioned above on (date of examination)
            </th> 
            <th width="30%">
                I extend this certificate unfit (if certificate is not extended, the period for which the worker is considered unfit for work is to be mentioned)
            </th> 
            <th width="30%">
                Signs and symptoms observed during examination
            </th> 
            <th width="25%">
                Signature of the Factory medical Officer with date.
            </th> 
        </tr>
    </thead>
    <tbody>
        <tr>
            <!-- Date of Examination -->
            <td height="60px">
                {{ optional($record->examination_date)->format('d-m-Y') }}
            </td>

            <!-- Fit / Unfit -->
            <td>
                {{ $record->health_status ?? 'Fit' }}<br>
                {{ $record->job_restriction ?? '' }}
            </td>

            <!-- Symptoms -->
            <td>
                {{ $record->present_complain ?? $record->diagnosis ?? '' }}
            </td>

            <!-- Doctor Signature -->
            <td>
                Dr. {{ $record->doctor_name ?? '' }}<br>
                {{ $record->doctor_qualification ?? '' }}<br>

                @if($record->doctor_signature)
                    <!-- <img src="{{ public_path('storage/'.$record->doctor_signature) }}" height="40"> -->
                @endif

                <br>
                Date: {{ optional($record->examination_date)->format('d-m-Y') }}
            </td>
        </tr>
    </tbody>
</table>

        <div class="footer-note">
            <strong>Notes :</strong> <br>
            1. If declared unfit, reference should be made immediately to the Certifying Surgeon. <br>
            2. Certifying Surgeon should communicate his findings to the occupier within 30 days of the receipt of this reference. 
        </div>
    </div>
</body>
</html>