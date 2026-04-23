<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attachments - {{ $record->full_name }}</title>
    <style>
        @page { size: A4; margin: 10mm 12mm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9.5px; line-height: 1.3; color: #111; margin: 0; padding: 0; }
        
        .header { text-align: center; border-bottom: 2px solid #222; padding-bottom: 5px; margin-bottom: 8px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; color: #000; letter-spacing: 0.5px; }
        .header p { margin: 1px 0; font-weight: bold; font-size: 9px; color: #333; }
        
        .report-title { text-align: center; background: #f1f3f5; padding: 5px; margin-bottom: 15px; border: 1px solid #ced4da; border-radius: 4px; }
        .report-title h2 { margin: 0; font-size: 12px; text-transform: uppercase; color: #111; font-weight: 800; }
        
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 70px; color: rgba(0,0,0,0.02); z-index: -1; white-space: nowrap; pointer-events: none; }
        
        .page-break { page-break-after: always; }
        
        .attachment-container { text-align: center; margin-bottom: 30px; page-break-inside: avoid; }
        .attachment-title { font-size: 11px; margin-bottom: 10px; background: #e9ecef; padding: 4px; border-radius: 4px; font-weight: bold; }
        .attachment-img { max-width: 100%; max-height: 23cm; border: 1px solid #ddd; padding: 5px; background: #fff; }
    </style>
</head>
<body>
    <div class="watermark">ATTACHMENTS</div>

    <div class="header">
        <h1>{{ config('enterprise-ui.workspace_name', 'Divit Hospital') }}</h1>
        <p>Occupational Health & Medical Services</p>
        <p style="font-size: 8px; font-weight: normal; color: #555;">Reg No: 12345/OH/2024 | Contact: +91 98765 43210</p>
    </div>

    <div class="report-title">
        <h2>Supporting Document Attachments</h2>
    </div>
    
    @foreach($imageAttachments as $attachment)
        <div class="attachment-container">
            <div class="attachment-title">
                {{ $attachment['name'] }} ({{ $attachment['size'] }} - {{ $attachment['date'] }})
            </div>
            <img src="{{ $attachment['src'] }}" class="attachment-img" alt="Attachment">
        </div>
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
