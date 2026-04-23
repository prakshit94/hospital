@extends('layouts.app')

@php
    $pageTitle = 'Health Record Details';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <x-ui.button variant="secondary" size="sm" href="{{ route('health-records.index') }}" class="gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="m15 18-6-6 6-6"/>
                            </svg>
                            Back to Directory
                        </x-ui.button>
                        <span class="ui-chip !bg-primary/5 !text-primary uppercase text-[10px] tracking-widest font-black italic">{{ $record->status }} Record</span>
                    </div>
                    <h1 class="hero-title">{{ $record->full_name }}</h1>
                    <p class="hero-copy">ID: {{ $record->employee_id }} | Company: {{ $record->company_name }} | Exam Date: {{ $record->examination_date ? $record->examination_date->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <x-ui.button variant="secondary" href="{{ route('health-records.print', $record->uuid) }}" target="_blank" class="gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                        Print Report
                    </x-ui.button>
                    <x-ui.button variant="primary" href="{{ route('health-records.edit', $record->uuid) }}" class="gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        Edit Data
                    </x-ui.button>
                </div>
            </div>
        </section>

        <!-- Quick Vitals Ribbon -->
        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
            @php
                $vitals = [
                    ['label' => 'Health Status', 'value' => $record->health_status ?? 'Unknown', 'color' => strtolower($record->health_status) === 'fit' ? 'text-emerald-500' : 'text-danger'],
                    ['label' => 'Blood Pressure', 'value' => ($record->bp_systolic ?? '--') . '/' . ($record->bp_diastolic ?? '--'), 'sub' => 'mmHg'],
                    ['label' => 'Pulse Rate', 'value' => $record->heart_rate ?? '--', 'sub' => 'bpm'],
                    ['label' => 'SpO2 Level', 'value' => ($record->spo2 ?? '--'), 'sub' => '%'],
                    ['label' => 'BMI Result', 'value' => $record->bmi ?? '--', 'sub' => 'kg/m²'],
                ];
            @endphp
            @foreach($vitals as $vital)
                <x-ui.card class="!p-5 text-center flex flex-col items-center justify-center">
                    <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">{{ $vital['label'] }}</p>
                    <div class="text-xl font-black {{ $vital['color'] ?? 'text-foreground' }}">
                        {{ $vital['value'] }}
                        @if(isset($vital['sub']))
                            <span class="text-[10px] text-muted-foreground font-bold tracking-normal">{{ $vital['sub'] }}</span>
                        @endif
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        <section class="grid gap-8 lg:grid-cols-12 items-start">
            <div class="lg:col-span-8 space-y-8">
                <!-- 2 & 3 Information -->
                <div class="grid gap-6 md:grid-cols-2">
                    <x-ui.card>
                        <div class="section-header">
                            <div>
                                <div class="section-kicker">Section 02</div>
                                <h2 class="section-title text-base">Employee Information</h2>
                            </div>
                        </div>
                        <div class="detail-grid">
                            <div class="detail-tile">
                                <div class="detail-label">Father's Name</div>
                                <div class="detail-value text-sm">{{ $record->father_name ?? 'N/A' }}</div>
                            </div>
                            <div class="detail-tile">
                                <div class="detail-label">DOB (Age)</div>
                                <div class="detail-value text-sm">{{ $record->dob ? $record->dob->format('d/m/Y') : 'N/A' }} ({{ $record->dob ? (int)$record->dob->diffInYears(now()) : 'N/A' }}y)</div>
                            </div>
                            <div class="detail-tile">
                                <div class="detail-label">Department</div>
                                <div class="detail-value text-sm">{{ $record->department ?? 'N/A' }}</div>
                            </div>
                            <div class="detail-tile">
                                <div class="detail-label">Joining Date</div>
                                <div class="detail-value text-sm">{{ $record->joining_date ? $record->joining_date->format('d/m/Y') : 'N/A' }}</div>
                            </div>
                        </div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="section-header">
                            <div>
                                <div class="section-kicker">Section 03</div>
                                <h2 class="section-title text-base">Physical Examination</h2>
                            </div>
                        </div>
                        <div class="detail-grid">
                            <div class="detail-tile">
                                <div class="detail-label">Temperature</div>
                                <div class="detail-value text-sm">{{ $record->temperature ?? '--' }} °F</div>
                            </div>
                            <div class="detail-tile">
                                <div class="detail-label">Height / Weight</div>
                                <div class="detail-value text-sm">{{ $record->height ?? '--' }}cm / {{ $record->weight ?? '--' }}kg</div>
                            </div>
                            <div class="detail-tile">
                                <div class="detail-label">Chest (N/E)</div>
                                <div class="detail-value text-sm">{{ $record->chest_before ?? '--' }} / {{ $record->chest_after ?? '--' }}</div>
                            </div>
                            <div class="detail-tile">
                                <div class="detail-label">Respiration</div>
                                <div class="detail-value text-sm">{{ $record->respiration_rate ?? '--' }}</div>
                            </div>
                        </div>
                    </x-ui.card>
                </div>

                <!-- 4. Vision -->
                <x-ui.card>
                    <div class="section-header">
                        <div>
                            <div class="section-kicker">Section 04</div>
                            <h2 class="section-title text-base">Vision Examination</h2>
                        </div>
                    </div>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="bg-secondary/20 p-4 rounded-2xl">
                            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-2">Right Eye</p>
                            <div class="flex justify-between">
                                <span class="text-xs font-bold">Distant: {{ $record->distant_vision_right ?? '--' }}</span>
                                <span class="text-xs font-bold">Near: {{ $record->near_vision_right ?? '--' }}</span>
                            </div>
                        </div>
                        <div class="bg-secondary/20 p-4 rounded-2xl">
                            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-2">Left Eye</p>
                            <div class="flex justify-between">
                                <span class="text-xs font-bold">Distant: {{ $record->distant_vision_left ?? '--' }}</span>
                                <span class="text-xs font-bold">Near: {{ $record->near_vision_left ?? '--' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between border-t border-border pt-4">
                        <span class="text-xs font-bold text-muted-foreground uppercase">Colour Vision</span>
                        <span class="text-sm font-black text-foreground">{{ $record->colour_vision ?? 'N/A' }}</span>
                    </div>
                </x-ui.card>

                <!-- 6. Medical History -->
                <x-ui.card>
                    <div class="section-header">
                        <div>
                            <div class="section-kicker">Section 06</div>
                            <h2 class="section-title text-base">Medical History Screening</h2>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach([
                            'Hypertension' => $record->hypertension,
                            'Diabetes' => $record->diabetes,
                            'Dyslipidemia' => $record->dyslipidemia,
                            'Tuberculosis' => $record->tuberculosis,
                            'Epilepsy' => $record->epilepsy,
                            'Asthma' => $record->asthma,
                            'Heart Disease' => $record->heart_disease
                        ] as $label => $val)
                            @if(strtolower($val) !== 'no' && $val)
                                <span class="px-4 py-1.5 rounded-xl bg-danger/10 text-danger text-[10px] font-black uppercase border border-danger/20">{{ $label }}: {{ $val }}</span>
                            @else
                                <span class="px-4 py-1.5 rounded-xl bg-secondary/40 text-muted-foreground text-[10px] font-bold uppercase">{{ $label }}: No</span>
                            @endif
                        @endforeach
                    </div>
                </x-ui.card>

                <!-- 11 & 12. Investigations -->
                <x-ui.card>
                    <div class="section-header">
                        <div>
                            <div class="section-kicker">Section 11 & 12</div>
                            <h2 class="section-title text-base">Clinical Investigations & Laboratory</h2>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach([
                            'HB' => $record->hb,
                            'RBC' => $record->rbc,
                            'WBC TC' => $record->wbc_tc,
                            'Platelet' => $record->platelet,
                            'FBS' => $record->fbs,
                            'SGPT' => $record->sgpt,
                            'S. Creatinine' => $record->s_creatinine,
                            'Urine Albumin' => $record->urine_albumin,
                        ] as $lab => $val)
                            <div class="bg-secondary/10 p-3 rounded-2xl border border-border/50">
                                <p class="text-[9px] font-black text-muted-foreground uppercase tracking-widest mb-1">{{ $lab }}</p>
                                <p class="text-xs font-extrabold text-foreground">{{ $val ?? '--' }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <!-- 15. Job & Advice -->
                <x-ui.card>
                    <div class="section-header">
                        <div>
                            <div class="section-kicker">Section 15</div>
                            <h2 class="section-title text-base">Job Restriction & Clinical Advice</h2>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="detail-tile !border-none !p-0">
                            <div class="detail-label">Restrictions</div>
                            <div class="mt-2 text-sm font-bold text-danger">{{ $record->job_restriction ?? 'No functional restrictions identified.' }}</div>
                        </div>
                        <div class="detail-tile !border-none !p-0">
                            <div class="detail-label">Doctor's Remarks</div>
                            <div class="mt-2 text-sm font-medium leading-relaxed text-foreground bg-secondary/20 p-4 rounded-2xl italic">{{ $record->doctor_remarks ?? 'No additional remarks.' }}</div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- 17. Documents -->
                <x-ui.card>
                    <div class="section-header">
                        <div>
                            <div class="section-kicker">Section 17</div>
                            <h2 class="section-title text-base">Documents</h2>
                        </div>
                        <a href="{{ route('health-records.edit', $record->uuid) }}#document_upload"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M12 12v9"/><path d="m8 17 4-5 4 5"/></svg>
                            Upload More
                        </a>
                    </div>

                    @if($record->documents->isEmpty())
                        <div class="flex flex-col items-center justify-center py-10 text-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-muted-foreground/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                            </svg>
                            <p class="text-sm text-muted-foreground font-medium">No documents uploaded yet.</p>
                            <a href="{{ route('health-records.edit', $record->uuid) }}#document_upload"
                               class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
                                Upload first document →
                            </a>
                        </div>
                    @else
                        <ul class="divide-y divide-border">
                            @foreach($record->documents as $doc)
                                @php
                                    $ext   = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
                                    $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                    $isPdf = $ext === 'pdf';
                                    $iconColor = $isPdf ? 'text-red-400' : ($isImg ? 'text-blue-400' : 'text-teal-400');
                                @endphp
                                <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-secondary/40 {{ $iconColor }}">
                                            @if($isPdf)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                            @elseif($isImg)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-foreground truncate">{{ $doc->original_name }}</p>
                                            <p class="text-[10px] text-muted-foreground font-medium mt-0.5">
                                                {{ strtoupper($ext) }} &bull; {{ $doc->formatted_size }} &bull; {{ $doc->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($doc->path) }}" target="_blank"
                                       class="shrink-0 inline-flex items-center gap-1 rounded-lg border border-border bg-secondary/30 px-3 py-1.5 text-xs font-bold text-foreground transition hover:bg-primary hover:text-white hover:border-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        View
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-ui.card>
            </div>

            <!-- Side Cards -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Administrative Card -->
                <x-ui.card>
                    <div class="section-header">
                        <div>
                            <div class="section-kicker">Meta Data</div>
                            <h2 class="section-title text-base">Administrative</h2>
                        </div>
                    </div>
                    <div class="detail-grid !grid-cols-1">
                        <div class="detail-tile">
                            <div class="detail-label">Record Status</div>
                            <div class="detail-value">
                                <span class="ui-status-{{ $record->status === 'active' ? 'success' : 'danger' }} uppercase tracking-widest text-[10px]">{{ $record->status }}</span>
                            </div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Mobile Contact</div>
                            <div class="detail-value text-sm">{{ $record->mobile ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Examined By</div>
                            <div class="detail-value">
                                <div class="font-black text-sm">Dr. {{ $record->doctor_name }}</div>
                                <div class="text-[10px] text-muted-foreground font-bold mt-0.5">{{ $record->doctor_qualification }}</div>
                            </div>
                        </div>
                        <div class="detail-tile">
                            <div class="detail-label">Documents</div>
                            <div class="detail-value">
                                @if($record->documents->isEmpty())
                                    <span class="text-xs text-muted-foreground">None uploaded</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-teal-500/10 text-teal-600 text-xs font-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        {{ $record->documents->count() }} {{ Str::plural('file', $record->documents->count()) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Activity Log -->
                <x-ui.card>
                    <div class="section-header">
                        <div>
                            <div class="section-kicker">Audit Trail</div>
                            <h2 class="section-title text-base">Recent Activity</h2>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @forelse($activities as $activity)
                            <div class="flex gap-3">
                                <div class="shrink-0 mt-1">
                                    <div class="size-2.5 rounded-full bg-primary ring-4 ring-primary/10"></div>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-foreground leading-tight">{{ $activity->description }}</p>
                                    <p class="mt-1 text-[10px] text-muted-foreground font-medium">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-xs text-muted-foreground italic">No events recorded.</p>
                            </div>
                        @endforelse
                    </div>
                </x-ui.card>

                <!-- Forms Print -->
                <x-ui.card class="!bg-secondary/20 border-dashed">
                    <h3 class="text-[10px] font-black uppercase text-muted-foreground tracking-widest mb-4">Official Forms</h3>
                    <div class="grid gap-3">
                        <x-ui.button variant="secondary" size="sm" href="{{ route('health-records.print-form32', $record->uuid) }}" target="_blank" class="w-full justify-between !bg-white">
                            Form 32 (Health Register)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 7h10v10M7 17L17 7"/></svg>
                        </x-ui.button>
                        <x-ui.button variant="secondary" size="sm" href="{{ route('health-records.print-form33', $record->uuid) }}" target="_blank" class="w-full justify-between !bg-white">
                            Form 33 (Fitness Cert)
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 7h10v10M7 17L17 7"/></svg>
                        </x-ui.button>
                        <div class="my-0.5 border-t border-border/50"></div>
                        <x-ui.button variant="primary" size="sm" href="{{ route('health-records.print-all', $record->uuid) }}" target="_blank" class="w-full justify-between shadow-sm">
                            Print Complete Report
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                        </x-ui.button>
                    </div>
                </x-ui.card>
            </div>
        </section>
    </div>
@endsection
