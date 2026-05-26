<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employees Export</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        .company-section { margin-bottom: 30px; page-break-inside: avoid; }
        .company-name { font-size: 16px; font-weight: bold; color: #1a56db; margin-bottom: 10px; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { background-color: #f9fafb; color: #374151; font-weight: 700; text-transform: uppercase; font-size: 9px; letter-spacing: 0.05em; padding: 8px 10px; border: 1px solid #e5e7eb; text-align: left; }
        td { padding: 8px 10px; border: 1px solid #e5e7eb; vertical-align: middle; }
        tr:nth-child(even) { background-color: #fbfcfe; }
        .sr-no { width: 40px; text-align: center; font-family: monospace; color: #6b7280; }
        .emp-no { font-weight: bold; color: #111827; }
        .name { font-weight: 600; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #9ca3af; border-top: 1px solid #f3f4f6; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="footer">
        Generated on {{ date('d M Y, h:i A') }} | {{ config('app.name') }} Healthcare System
    </div>

    @foreach($records as $companyName => $companyRecords)
        <div class="company-section">
            <div class="company-name">{{ $companyName }}</div>
            <table>
                <thead>
                    <tr>
                        <th class="sr-no">SR NO</th>
                        <th>EMP NO</th>
                        <th>NAME</th>
                        <th>AGE / SEX</th>
                        <th>DEPARTMENT</th>
                        <th>DESIGNATION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companyRecords as $index => $record)
                        @php
                            // Handle both HealthCheckup and Employee models
                            $employee = ($record instanceof \App\Models\Employee) ? $record : $record->employee;
                            if (!$employee) continue;
                            
                            $age = $employee->dob ? \Carbon\Carbon::parse($employee->dob)->age : 'N/A';
                            $sex = strtoupper(substr($employee->gender ?? '', 0, 1));
                        @endphp
                        <tr>
                            <td class="sr-no">{{ $index + 1 }}</td>
                            <td class="emp-no">{{ $employee->employee_id }}</td>
                            <td class="name">{{ $employee->full_name }}</td>
                            <td>{{ $age }} / {{ $sex }}</td>
                            <td>{{ $employee->department ?? 'N/A' }}</td>
                            <td>{{ $employee->designation ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>
</html>
