@extends('layouts.app')

@php
    $pageTitle = 'Health Record Details';
@endphp

@section('content')

<div class="page-stack">

    {{-- ═══════════════════════ HERO PANEL ═══════════════════════ --}}
    <section class="hero-panel">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <x-ui.button variant="secondary" size="sm" href="{{ route('health-records.index') }}" class="gap-2 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                        Back to Directory
                    </x-ui.button>
                    <span class="{{ strtolower($record->status) === 'active' ? 'ui-status-success' : 'ui-status-danger' }}">
                        {{ $record->status }} Record
                    </span>
                </div>
                <div>
                    <h1 class="hero-title">{{ $record->full_name }}</h1>
                    <p class="hero-copy mt-2 flex flex-wrap items-center gap-2">
                        <span class="font-bold text-foreground">ID: {{ $record->employee->employee_id ?? 'N/A' }}</span>
                        <span class="size-1.5 rounded-full bg-border"></span>
                        <span>{{ $record->company_name }}</span>
                        <span class="size-1.5 rounded-full bg-border"></span>
                        <span>Exam: {{ $record->examination_date ? $record->examination_date->format('d/m/Y') : 'N/A' }}</span>
                    </p>
                </div>
            </div>
            
            <div class="flex shrink-0 flex-wrap gap-3">
                <x-ui.button variant="secondary" href="{{ route('health-records.print', $record->uuid) }}" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    Print Report
                </x-ui.button>
                <x-ui.button href="{{ route('health-records.edit', $record->uuid) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    Edit Data
                </x-ui.button>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════ VITALS RIBBON ═══════════════════════ --}}
    <div class="grid grid-cols-2 gap-3 md:grid-cols-5 md:gap-4">
        @php
            $vitals = [
                ['label' => 'Health Status', 'value' => $record->health_status ?? 'Unknown', 'color' => strtolower($record->health_status) === 'fit' ? 'text-emerald-600' : 'text-destructive'],
                ['label' => 'Blood Pressure', 'value' => ($record->bp_systolic ?? '--') . '/' . ($record->bp_diastolic ?? '--'), 'sub' => 'mmHg'],
                ['label' => 'Pulse Rate',     'value' => $record->heart_rate ?? '--', 'sub' => 'bpm'],
                ['label' => 'SpO2 Level',     'value' => $record->spo2 ?? '--',       'sub' => '%'],
                ['label' => 'BMI Result',     'value' => $record->bmi ?? '--',        'sub' => 'kg/m²'],
            ];
        @endphp
        @foreach($vitals as $vital)
            <div class="flex flex-col items-center justify-center gap-1 rounded-2xl border border-border/70 bg-card p-4 text-center shadow-sm transition hover:shadow-md">
                <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">{{ $vital['label'] }}</p>
                <div class="font-heading text-2xl font-black text-foreground {{ $vital['color'] ?? '' }}">
                    {{ $vital['value'] }}
                    @if(isset($vital['sub']))
                        <span class="text-[11px] font-semibold text-muted-foreground">{{ $vital['sub'] }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- ═══════════════════════ MAIN GRID ═══════════════════════ --}}
    <div class="grid gap-6 xl:grid-cols-[1fr_320px]">
        
        {{-- LEFT COLUMN --}}
        <div class="space-y-6">

            {{-- 1. Checkup History (Arrow Timeline) --}}
            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Chronological Journey</div>
                    <h3 class="section-title">Checkup History</h3>
                </div>
                <div class="flex gap-0 overflow-x-auto pb-1 no-scrollbar">
                    @php
                        // Merge current and past checkups, sort by date desc
                        $allExams = $history->push($record)->sortBy('examination_date');
                    @endphp
                    @foreach($allExams as $exam)
                        <a href="{{ route('health-records.show', $exam->uuid) }}" 
                           class="group relative flex shrink-0 items-center gap-2 border-y border-r border-border/70 bg-secondary px-5 py-2.5 text-xs font-bold text-muted-foreground transition first:rounded-l-xl first:border-l last:rounded-r-xl hover:bg-border/50 hover:text-foreground {{ $exam->uuid === $record->uuid ? '!bg-primary !border-primary !text-primary-foreground z-10' : '' }}">
                            <span class="size-2 shrink-0 rounded-full {{ strtolower($exam->health_status) === 'fit' ? 'bg-emerald-500' : 'bg-destructive' }} {{ $exam->uuid === $record->uuid ? '!bg-white shadow-[0_0_0_2px_rgba(255,255,255,0.3)]' : 'shadow-[0_0_0_2px_rgba(255,255,255,0.5)]' }}"></span>
                            {{ $exam->examination_date->format('d M, Y') }}
                        </a>
                    @endforeach
                </div>
            </x-ui.card>

            {{-- 2. Longitudinal Comparison --}}
            <x-ui.card class="border-indigo-100 bg-indigo-50/50 space-y-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="section-title text-indigo-950">Longitudinal Health Comparison</h3>
                            <div class="section-kicker !mb-0 text-indigo-600/70">
                                Current vs. previous — {{ $previousRecord ? $previousRecord->examination_date->format('d/m/Y') : 'NA' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @php
                        $comparisonVitals = [
                            ['label' => 'Weight',      'current' => $record->weight,      'prev' => $previousRecord?->weight ?? 'NA',      'unit' => 'kg'],
                            ['label' => 'BMI',         'current' => $record->bmi,         'prev' => $previousRecord?->bmi ?? 'NA',         'unit' => ''],
                            ['label' => 'BP (Sys/Dia)','current' => ($record->bp_systolic ?? '--') . '/' . ($record->bp_diastolic ?? '--'), 'prev' => $previousRecord ? ($previousRecord->bp_systolic ?? '--') . '/' . ($previousRecord->bp_diastolic ?? '--') : 'NA', 'unit' => ''],
                            ['label' => 'Heart Rate',  'current' => $record->heart_rate,  'prev' => $previousRecord?->heart_rate ?? 'NA',  'unit' => 'bpm'],
                        ];
                    @endphp
                    @foreach($comparisonVitals as $vital)
                        <div class="rounded-xl border border-indigo-100 bg-white p-3 shadow-sm">
                            <p class="mb-2 text-center text-[9px] font-black uppercase tracking-widest text-muted-foreground">{{ $vital['label'] }}</p>
                            <div class="flex items-center justify-between gap-1">
                                <div class="flex-1 text-center">
                                    <p class="mb-0.5 text-[9px] font-black uppercase tracking-widest text-border">Prev</p>
                                    <p class="text-xs font-semibold text-muted-foreground">{{ $vital['prev'] ?? 'NA' }}</p>
                                </div>
                                <div class="flex size-5 shrink-0 items-center justify-center">
                                    @php
                                        $diff = 0;
                                        if (is_numeric($vital['current']) && is_numeric($vital['prev'])) {
                                            $diff = $vital['current'] - $vital['prev'];
                                        }
                                    @endphp
                                    @if($diff > 0)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m18 15-6-6-6 6"/></svg>
                                    @elseif($diff < 0)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m6 9 6 6 6-6"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-border" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 text-center">
                                    <p class="mb-0.5 text-[9px] font-black uppercase tracking-widest text-primary">Now</p>
                                    <p class="text-sm font-bold text-primary">{{ $vital['current'] ?? '--' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            {{-- Employee Info & Physical Exam --}}
            <div class="grid gap-6 md:grid-cols-2">
                <x-ui.card class="space-y-4">
                    <div>
                        <div class="section-kicker">Section 02</div>
                        <h3 class="section-title">Employee Information</h3>
                    </div>
                    <div class="detail-grid">
                        <div class="detail-tile">
                            <div class="detail-label">Father's Name</div>
                            <div class="detail-value">{{ $record->father_name ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">DOB (Age)</div>
                            <div class="detail-value">{{ $record->dob ? $record->dob->format('d/m/Y') : 'N/A' }} ({{ $record->dob ? (int)$record->dob->diffInYears(now()) : 'N/A' }}y)</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Department</div>
                            <div class="detail-value">{{ $record->department ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Joining Date</div>
                            <div class="detail-value">{{ $record->joining_date ? $record->joining_date->format('d/m/Y') : 'N/A' }}</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Marital Status</div>
                            <div class="detail-value">{{ $record->marital_status ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Husband's Name</div>
                            <div class="detail-value">{{ $record->husband_name ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-tile col-span-full">
                            <div class="detail-label">Identification Mark</div>
                            <div class="detail-value">{{ $record->identification_mark ?? 'None' }}</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">H/O Habits</div>
                            <div class="detail-value">{{ $record->habits ?? 'None' }}</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Dependents</div>
                            <div class="detail-value">{{ $record->dependent ?? 'None' }}</div>
                        </div>
                        <div class="detail-tile col-span-full">
                            <div class="detail-label">Permanent Address</div>
                            <div class="detail-value">{{ $record->address ?? 'No address provided.' }}</div>
                        </div>
                        <div class="detail-tile col-span-full">
                            <div class="detail-label">Prev. Occupational History</div>
                            <div class="detail-value">{{ $record->prev_occ_history ?? 'None reported.' }}</div>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card class="space-y-4">
                    <div>
                        <div class="section-kicker">Section 03</div>
                        <h3 class="section-title">Physical Examination</h3>
                    </div>
                    <div class="detail-grid">
                        <div class="detail-tile">
                            <div class="detail-label">Temperature</div>
                            <div class="detail-value">{{ $record->temperature ?? '--' }} °F</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Height / Weight</div>
                            <div class="detail-value">{{ $record->height ?? '--' }}cm / {{ $record->weight ?? '--' }}kg</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Chest (N/E)</div>
                            <div class="detail-value">{{ $record->chest_before ?? '--' }} / {{ $record->chest_after ?? '--' }}</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Respiration</div>
                            <div class="detail-value">{{ $record->respiration_rate ?? '--' }}</div>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            {{-- 4. Vision --}}
            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Section 04</div>
                    <h3 class="section-title">Vision Examination</h3>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl border border-border/50 bg-secondary/30 p-4">
                        <p class="mb-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground">Right Eye</p>
                        
                        <div class="mt-2 mb-1 text-[10px] font-bold text-muted-foreground/70">With Specs</div>
                        <div class="flex justify-between">
                            <span class="text-sm font-semibold text-foreground">Distant: {{ $record->distant_vision_right ?? '--' }}</span>
                            <span class="text-sm font-semibold text-foreground">Near: {{ $record->near_vision_right ?? '--' }}</span>
                        </div>
                        
                        <div class="mt-3 mb-1 text-[10px] font-bold text-muted-foreground/70">Without Specs</div>
                        <div class="flex justify-between">
                            <span class="text-sm font-semibold text-foreground">Distant: {{ $record->distant_vision_right_without ?? '--' }}</span>
                            <span class="text-sm font-semibold text-foreground">Near: {{ $record->near_vision_right_without ?? '--' }}</span>
                        </div>
                    </div>
                    <div class="rounded-xl border border-border/50 bg-secondary/30 p-4">
                        <p class="mb-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground">Left Eye</p>
                        
                        <div class="mt-2 mb-1 text-[10px] font-bold text-muted-foreground/70">With Specs</div>
                        <div class="flex justify-between">
                            <span class="text-sm font-semibold text-foreground">Distant: {{ $record->distant_vision_left ?? '--' }}</span>
                            <span class="text-sm font-semibold text-foreground">Near: {{ $record->near_vision_left ?? '--' }}</span>
                        </div>
                        
                        <div class="mt-3 mb-1 text-[10px] font-bold text-muted-foreground/70">Without Specs</div>
                        <div class="flex justify-between">
                            <span class="text-sm font-semibold text-foreground">Distant: {{ $record->distant_vision_left_without ?? '--' }}</span>
                            <span class="text-sm font-semibold text-foreground">Near: {{ $record->near_vision_left_without ?? '--' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between border-t border-border/70 pt-4">
                    <span class="text-[11px] font-black uppercase tracking-widest text-muted-foreground">Colour Vision</span>
                    <span class="text-sm font-bold text-foreground">{{ $record->colour_vision ?? 'N/A' }}</span>
                </div>
            </x-ui.card>

            {{-- 6. Medical History --}}
            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Section 06</div>
                    <h3 class="section-title">Medical History Screening</h3>
                </div>
                <div class="flex flex-wrap gap-2">
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
                            <span class="inline-flex items-center rounded-full border border-destructive/20 bg-destructive/10 px-3 py-1 text-[11.5px] font-bold text-destructive">{{ $label }}: {{ $val }}</span>
                        @else
                            <span class="ui-chip">{{ $label }}: No</span>
                        @endif
                    @endforeach
                </div>
            </x-ui.card>

            {{-- 11 & 12. Lab Investigations --}}
            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Section 11 &amp; 12</div>
                    <h3 class="section-title">Clinical Investigations &amp; Laboratory</h3>
                </div>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
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
                        <div class="rounded-xl border border-border/50 bg-secondary/30 p-3 transition hover:border-border hover:bg-card">
                            <p class="mb-1 text-[9px] font-black uppercase tracking-widest text-muted-foreground">{{ $lab }}</p>
                            <p class="text-sm font-bold text-foreground">{{ $val ?? '--' }}</p>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            {{-- 15. Job Restriction & Advice --}}
            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Section 15</div>
                    <h3 class="section-title">Job Restriction &amp; Clinical Advice</h3>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Restrictions</div>
                    <div class="inline-block rounded-xl border border-destructive/20 bg-destructive/5 px-3 py-2 text-sm font-semibold text-destructive">
                        {{ $record->job_restriction ?? 'No functional restrictions identified.' }}
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Doctor's Remarks</div>
                    <div class="rounded-xl rounded-l-sm border border-border/70 border-l-[3px] border-l-primary/40 bg-secondary/30 p-4 text-sm italic text-muted-foreground">
                        {{ $record->doctor_remarks ?? 'No additional remarks.' }}
                    </div>
                </div>
            </x-ui.card>

            {{-- 17. Documents --}}
            <x-ui.card class="space-y-4">
                <div class="flex items-start justify-between gap-4 border-b border-border/70 pb-4">
                    <div>
                        <div class="section-kicker">Section 17</div>
                        <h3 class="section-title">Documents</h3>
                    </div>
                    <x-ui.button variant="ghost" size="sm" href="{{ route('health-records.edit', $record->uuid) }}#document_upload" class="shrink-0 text-primary">
                        Upload More
                    </x-ui.button>
                </div>

                @if($record->documents->isEmpty())
                    <div class="py-8 text-center">
                        <div class="mx-auto mb-3 flex size-12 items-center justify-center rounded-xl border border-border/70 bg-secondary text-muted-foreground/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <p class="mb-2 text-sm text-muted-foreground">No documents uploaded yet.</p>
                        <a href="{{ route('health-records.edit', $record->uuid) }}#document_upload" class="text-xs font-bold text-primary hover:underline">
                            Upload first document &rarr;
                        </a>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($record->documents as $doc)
                            @php
                                $ext     = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
                                $isImg   = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                $isPdf   = $ext === 'pdf';
                                $iconColor = $isPdf ? 'text-destructive bg-destructive/10' : ($isImg ? 'text-blue-600 bg-blue-100' : 'text-primary bg-primary/10');
                            @endphp
                            <div class="flex flex-col gap-3 rounded-xl border border-border/50 bg-secondary/20 p-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl {{ $iconColor }}">
                                        @if($isPdf)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                        @elseif($isImg)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-foreground">{{ $doc->original_name }}</p>
                                        <p class="mt-0.5 text-[10px] font-medium text-muted-foreground">{{ strtoupper($ext) }} &bull; {{ $doc->formatted_size }} &bull; {{ $doc->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex shrink-0 gap-2">
                                    <x-ui.button variant="secondary" size="sm" href="{{ Storage::url($doc->path) }}" target="_blank">View</x-ui.button>
                                    <form action="{{ route('health-records.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button variant="ghost" size="sm" type="submit" class="!text-destructive hover:!bg-destructive/10">Delete</x-ui.button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-ui.card>

        </div>{{-- /LEFT COLUMN --}}

        {{-- RIGHT COLUMN --}}
        <div class="space-y-6">

            {{-- Administrative --}}
            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Meta Data</div>
                    <h3 class="section-title">Administrative</h3>
                </div>
                <div class="detail-grid !grid-cols-1">
                    <div class="detail-tile flex items-center justify-between">
                        <div class="detail-label !mb-0">Record Status</div>
                        <div class="{{ $record->status === 'active' ? 'ui-status-success' : 'ui-status-danger' }}">{{ $record->status }}</div>
                    </div>
                    <div class="detail-tile flex items-center justify-between">
                        <div class="detail-label !mb-0">Mobile</div>
                        <div class="detail-value text-right">{{ $record->mobile ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-tile flex items-center justify-between gap-3">
                        <div class="detail-label !mb-0 shrink-0">Email</div>
                        <div class="detail-value truncate text-right text-xs">{{ $record->email ?? 'N/A' }}</div>
                    </div>
                    <div class="detail-tile flex items-center justify-between">
                        <div class="detail-label !mb-0">Examined By</div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-foreground">Dr. {{ $record->doctor_name }}</div>
                            <div class="mt-0.5 text-[10px] text-muted-foreground">{{ $record->doctor_qualification }}</div>
                        </div>
                    </div>
                    <div class="detail-tile flex items-center justify-between">
                        <div class="detail-label !mb-0">Documents</div>
                        @if($record->documents->isEmpty())
                            <div class="text-xs text-muted-foreground">None uploaded</div>
                        @else
                            <div class="ui-chip !bg-emerald-500/10 !text-emerald-600 !border-emerald-500/20">{{ $record->documents->count() }} {{ Str::plural('file', $record->documents->count()) }}</div>
                        @endif
                    </div>
                </div>
            </x-ui.card>

            {{-- Activity Log --}}
            <x-ui.card class="space-y-4">
                <div>
                    <div class="section-kicker">Audit Trail</div>
                    <h3 class="section-title">Recent Activity</h3>
                </div>
                <div class="space-y-4">
                    @forelse($activities as $activity)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center pt-1">
                                <div class="size-2 shrink-0 rounded-full bg-primary shadow-[0_0_0_3px_color-mix(in_oklab,var(--primary)_15%,transparent)]"></div>
                                @if(!$loop->last)<div class="mt-1.5 w-px flex-1 bg-border/70"></div>@endif
                            </div>
                            <div class="pb-1">
                                <p class="text-sm font-semibold text-foreground leading-snug">{{ $activity->description }}</p>
                                <p class="mt-1 text-[10px] text-muted-foreground">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center">
                            <p class="text-sm italic text-muted-foreground">No events recorded.</p>
                        </div>
                    @endforelse
                </div>
            </x-ui.card>

            {{-- Official Forms --}}
            <div class="rounded-2xl border border-dashed border-border/70 bg-secondary/30 p-5">
                <div class="section-kicker mb-3">Official Forms</div>
                <div class="flex flex-col gap-2.5">
                    <a href="{{ route('health-records.print-form32', $record->uuid) }}" target="_blank" class="flex items-center justify-between rounded-xl border border-border/70 bg-card px-4 py-3 text-sm font-semibold text-foreground transition hover:border-primary/30 hover:bg-primary/5 hover:text-primary">
                        Form 32 (Health Register)
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 7h10v10M7 17 17 7"/></svg>
                    </a>
                    <a href="{{ route('health-records.print-form33', $record->uuid) }}" target="_blank" class="flex items-center justify-between rounded-xl border border-border/70 bg-card px-4 py-3 text-sm font-semibold text-foreground transition hover:border-primary/30 hover:bg-primary/5 hover:text-primary">
                        Form 33 (Fitness Cert)
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 7h10v10M7 17 17 7"/></svg>
                    </a>
                    <div class="my-1 h-px bg-border/70"></div>
                    <a href="{{ route('health-records.print-all', $record->uuid) }}" target="_blank" class="flex items-center justify-between rounded-xl bg-primary px-4 py-3 text-sm font-semibold text-primary-foreground transition hover:bg-primary/90">
                        Print Complete Report
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    </a>
                </div>
            </div>

            {{-- Fast Workflow --}}
            <div class="rounded-2xl border border-[#312E81] bg-[#1E1B4B] p-5">
                <div class="section-kicker !text-white/40 mb-3">Fast Workflow</div>
                <a href="{{ route('health-records.create') }}?prefill={{ $record->employee?->uuid }}" class="flex items-center justify-between rounded-xl bg-white px-4 py-3 text-sm font-bold text-primary transition hover:bg-indigo-50">
                    New Examination
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                </a>
                <p class="mt-3 text-center text-xs text-white/40 leading-relaxed">Start a fresh medical record for this employee.</p>
            </div>

        </div>{{-- /RIGHT COLUMN --}}

    </div>

</div>
@endsection