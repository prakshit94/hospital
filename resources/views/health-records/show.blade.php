@extends('layouts.app')

@php
    $pageTitle = 'Health Record Details';
@endphp

@section('content')

{{-- ============================================================
     PREMIUM UI — Health Record Show Page
     All logic preserved. UI only refactored.
     ============================================================ --}}

<style>
    /* ── Typography ─────────────────────────────────────────── */
    .hr-page { font-family: 'DM Sans', sans-serif; }

    /* ── Hero ────────────────────────────────────────────────── */
    .hr-hero {
        background: white;
        border: 1px solid #F0F0F0;
        border-radius: 20px;
        padding: 28px 32px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }
    .hr-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 600;
        color: #64748B;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 5px 14px;
        text-decoration: none;
        transition: all .15s;
    }
    .hr-back-btn:hover { background: #F1F5F9; color: #334155; }
    .hr-status-chip {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        background: #EEF2FF;
        color: #4F46E5;
        border: 1px solid #C7D2FE;
        border-radius: 99px;
        padding: 4px 13px;
    }
    .hr-hero-name {
        font-size: 26px;
        font-weight: 700;
        color: #0F172A;
        letter-spacing: -.4px;
        margin: 10px 0 6px;
        line-height: 1.2;
    }
    .hr-hero-meta {
        font-size: 13px;
        color: #94A3B8;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .hr-meta-sep { width: 3px; height: 3px; border-radius: 50%; background: #CBD5E1; }
    .hr-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 11px;
        padding: 9px 18px;
        cursor: pointer;
        text-decoration: none;
        transition: all .15s;
        border: 1px solid #E2E8F0;
        background: white;
        color: #334155;
    }
    .hr-btn:hover { background: #F8FAFC; }
    .hr-btn-primary {
        background: #4F46E5;
        border-color: #4F46E5;
        color: white;
    }
    .hr-btn-primary:hover { background: #4338CA; }

    /* ── Vitals Ribbon ───────────────────────────────────────── */
    .hr-vitals {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
    }
    .hr-vital-card {
        background: white;
        border: 1px solid #F0F0F0;
        border-radius: 16px;
        padding: 18px 14px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        transition: box-shadow .15s;
    }
    .hr-vital-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }
    .hr-vital-lbl {
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #94A3B8;
    }
    .hr-vital-val {
        font-size: 22px;
        font-weight: 700;
        color: #0F172A;
        line-height: 1.1;
    }
    .hr-vital-unit { font-size: 10px; color: #94A3B8; font-weight: 500; }
    .hr-vital-fit  { color: #059669; }
    .hr-vital-unfit { color: #DC2626; }

    /* ── Layout ──────────────────────────────────────────────── */
    .hr-main { display: grid; grid-template-columns: 1fr 310px; gap: 20px; align-items: start; }
    .hr-left  { display: flex; flex-direction: column; gap: 20px; }
    .hr-right { display: flex; flex-direction: column; gap: 20px; }

    /* ── Card ────────────────────────────────────────────────── */
    .hr-card {
        background: white;
        border: 1px solid #F0F0F0;
        border-radius: 20px;
        padding: 22px 26px;
    }
    .hr-card-indigo { background: #F5F3FF; border-color: #DDD6FE; }
    .hr-card-ghost  { background: #FAFAFA; border: 1px dashed #E2E8F0; }
    .hr-card-navy   { background: #1E1B4B; border-color: #312E81; }

    /* ── Section Header ──────────────────────────────────────── */
    .hr-sec-kicker {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #94A3B8;
        margin-bottom: 2px;
    }
    .hr-sec-title {
        font-size: 15px;
        font-weight: 700;
        color: #0F172A;
    }
    .hr-sec-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 18px;
        gap: 10px;
    }
    .hr-sec-link {
        font-size: 11.5px;
        font-weight: 600;
        color: #4F46E5;
        text-decoration: none;
        white-space: nowrap;
    }
    .hr-sec-link:hover { text-decoration: underline; }

    /* ── Comparison Block ────────────────────────────────────── */
    .hr-comp-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .hr-comp-item {
        background: white;
        border: 1px solid #EDE9FE;
        border-radius: 14px;
        padding: 14px 12px;
    }
    .hr-comp-lbl {
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94A3B8;
        text-align: center;
        margin-bottom: 10px;
    }
    .hr-comp-row  { display: flex; align-items: center; justify-content: space-between; gap: 6px; }
    .hr-comp-side { text-align: center; flex: 1; }
    .hr-comp-micro { font-size: 9px; font-weight: 700; text-transform: uppercase; color: #CBD5E1; margin-bottom: 3px; }
    .hr-comp-micro-curr { color: #4F46E5; }
    .hr-comp-prev { font-size: 13px; color: #94A3B8; font-weight: 500; }
    .hr-comp-curr { font-size: 16px; font-weight: 700; color: #4F46E5; }

    /* ── Timeline ────────────────────────────────────────────── */
    .hr-tl-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .hr-tl-current {
        background: #4F46E5;
        border-radius: 14px;
        padding: 14px;
        color: white;
    }
    .hr-tl-micro  { font-size: 9px; text-transform: uppercase; letter-spacing: .08em; opacity: .6; margin-bottom: 4px; }
    .hr-tl-date   { font-size: 13px; font-weight: 700; }
    .hr-tl-badge  {
        font-size: 10px;
        background: rgba(255,255,255,.15);
        display: inline-block;
        padding: 2px 9px;
        border-radius: 7px;
        margin-top: 8px;
    }
    .hr-tl-past {
        background: #FAFAFA;
        border: 1px solid #F0F0F0;
        border-radius: 14px;
        padding: 14px;
        text-decoration: none;
        display: block;
        transition: all .15s;
    }
    .hr-tl-past:hover { background: white; border-color: #C7D2FE; box-shadow: 0 2px 12px rgba(79,70,229,.08); }
    .hr-tl-date-past { font-size: 9px; text-transform: uppercase; letter-spacing: .08em; color: #94A3B8; margin-bottom: 4px; }
    .hr-tl-label { font-size: 13px; font-weight: 600; color: #0F172A; }
    .hr-tl-status { display: flex; align-items: center; gap: 6px; margin-top: 8px; }
    .hr-tl-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .hr-tl-dot-fit { background: #10B981; }
    .hr-tl-dot-unfit { background: #EF4444; }
    .hr-tl-status-txt { font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #94A3B8; }

    /* ── Detail Grid ─────────────────────────────────────────── */
    .hr-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1px;
        background: #F1F5F9;
        border-radius: 12px;
        overflow: hidden;
    }
    .hr-detail-tile { background: white; padding: 11px 14px; }
    .hr-detail-tile.span2 { grid-column: span 2; }
    .hr-detail-lbl {
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94A3B8;
        margin-bottom: 4px;
    }
    .hr-detail-val { font-size: 13px; color: #1E293B; font-weight: 500; line-height: 1.4; }

    /* ── Vision ──────────────────────────────────────────────── */
    .hr-vision-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px; }
    .hr-vision-box {
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
        border-radius: 12px;
        padding: 14px;
    }
    .hr-vision-eye {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94A3B8;
        margin-bottom: 8px;
    }
    .hr-vision-row { display: flex; justify-content: space-between; }
    .hr-vision-val { font-size: 12.5px; font-weight: 600; color: #1E293B; }

    /* ── Tags ────────────────────────────────────────────────── */
    .hr-tags { display: flex; flex-wrap: wrap; gap: 8px; }
    .hr-tag {
        font-size: 11.5px;
        font-weight: 600;
        padding: 5px 13px;
        border-radius: 99px;
    }
    .hr-tag-clear { background: #F8FAFC; border: 1px solid #E2E8F0; color: #64748B; }
    .hr-tag-warn  { background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C; }

    /* ── Lab Grid ────────────────────────────────────────────── */
    .hr-lab-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
    .hr-lab-item {
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
        border-radius: 12px;
        padding: 11px 13px;
        transition: all .15s;
    }
    .hr-lab-item:hover { background: white; border-color: #E2E8F0; }
    .hr-lab-lbl {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .09em;
        color: #94A3B8;
        margin-bottom: 5px;
    }
    .hr-lab-val { font-size: 13.5px; font-weight: 700; color: #0F172A; }

    /* ── Remarks ─────────────────────────────────────────────── */
    .hr-remarks-box {
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
        border-left: 3px solid #C7D2FE;
        border-radius: 0 12px 12px 0;
        padding: 14px 16px;
        font-size: 13px;
        color: #475569;
        font-style: italic;
        line-height: 1.7;
        margin-top: 8px;
    }
    .hr-restriction-val {
        font-size: 13px;
        color: #DC2626;
        font-weight: 600;
        margin-top: 4px;
        padding: 8px 12px;
        background: #FEF2F2;
        border-radius: 10px;
        border: 1px solid #FECACA;
    }

    /* ── Documents ───────────────────────────────────────────── */
    .hr-doc-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 11px 0;
        border-top: 1px solid #F8FAFC;
    }
    .hr-doc-item:first-child { border-top: none; padding-top: 0; }
    .hr-doc-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .hr-doc-name { font-size: 13px; font-weight: 600; color: #1E293B; }
    .hr-doc-meta { font-size: 10px; color: #94A3B8; margin-top: 2px; font-weight: 500; }
    .hr-doc-view-btn {
        font-size: 11.5px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 9px;
        border: 1px solid #E2E8F0;
        background: white;
        color: #334155;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: all .15s;
        flex-shrink: 0;
    }
    .hr-doc-view-btn:hover { background: #4F46E5; color: white; border-color: #4F46E5; }
    .hr-doc-del-btn {
        color: #DC2626 !important;
        border-color: #FECACA !important;
    }
    .hr-doc-del-btn:hover {
        background: #DC2626 !important;
        color: white !important;
        border-color: #DC2626 !important;
    }
    .hr-doc-empty { text-align: center; padding: 30px 0; }
    .hr-doc-empty-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
    }

    /* ── Admin Sidebar ───────────────────────────────────────── */
    .hr-admin-rows {
        display: flex;
        flex-direction: column;
        gap: 1px;
        background: #F1F5F9;
        border-radius: 12px;
        overflow: hidden;
    }
    .hr-admin-item {
        background: white;
        padding: 11px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }
    .hr-admin-lbl { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94A3B8; }
    .hr-admin-val { font-size: 12.5px; font-weight: 600; color: #1E293B; text-align: right; }
    .hr-badge-success {
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
        background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0;
        border-radius: 99px; padding: 3px 11px;
    }
    .hr-badge-danger {
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
        background: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA;
        border-radius: 99px; padding: 3px 11px;
    }
    .hr-badge-docs {
        font-size: 11.5px; font-weight: 600;
        background: #ECFDF5; color: #065F46;
        border-radius: 99px; padding: 3px 11px;
    }

    /* ── Activity Log ────────────────────────────────────────── */
    .hr-activity-list { display: flex; flex-direction: column; gap: 14px; }
    .hr-activity-item { display: flex; gap: 12px; }
    .hr-activity-dot-col { display: flex; flex-direction: column; align-items: center; padding-top: 3px; }
    .hr-activity-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #4F46E5;
        box-shadow: 0 0 0 3px #EEF2FF;
        flex-shrink: 0;
    }
    .hr-activity-line { width: 1px; flex: 1; background: #F1F5F9; margin-top: 6px; }
    .hr-activity-txt { font-size: 12.5px; font-weight: 600; color: #1E293B; line-height: 1.4; }
    .hr-activity-time { font-size: 10px; color: #94A3B8; margin-top: 3px; }

    /* ── Forms & Quick ───────────────────────────────────────── */
    .hr-form-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 14px;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        background: white;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        color: #334155;
        transition: all .15s;
    }
    .hr-form-btn:hover { border-color: #C7D2FE; background: #F5F3FF; color: #4F46E5; }
    .hr-form-btn-primary {
        background: #4F46E5;
        border-color: #4F46E5;
        color: white;
    }
    .hr-form-btn-primary:hover { background: #4338CA; color: white; }
    .hr-form-divider { height: 1px; background: #F1F5F9; margin: 4px 0; }
    .hr-quick-lbl {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .1em; color: rgba(255,255,255,.4); margin-bottom: 14px;
    }
    .hr-quick-btn {
        display: flex; align-items: center; justify-content: space-between;
        padding: 11px 16px; border-radius: 12px; background: white;
        color: #4F46E5; font-size: 13px; font-weight: 700;
        cursor: pointer; text-decoration: none; border: none; transition: background .15s;
    }
    .hr-quick-btn:hover { background: #EEF2FF; }
    .hr-quick-sub {
        font-size: 11px; color: rgba(255,255,255,.35);
        margin-top: 10px; text-align: center; line-height: 1.5;
    }

    /* ── Utility ─────────────────────────────────────────────── */
    .hr-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .hr-stack { display: flex; flex-direction: column; gap: 20px; }
</style>

<div class="page-stack hr-page">

    {{-- ── Hero ── --}}
    <section class="hr-hero">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                <x-ui.button variant="secondary" size="sm" href="{{ route('health-records.index') }}" class="hr-back-btn gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Back to Directory
                </x-ui.button>
                <span class="hr-status-chip">{{ $record->status }} Record</span>
            </div>
            <h1 class="hr-hero-name">{{ $record->full_name }}</h1>
            <p class="hr-hero-meta">
                <span>ID: {{ $record->employee->employee_id ?? 'N/A' }}</span>
                <span class="hr-meta-sep"></span>
                <span>{{ $record->company_name }}</span>
                <span class="hr-meta-sep"></span>
                <span>Exam: {{ $record->examination_date ? $record->examination_date->format('d/m/Y') : 'N/A' }}</span>
            </p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;padding-top:4px">
            <a href="{{ route('health-records.print', $record->uuid) }}" target="_blank" class="hr-btn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                </svg>
                Print Report
            </a>
            <a href="{{ route('health-records.edit', $record->uuid) }}" class="hr-btn hr-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/>
                </svg>
                Edit Data
            </a>
        </div>
    </section>

    {{-- ── Vitals Ribbon ── --}}
    <div class="hr-vitals">
        @php
            $vitals = [
                ['label' => 'Health Status', 'value' => $record->health_status ?? 'Unknown', 'color' => strtolower($record->health_status) === 'fit' ? 'hr-vital-fit' : 'hr-vital-unfit'],
                ['label' => 'Blood Pressure', 'value' => ($record->bp_systolic ?? '--') . '/' . ($record->bp_diastolic ?? '--'), 'sub' => 'mmHg'],
                ['label' => 'Pulse Rate',     'value' => $record->heart_rate ?? '--', 'sub' => 'bpm'],
                ['label' => 'SpO2 Level',     'value' => $record->spo2 ?? '--',       'sub' => '%'],
                ['label' => 'BMI Result',     'value' => $record->bmi ?? '--',        'sub' => 'kg/m²'],
            ];
        @endphp
        @foreach($vitals as $vital)
            <div class="hr-vital-card">
                <p class="hr-vital-lbl">{{ $vital['label'] }}</p>
                <div class="hr-vital-val {{ $vital['color'] ?? '' }}">
                    {{ $vital['value'] }}
                    @if(isset($vital['sub']))
                        <span class="hr-vital-unit">{{ $vital['sub'] }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Main Grid ── --}}
    <section class="hr-main">

        {{-- Left Column --}}
        <div class="hr-left">

            {{-- 1. Longitudinal Comparison --}}
            @if($previousRecord)
            <div class="hr-card hr-card-indigo">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;gap:12px;flex-wrap:wrap">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:40px;height:40px;border-radius:12px;background:#EDE9FE;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div>
                            <div class="hr-sec-title">Longitudinal Health Comparison</div>
                            <div class="hr-sec-kicker" style="margin-bottom:0">Current vs. previous — {{ $previousRecord->examination_date->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    <a href="#full-history" class="hr-sec-link">View all {{ $history->count() + 1 }} records</a>
                </div>
                <div class="hr-comp-grid">
                    @php
                        $comparisonVitals = [
                            ['label' => 'Weight',      'current' => $record->weight,      'prev' => $previousRecord->weight,      'unit' => 'kg'],
                            ['label' => 'BMI',         'current' => $record->bmi,         'prev' => $previousRecord->bmi,         'unit' => ''],
                            ['label' => 'BP (Sys/Dia)','current' => ($record->bp_systolic ?? '--') . '/' . ($record->bp_diastolic ?? '--'), 'prev' => ($previousRecord->bp_systolic ?? '--') . '/' . ($previousRecord->bp_diastolic ?? '--'), 'unit' => ''],
                            ['label' => 'Heart Rate',  'current' => $record->heart_rate,  'prev' => $previousRecord->heart_rate,  'unit' => 'bpm'],
                        ];
                    @endphp
                    @foreach($comparisonVitals as $vital)
                    <div class="hr-comp-item">
                        <p class="hr-comp-lbl">{{ $vital['label'] }}</p>
                        <div class="hr-comp-row">
                            <div class="hr-comp-side">
                                <p class="hr-comp-micro">Prev</p>
                                <p class="hr-comp-prev">{{ $vital['prev'] ?? '--' }}</p>
                            </div>
                            <div style="width:22px;display:flex;align-items:center;justify-content:center">
                                @php
                                    $diff = 0;
                                    if (is_numeric($vital['current']) && is_numeric($vital['prev'])) {
                                        $diff = $vital['current'] - $vital['prev'];
                                    }
                                @endphp
                                @if($diff > 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="3"><path d="m18 15-6-6-6 6"/></svg>
                                @elseif($diff < 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="3"><path d="m6 9 6 6 6-6"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="#CBD5E1" stroke-width="3"><path d="M5 12h14"/></svg>
                                @endif
                            </div>
                            <div class="hr-comp-side">
                                <p class="hr-comp-micro hr-comp-micro-curr">Now</p>
                                <p class="hr-comp-curr">{{ $vital['current'] ?? '--' }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 2. Checkup History Timeline --}}
            <div class="hr-card" id="full-history">
                <div class="hr-sec-header">
                    <div>
                        <div class="hr-sec-kicker">Longitudinal Timeline</div>
                        <div class="hr-sec-title">Checkup History</div>
                    </div>
                </div>
                <div class="hr-tl-grid">
                    <div class="hr-tl-current">
                        <p class="hr-tl-micro">Current Exam</p>
                        <p class="hr-tl-date">{{ $record->examination_date->format('d M, Y') }}</p>
                        <span class="hr-tl-badge">ID: {{ $record->id }}</span>
                    </div>
                    @foreach($history->take(3) as $past)
                    <a href="{{ route('health-records.show', $past->uuid) }}" class="hr-tl-past">
                        <p class="hr-tl-date-past">{{ $past->examination_date->format('d M, Y') }}</p>
                        <p class="hr-tl-label">Medical Checkup</p>
                        <div class="hr-tl-status">
                            <span class="hr-tl-dot {{ strtolower($past->health_status) === 'fit' ? 'hr-tl-dot-fit' : 'hr-tl-dot-unfit' }}"></span>
                            <span class="hr-tl-status-txt">{{ $past->health_status }}</span>
                        </div>
                    </a>
                    @endforeach
                    @if($history->count() > 3)
                    <div style="border:1px dashed #E2E8F0;border-radius:14px;display:flex;align-items:center;justify-content:center;padding:14px">
                        <p style="font-size:11px;font-weight:700;color:#94A3B8;text-align:center">+{{ $history->count() - 3 }} more records</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Employee Info & Physical Exam --}}
            <div class="hr-two-col">
                <div class="hr-card">
                    <div class="hr-sec-header">
                        <div>
                            <div class="hr-sec-kicker">Section 02</div>
                            <div class="hr-sec-title">Employee Information</div>
                        </div>
                    </div>
                    <div class="hr-detail-grid">
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Father's Name</div>
                            <div class="hr-detail-val">{{ $record->father_name ?? 'N/A' }}</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">DOB (Age)</div>
                            <div class="hr-detail-val">{{ $record->dob ? $record->dob->format('d/m/Y') : 'N/A' }} ({{ $record->dob ? (int)$record->dob->diffInYears(now()) : 'N/A' }}y)</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Department</div>
                            <div class="hr-detail-val">{{ $record->department ?? 'N/A' }}</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Joining Date</div>
                            <div class="hr-detail-val">{{ $record->joining_date ? $record->joining_date->format('d/m/Y') : 'N/A' }}</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Marital Status</div>
                            <div class="hr-detail-val">{{ $record->marital_status ?? 'N/A' }}</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Husband's Name</div>
                            <div class="hr-detail-val">{{ $record->husband_name ?? 'N/A' }}</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Identification Mark</div>
                            <div class="hr-detail-val">{{ $record->identification_mark ?? 'None' }}</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">H/O Habits</div>
                            <div class="hr-detail-val">{{ $record->habits ?? 'None' }}</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Dependents</div>
                            <div class="hr-detail-val">{{ $record->dependent ?? 'None' }}</div>
                        </div>
                        <div class="hr-detail-tile span2">
                            <div class="hr-detail-lbl">Permanent Address</div>
                            <div class="hr-detail-val">{{ $record->address ?? 'No address provided.' }}</div>
                        </div>
                        <div class="hr-detail-tile span2">
                            <div class="hr-detail-lbl">Prev. Occupational History</div>
                            <div class="hr-detail-val">{{ $record->prev_occ_history ?? 'None reported.' }}</div>
                        </div>
                    </div>
                </div>

                <div class="hr-card">
                    <div class="hr-sec-header">
                        <div>
                            <div class="hr-sec-kicker">Section 03</div>
                            <div class="hr-sec-title">Physical Examination</div>
                        </div>
                    </div>
                    <div class="hr-detail-grid">
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Temperature</div>
                            <div class="hr-detail-val">{{ $record->temperature ?? '--' }} °F</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Height / Weight</div>
                            <div class="hr-detail-val">{{ $record->height ?? '--' }}cm / {{ $record->weight ?? '--' }}kg</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Chest (N/E)</div>
                            <div class="hr-detail-val">{{ $record->chest_before ?? '--' }} / {{ $record->chest_after ?? '--' }}</div>
                        </div>
                        <div class="hr-detail-tile">
                            <div class="hr-detail-lbl">Respiration</div>
                            <div class="hr-detail-val">{{ $record->respiration_rate ?? '--' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Vision --}}
            <div class="hr-card">
                <div class="hr-sec-header">
                    <div>
                        <div class="hr-sec-kicker">Section 04</div>
                        <div class="hr-sec-title">Vision Examination</div>
                    </div>
                </div>
                <div class="hr-vision-grid">
                    <div class="hr-vision-box">
                        <p class="hr-vision-eye">Right Eye</p>
                        <div class="hr-vision-row">
                            <span class="hr-vision-val">Distant: {{ $record->distant_vision_right ?? '--' }}</span>
                            <span class="hr-vision-val">Near: {{ $record->near_vision_right ?? '--' }}</span>
                        </div>
                    </div>
                    <div class="hr-vision-box">
                        <p class="hr-vision-eye">Left Eye</p>
                        <div class="hr-vision-row">
                            <span class="hr-vision-val">Distant: {{ $record->distant_vision_left ?? '--' }}</span>
                            <span class="hr-vision-val">Near: {{ $record->near_vision_left ?? '--' }}</span>
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid #F8FAFC;padding-top:14px">
                    <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94A3B8">Colour Vision</span>
                    <span style="font-size:13.5px;font-weight:700;color:#1E293B">{{ $record->colour_vision ?? 'N/A' }}</span>
                </div>
            </div>

            {{-- 6. Medical History --}}
            <div class="hr-card">
                <div class="hr-sec-header">
                    <div>
                        <div class="hr-sec-kicker">Section 06</div>
                        <div class="hr-sec-title">Medical History Screening</div>
                    </div>
                </div>
                <div class="hr-tags">
                    @foreach([
                        'Hypertension' => $record->hypertension,
                        'Diabetes'     => $record->diabetes,
                        'Dyslipidemia' => $record->dyslipidemia,
                        'Tuberculosis' => $record->tuberculosis,
                        'Epilepsy'     => $record->epilepsy,
                        'Asthma'       => $record->asthma,
                        'Heart Disease'=> $record->heart_disease,
                    ] as $label => $val)
                        @if(strtolower($val) !== 'no' && $val)
                            <span class="hr-tag hr-tag-warn">{{ $label }}: {{ $val }}</span>
                        @else
                            <span class="hr-tag hr-tag-clear">{{ $label }}: No</span>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- 11 & 12. Lab Investigations --}}
            <div class="hr-card">
                <div class="hr-sec-header">
                    <div>
                        <div class="hr-sec-kicker">Section 11 &amp; 12</div>
                        <div class="hr-sec-title">Clinical Investigations &amp; Laboratory</div>
                    </div>
                </div>
                <div class="hr-lab-grid">
                    @foreach([
                        'HB'           => $record->hb,
                        'RBC'          => $record->rbc,
                        'WBC TC'       => $record->wbc_tc,
                        'Platelet'     => $record->platelet,
                        'FBS'          => $record->fbs,
                        'SGPT'         => $record->sgpt,
                        'S. Creatinine'=> $record->s_creatinine,
                        'Urine Albumin'=> $record->urine_albumin,
                    ] as $lab => $val)
                        <div class="hr-lab-item">
                            <p class="hr-lab-lbl">{{ $lab }}</p>
                            <p class="hr-lab-val">{{ $val ?? '--' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 15. Job Restriction & Advice --}}
            <div class="hr-card">
                <div class="hr-sec-header">
                    <div>
                        <div class="hr-sec-kicker">Section 15</div>
                        <div class="hr-sec-title">Job Restriction &amp; Clinical Advice</div>
                    </div>
                </div>
                <div style="margin-bottom:18px">
                    <div class="hr-detail-lbl">Restrictions</div>
                    <div class="hr-restriction-val">{{ $record->job_restriction ?? 'No functional restrictions identified.' }}</div>
                </div>
                <div>
                    <div class="hr-detail-lbl">Doctor's Remarks</div>
                    <div class="hr-remarks-box">{{ $record->doctor_remarks ?? 'No additional remarks.' }}</div>
                </div>
            </div>

            {{-- 17. Documents --}}
            <div class="hr-card">
                <div class="hr-sec-header">
                    <div>
                        <div class="hr-sec-kicker">Section 17</div>
                        <div class="hr-sec-title">Documents</div>
                    </div>
                    <a href="{{ route('health-records.edit', $record->uuid) }}#document_upload" class="hr-sec-link">
                        Upload More
                    </a>
                </div>

                @if($record->documents->isEmpty())
                    <div class="hr-doc-empty">
                        <div class="hr-doc-empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#CBD5E1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </div>
                        <p style="font-size:13px;color:#94A3B8;margin-bottom:8px">No documents uploaded yet.</p>
                        <a href="{{ route('health-records.edit', $record->uuid) }}#document_upload" class="hr-sec-link">
                            Upload first document →
                        </a>
                    </div>
                @else
                    @foreach($record->documents as $doc)
                        @php
                            $ext     = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
                            $isImg   = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                            $isPdf   = $ext === 'pdf';
                            $iconColor = $isPdf ? '#EF4444' : ($isImg ? '#3B82F6' : '#14B8A6');
                        @endphp
                        <div class="hr-doc-item">
                            <div style="display:flex;align-items:center;gap:12px;min-width:0">
                                <div class="hr-doc-icon" style="color:{{ $iconColor }}">
                                    @if($isPdf)
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    @elseif($isImg)
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    @endif
                                </div>
                                <div style="min-width:0">
                                    <p class="hr-doc-name">{{ $doc->original_name }}</p>
                                    <p class="hr-doc-meta">{{ strtoupper($ext) }} &bull; {{ $doc->formatted_size }} &bull; {{ $doc->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div style="display:flex;gap:6px">
                                <a href="{{ Storage::url($doc->path) }}" target="_blank" class="hr-doc-view-btn">View</a>
                                <form action="{{ route('health-records.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hr-doc-view-btn hr-doc-del-btn">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>{{-- /hr-left --}}

        {{-- Right Column --}}
        <div class="hr-right">

            {{-- Administrative --}}
            <div class="hr-card">
                <div class="hr-sec-header">
                    <div>
                        <div class="hr-sec-kicker">Meta Data</div>
                        <div class="hr-sec-title">Administrative</div>
                    </div>
                </div>
                <div class="hr-admin-rows">
                    <div class="hr-admin-item">
                        <span class="hr-admin-lbl">Record Status</span>
                        <span class="{{ $record->status === 'active' ? 'hr-badge-success' : 'hr-badge-danger' }}">{{ $record->status }}</span>
                    </div>
                    <div class="hr-admin-item">
                        <span class="hr-admin-lbl">Mobile</span>
                        <span class="hr-admin-val">{{ $record->mobile ?? 'N/A' }}</span>
                    </div>
                    <div class="hr-admin-item">
                        <span class="hr-admin-lbl">Email</span>
                        <span class="hr-admin-val" style="font-size:11.5px;max-width:160px;overflow:hidden;text-overflow:ellipsis">{{ $record->email ?? 'N/A' }}</span>
                    </div>
                    <div class="hr-admin-item">
                        <span class="hr-admin-lbl">Examined By</span>
                        <div style="text-align:right">
                            <div style="font-size:13px;font-weight:700;color:#1E293B">Dr. {{ $record->doctor_name }}</div>
                            <div style="font-size:10px;color:#94A3B8;margin-top:2px">{{ $record->doctor_qualification }}</div>
                        </div>
                    </div>
                    <div class="hr-admin-item">
                        <span class="hr-admin-lbl">Documents</span>
                        @if($record->documents->isEmpty())
                            <span style="font-size:12px;color:#94A3B8">None uploaded</span>
                        @else
                            <span class="hr-badge-docs">{{ $record->documents->count() }} {{ Str::plural('file', $record->documents->count()) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Activity Log --}}
            <div class="hr-card">
                <div class="hr-sec-header">
                    <div>
                        <div class="hr-sec-kicker">Audit Trail</div>
                        <div class="hr-sec-title">Recent Activity</div>
                    </div>
                </div>
                <div class="hr-activity-list">
                    @forelse($activities as $activity)
                        <div class="hr-activity-item">
                            <div class="hr-activity-dot-col">
                                <div class="hr-activity-dot"></div>
                                @if(!$loop->last)<div class="hr-activity-line"></div>@endif
                            </div>
                            <div>
                                <p class="hr-activity-txt">{{ $activity->description }}</p>
                                <p class="hr-activity-time">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:24px 0">
                            <p style="font-size:13px;color:#94A3B8;font-style:italic">No events recorded.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Official Forms --}}
            <div class="hr-card hr-card-ghost">
                <div class="hr-sec-kicker" style="margin-bottom:14px">Official Forms</div>
                <div style="display:flex;flex-direction:column;gap:8px">
                    <a href="{{ route('health-records.print-form32', $record->uuid) }}" target="_blank" class="hr-form-btn">
                        Form 32 (Health Register)
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;opacity:.4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 7h10v10M7 17 17 7"/></svg>
                    </a>
                    <a href="{{ route('health-records.print-form33', $record->uuid) }}" target="_blank" class="hr-form-btn">
                        Form 33 (Fitness Cert)
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;opacity:.4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 7h10v10M7 17 17 7"/></svg>
                    </a>
                    <div class="hr-form-divider"></div>
                    <a href="{{ route('health-records.print-all', $record->uuid) }}" target="_blank" class="hr-form-btn hr-form-btn-primary">
                        Print Complete Report
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    </a>
                </div>
            </div>

            {{-- Fast Workflow --}}
            <div class="hr-card hr-card-navy">
                <div class="hr-quick-lbl">Fast Workflow</div>
                <a href="{{ route('health-records.create') }}?prefill={{ $record->employee?->uuid }}" class="hr-quick-btn">
                    New Examination
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" viewBox="0 0 24 24" fill="none" stroke="#4F46E5" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                </a>
                <p class="hr-quick-sub">Start a fresh medical record for this employee.</p>
            </div>

        </div>{{-- /hr-right --}}

    </section>

</div>
@endsection