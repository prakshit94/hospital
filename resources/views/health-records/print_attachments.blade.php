<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attachments - {{ $record->full_name }}</title>
    <style>
        @page { 
            size: A4; 
            margin: 15mm;
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            line-height: 1.5; 
            color: #2D3748; 
            margin: 0; 
            padding: 0;
            background-color: #fff;
        }
        
        .header { 
            text-align: center; 
            padding-bottom: 20px; 
            margin-bottom: 30px;
            border-bottom: 2px solid #1A202C;
        }
        .header h1 { 
            margin: 0; 
            font-size: 24px; 
            color: #1A202C; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p { 
            margin: 5px 0 0; 
            font-size: 13px; 
            color: #4A5568;
            font-weight: bold;
        }
        
        .report-title { 
            text-align: center; 
            background: #F7FAFC; 
            padding: 10px; 
            margin-bottom: 25px; 
            border: 1px solid #E2E8F0; 
            border-radius: 6px; 
        }
        .report-title h2 { 
            margin: 0; 
            font-size: 14px; 
            text-transform: uppercase; 
            color: #2D3748; 
            font-weight: 800; 
        }
        
        .attachment-container {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        .attachment-header {
            margin-bottom: 15px;
            padding: 10px 15px;
            background-color: #EDF2F7;
            border-radius: 6px;
            border-left: 5px solid #4A5568;
        }
        .attachment-name {
            font-weight: bold;
            color: #2D3748;
            font-size: 12px;
        }
        .attachment-details {
            font-size: 10px;
            color: #718096;
            margin-top: 3px;
        }
        
        .image-box {
            text-align: center;
            border: 1px solid #E2E8F0;
            padding: 10px;
            border-radius: 8px;
            background-color: #fff;
            min-height: 200px;
        }
        .attachment-img {
            max-width: 100%;
            height: auto;
            max-height: 22cm;
            display: block;
            margin: 0 auto;
        }
        
        .watermark { 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%) rotate(-45deg); 
            font-size: 80px; 
            color: rgba(0,0,0,0.03); 
            z-index: -1; 
            white-space: nowrap; 
            pointer-events: none; 
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="watermark">SUPPORTING DOCUMENT</div>

    <div class="header">
        <h1>{{ config('enterprise-ui.workspace_name', 'Divit Hospital') }}</h1>
        <p>Medical Services & Occupational Health</p>
    </div>

    <div class="report-title">
        <h2>Supporting Document Attachments</h2>
    </div>
    
    @foreach($imageAttachments as $attachment)
        <div class="attachment-container">
            <div class="attachment-header">
                <div class="attachment-name">{{ $attachment['name'] }}</div>
                <div class="attachment-details">Date: {{ $attachment['date'] }} | Size: {{ $attachment['size'] }}</div>
            </div>
            <div class="image-box">
                <img src="{{ $attachment['src'] }}" class="attachment-img" alt="Attachment">
            </div>
        </div>
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
