@extends('layouts.app')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                <a href="{{ route('health-records.index') }}" class="hover:text-primary transition-colors">Health Records</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                <span>Clinical View</span>
            </div>
            <h1 class="mt-1 font-heading text-3xl font-bold tracking-tight text-foreground">{{ $record->full_name }}</h1>
            <p class="text-sm text-muted-foreground">ID: {{ $record->employee_id }} | Company: {{ $record->company_name }} | Exam Date: {{ $record->examination_date ? $record->examination_date->format('d/m/Y') : 'N/A' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('health-records.print', $record->uuid) }}" target="_blank"
               class="flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-sm font-bold shadow-sm transition hover:bg-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                Print Certificate
            </a>
            <a href="{{ route('health-records.print-form32', $record->uuid) }}" target="_blank"
               class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-bold text-emerald-700 shadow-sm transition hover:bg-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                Print Form 32
            </a>
            <a href="{{ route('health-records.print-form33', $record->uuid) }}" target="_blank"
               class="flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-bold text-blue-700 shadow-sm transition hover:bg-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Print Form 33
            </a>
            <a href="{{ route('health-records.edit', $record->uuid) }}"
               class="flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 transition hover:bg-primary/90">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                Edit Clinical Data
            </a>
        </div>
    </div>

    <!-- Quick Vitals Bar -->
    <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
        <div class="rounded-3xl border border-border bg-card p-5 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Status</p>
            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[10px] font-black uppercase tracking-tighter {{ strtolower($record->health_status) === 'fit' ? 'bg-green-500/10 text-green-600' : 'bg-red-500/10 text-red-600' }}">
                {{ $record->health_status ?? 'Unknown' }}
            </span>
        </div>
        <div class="rounded-3xl border border-border bg-card p-5 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Blood Pressure</p>
            <span class="text-xl font-black text-foreground">{{ $record->bp_systolic ?? '--' }}/{{ $record->bp_diastolic ?? '--' }}</span>
        </div>
        <div class="rounded-3xl border border-border bg-card p-5 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Pulse Rate</p>
            <span class="text-xl font-black text-foreground">{{ $record->heart_rate ?? '--' }} <span class="text-[10px] text-muted-foreground font-bold">bpm</span></span>
        </div>
        <div class="rounded-3xl border border-border bg-card p-5 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">SpO2</p>
            <span class="text-xl font-black text-foreground">{{ $record->spo2 ?? '--' }} <span class="text-[10px] text-muted-foreground font-bold">%</span></span>
        </div>
        <div class="rounded-3xl border border-border bg-card p-5 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">BMI</p>
            <span class="text-xl font-black text-foreground">{{ $record->bmi ?? '--' }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <div class="space-y-8 lg:col-span-9">
            <!-- Sections 2-16 -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- 2. Employee Information -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">2. Employee Information</h3>
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <dt class="text-muted-foreground">Father's Name</dt><dd class="font-bold text-right">{{ $record->father_name }}</dd>
                        <dt class="text-muted-foreground">DOB (Age)</dt><dd class="font-bold text-right">{{ $record->dob ? $record->dob->format('d/m/Y') : 'N/A' }} ({{ $record->dob ? $record->dob->age : 'N/A' }}y)</dd>
                        <dt class="text-muted-foreground">Department</dt><dd class="font-bold text-right">{{ $record->department }}</dd>
                        <dt class="text-muted-foreground">Joining Date</dt><dd class="font-bold text-right">{{ $record->joining_date ? $record->joining_date->format('d/m/Y') : 'N/A' }}</dd>
                        <dt class="text-muted-foreground">Identification</dt><dd class="font-bold text-right text-xs">{{ $record->identification_mark }}</dd>
                    </dl>
                </div>

                <!-- 3. Physical Examination -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">3. Physical Examination</h3>
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <dt class="text-muted-foreground">Temperature</dt><dd class="font-bold text-right">{{ $record->temperature }} °F</dd>
                        <dt class="text-muted-foreground">Height</dt><dd class="font-bold text-right">{{ $record->height }} cm</dd>
                        <dt class="text-muted-foreground">Weight</dt><dd class="font-bold text-right">{{ $record->weight }} kg</dd>
                        <dt class="text-muted-foreground">Chest (N/E)</dt><dd class="font-bold text-right">{{ $record->chest_before }} / {{ $record->chest_after }}</dd>
                        <dt class="text-muted-foreground">Respiration</dt><dd class="font-bold text-right">{{ $record->respiration_rate }}</dd>
                    </dl>
                </div>

                <!-- 4. Vision Examination -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">4. Vision Examination</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-secondary/20 p-2 rounded-xl">
                            <p class="text-[9px] font-black uppercase text-muted-foreground">Right Eye</p>
                            <p class="text-xs font-bold">{{ $record->distant_vision_right }} Dist | {{ $record->near_vision_right }} Near</p>
                        </div>
                        <div class="bg-secondary/20 p-2 rounded-xl">
                            <p class="text-[9px] font-black uppercase text-muted-foreground">Left Eye</p>
                            <p class="text-xs font-bold">{{ $record->distant_vision_left }} Dist | {{ $record->near_vision_left }} Near</p>
                        </div>
                    </div>
                    <dl class="flex justify-between text-sm">
                        <dt class="text-muted-foreground">Colour Vision</dt><dd class="font-bold">{{ $record->colour_vision }}</dd>
                    </dl>
                </div>

                <!-- 5. Local Examination -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">5. Local Examination</h3>
                    <div class="grid grid-cols-3 gap-2 text-[10px]">
                        @foreach(['eye', 'nose', 'ear', 'tongue', 'throat', 'teeth', 'skin', 'pefr', 'jaundice', 'anaemia'] as $field)
                            <div class="flex flex-col">
                                <span class="text-muted-foreground uppercase">{{ $field }}</span>
                                <span class="font-bold truncate">{{ $record->$field ?? 'N/A' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 6. Medical History -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm md:col-span-2">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">6. Medical History Examination</h3>
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
                                <span class="px-3 py-1 rounded-full bg-red-500/10 text-red-600 text-[10px] font-black uppercase">{{ $label }}: {{ $val }}</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-secondary/30 text-muted-foreground text-[10px] font-bold uppercase">{{ $label }}: No</span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- 7. History Details -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">7. History Details</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-black text-muted-foreground uppercase">Past History</p>
                            <p class="text-xs font-bold leading-relaxed">{{ $record->past_history ?? 'None recorded.' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-muted-foreground uppercase">Present Complaint</p>
                            <p class="text-xs font-bold leading-relaxed">{{ $record->present_complain ?? 'None.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- 8. Family History -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">8. Family History</h3>
                    <dl class="grid grid-cols-2 gap-y-2 text-sm">
                        <dt class="text-muted-foreground">Father</dt><dd class="font-bold text-right">{{ $record->family_father }}</dd>
                        <dt class="text-muted-foreground">Mother</dt><dd class="font-bold text-right">{{ $record->family_mother }}</dd>
                        <dt class="text-muted-foreground">Siblings</dt><dd class="font-bold text-right">{{ $record->family_brother }} / {{ $record->family_sister }}</dd>
                    </dl>
                </div>

                <!-- 9. Systemic & 10. Investigations -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">9. Systemic</h3>
                            <dl class="space-y-1 text-[11px]">
                                <div class="flex justify-between"><span>Resp System</span><span class="font-bold">{{ $record->resp_system }}</span></div>
                                <div class="flex justify-between"><span>CVS</span><span class="font-bold">{{ $record->cvs }}</span></div>
                                <div class="flex justify-between"><span>CNS</span><span class="font-bold">{{ $record->cns }}</span></div>
                                <div class="flex justify-between"><span>Abdomen</span><span class="font-bold">{{ $record->per_abdomen }}</span></div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">10. Investigations</h3>
                            <dl class="space-y-1 text-[11px]">
                                <div class="flex justify-between"><span>PFT</span><span class="font-bold">{{ $record->pft }}</span></div>
                                <div class="flex justify-between"><span>X-Ray</span><span class="font-bold">{{ $record->xray_chest }}</span></div>
                                <div class="flex justify-between"><span>ECG</span><span class="font-bold">{{ $record->ecg }}</span></div>
                                <div class="flex justify-between"><span>Audiometry</span><span class="font-bold">{{ $record->audiometry }}</span></div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- 11. Lab & 12. Urine -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm md:col-span-2">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">11. Laboratory & 12. Urine Reports</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach(['hb', 'rbc', 'wbc_tc', 'platelet', 'fbs', 'sgpt', 's_creatinine', 'urine_albumin', 'urine_sugar', 'urine_pus_cell'] as $lab)
                            <div class="flex flex-col bg-secondary/10 p-2 rounded-xl">
                                <span class="text-[9px] font-black text-muted-foreground uppercase">{{ $lab }}</span>
                                <span class="text-xs font-bold">{{ $record->$lab ?? '--' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 13. Final Assessment -->
                <div class="rounded-[2.5rem] border-2 border-primary/20 bg-primary/5 p-8 shadow-sm md:col-span-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-black text-foreground uppercase tracking-tight">13. Final Medical Status</h3>
                            <p class="text-sm text-muted-foreground">Assessment results for current occupation</p>
                        </div>
                        <div class="px-6 py-2 rounded-2xl {{ strtolower($record->health_status) === 'fit' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }} text-xl font-black uppercase italic shadow-lg">
                            {{ $record->health_status }}
                        </div>
                    </div>
                </div>

                <!-- 14. Doctor Details -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">14. Doctor Details</h3>
                    <div class="space-y-1">
                        <p class="text-sm font-black text-foreground">Dr. {{ $record->doctor_name }}</p>
                        <p class="text-xs text-muted-foreground font-bold">{{ $record->doctor_qualification }}</p>
                        <p class="text-[10px] text-primary/70 font-black mt-2">SEAL: {{ $record->doctor_seal ?? 'None' }}</p>
                    </div>
                </div>

                <!-- 15. Job & Advice -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">15. Job & Advice</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-[10px] font-black text-muted-foreground uppercase">Restrictions</span>
                            <p class="text-xs font-bold text-red-600">{{ $record->job_restriction ?? 'No Restrictions' }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-muted-foreground uppercase">Remarks</span>
                            <p class="text-xs font-bold leading-relaxed">{{ $record->doctor_remarks }}</p>
                        </div>
                    </div>
                </div>

                <!-- 16. Review Section -->
                <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm md:col-span-2">
                    <h3 class="text-sm font-black uppercase text-primary mb-4 border-b border-border pb-2">16. Review Section</h3>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-secondary/50 flex items-center justify-center font-black text-primary italic">RV</div>
                            <div>
                                <p class="text-[10px] font-black text-muted-foreground uppercase">Reviewed By</p>
                                <p class="text-sm font-bold">{{ $record->reviewed_by ?? 'Pending Review' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-muted-foreground uppercase">Report UUID</p>
                            <p class="text-[10px] font-mono text-muted-foreground">{{ $record->uuid }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Admin & History -->
        <div class="space-y-8 lg:col-span-3">
             <!-- Status Card -->
             <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm">
                <h3 class="mb-4 text-[10px] font-black uppercase text-muted-foreground tracking-widest">Administrative</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-muted-foreground">Record Status</span>
                        <span class="px-2 py-0.5 rounded-full {{ $record->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-[9px] font-black uppercase">{{ $record->status }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-muted-foreground">Mobile</span>
                        <span class="text-xs font-bold">{{ $record->mobile }}</span>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm">
                <h3 class="mb-6 text-[10px] font-black uppercase text-muted-foreground tracking-widest">Recent Activity</h3>
                <div class="relative space-y-6 before:absolute before:left-[11px] before:top-2 before:h-[calc(100%-16px)] before:w-px before:bg-border/50">
                    @forelse($activities as $activity)
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-1.5 h-6 w-6 rounded-full border border-border bg-card flex items-center justify-center shadow-sm">
                                <div class="h-1.5 w-1.5 rounded-full bg-primary"></div>
                            </div>
                            <p class="text-[11px] font-bold text-foreground leading-tight">{{ $activity->description }}</p>
                            <p class="mt-0.5 text-[9px] text-muted-foreground">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-[10px] text-muted-foreground text-center py-4 italic">No activity recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
