@php $labelCounter = 1; $prefillEmployee = $prefillEmployee ?? null; @endphp
<div class="space-y-10">
    <!-- 2. Employee Information -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">2. Employee Information</h3>
        </div>
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Employee No</label>
                <div class="relative">
                    <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $prefillEmployee?->employee_id ?? $record->employee?->employee_id ?? '') }}"
                           class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20 @error('employee_id') border-red-500 @enderror">
                    <div id="employee_id_loading" class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-primary">
                        <svg class="animate-spin size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
                @error('employee_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Date of Examination</label>
                <input type="date" name="examination_date" value="{{ old('examination_date', $record->examination_date ? $record->examination_date->format('Y-m-d') : date('Y-m-d')) }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2 lg:col-span-1">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Employee Name <span class="text-red-500">*</span></label>
                <input type="text" name="full_name" value="{{ old('full_name', $prefillEmployee?->full_name ?? $record->full_name ?? '') }}" required
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20 @error('full_name') border-red-500 @enderror">
                @error('full_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Father's Name</label>
                <input type="text" name="father_name" value="{{ old('father_name', $prefillEmployee?->father_name ?? $record->father_name ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Date of Birth <span class="text-red-500">*</span></label>
                <input type="date" name="dob" value="{{ old('dob', $prefillEmployee?->dob?->format('Y-m-d') ?? ($record->dob ? $record->dob->format('Y-m-d') : '')) }}" required
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20 @error('dob') border-red-500 @enderror">
                @error('dob') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Department</label>
                <input type="text" name="department" value="{{ old('department', $prefillEmployee?->department ?? $record->department ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Sex <span class="text-red-500">*</span></label>
                <select name="gender" required
                        class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20 @error('gender') border-red-500 @enderror">
                    <option value="">Select Sex</option>
                    @php $currentGender = old('gender', $prefillEmployee?->gender ?? $record->gender); @endphp
                    <option value="male" {{ $currentGender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $currentGender == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $currentGender == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Joining Date</label>
                <input type="date" name="joining_date" value="{{ old('joining_date', $prefillEmployee?->joining_date?->format('Y-m-d') ?? ($record->joining_date ? $record->joining_date->format('Y-m-d') : '')) }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Identification Mark</label>
                <input type="text" name="identification_mark" value="{{ old('identification_mark', $prefillEmployee?->identification_mark ?? $record->identification_mark ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Habit (H/O Habit)</label>
                <input type="text" name="habits" value="{{ old('habits', $prefillEmployee?->habits ?? $record->habits ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Marital Status</label>
                <select name="marital_status" class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                    <option value="">Select Marital Status</option>
                    @php $currentMarital = old('marital_status', $prefillEmployee?->marital_status ?? $record->marital_status); @endphp
                    <option value="Single" {{ $currentMarital == 'Single' ? 'selected' : '' }}>Single</option>
                    <option value="Married" {{ $currentMarital == 'Married' ? 'selected' : '' }}>Married</option>
                    <option value="Unmarried" {{ $currentMarital == 'Unmarried' ? 'selected' : '' }}>Unmarried</option>
                    <option value="NA" {{ $currentMarital == 'NA' ? 'selected' : '' }}>NA</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Designation</label>
                <input type="text" name="designation" value="{{ old('designation', $prefillEmployee?->designation ?? $record->designation ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Husband's Name</label>
                <input type="text" name="husband_name" value="{{ old('husband_name', $prefillEmployee?->husband_name ?? $record->husband_name ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Company Name <span class="text-red-500">*</span></label>
                <select name="company_id" required id="company_id"
                        class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20 @error('company_id') border-red-500 @enderror">
                    <option value="">Select Company</option>
                    @foreach($globalCompanies as $company)
                        <option value="{{ $company->id }}" 
                                {{ old('company_id', $prefillEmployee?->company_id ?? $record->company_id ?? session('current_company_id')) == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <input type="hidden" name="company_name" id="company_name" value="{{ old('company_name', $record->company_name ?? session('current_company_name')) }}">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Dependents</label>
                <input type="text" name="dependent" value="{{ old('dependent', $prefillEmployee?->dependent ?? $record->dependent ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2 lg:col-span-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Previous Occupational History</label>
                <input type="text" name="prev_occ_history" value="{{ old('prev_occ_history', $prefillEmployee?->prev_occ_history ?? $record->prev_occ_history ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Mobile Number</label>
                <input type="text" name="mobile" value="{{ old('mobile', $prefillEmployee?->mobile ?? $record->mobile ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Email</label>
                <input type="email" name="email" value="{{ old('email', $prefillEmployee?->email ?? $record->email ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Blood Group</label>
                <select name="blood_group" class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                    <option value="">Select Blood Group</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                        <option value="{{ $bg }}" {{ old('blood_group', $prefillEmployee?->blood_group ?? $record->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2 lg:col-span-3">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Address</label>
                <input type="text" name="address" value="{{ old('address', $prefillEmployee?->address ?? $record->address ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>
        </div>
    </div>

    <!-- 3. Physical Examination -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500/10 text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">3. Physical Examination</h3>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Temperature (F)</label>
                <input type="number" step="0.1" name="temperature" value="{{ old('temperature', $record->temperature ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Height (cm)</label>
                <input type="number" step="0.1" name="height" id="Height" value="{{ old('height', $record->height ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Chest Before Breathing (cm)</label>
                <input type="text" name="chest_before" value="{{ old('chest_before', $record->chest_before ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Pulse Rate (bpm)</label>
                <input type="number" name="heart_rate" value="{{ old('heart_rate', $record->heart_rate ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Weight (kg)</label>
                <input type="number" step="0.1" name="weight" id="Weight" value="{{ old('weight', $record->weight ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Chest After Breathing (cm)</label>
                <input type="text" name="chest_after" value="{{ old('chest_after', $record->chest_after ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Blood Pressure (BP)</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="bp_systolic" value="{{ old('bp_systolic', $record->bp_systolic ?? '') }}" placeholder="Sys"
                           class="w-1/2 rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                    <span class="text-muted-foreground">/</span>
                    <input type="number" name="bp_diastolic" value="{{ old('bp_diastolic', $record->bp_diastolic ?? '') }}" placeholder="Dia"
                           class="w-1/2 rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. BMI</label>
                <input type="text" name="bmi" id="BMI" value="{{ old('bmi', $record->bmi) }}" readonly
                       class="w-full rounded-xl border-border bg-secondary/10 py-2.5 px-4 text-sm cursor-not-allowed">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. SpO₂ (%)</label>
                <input type="number" name="spo2" value="{{ old('spo2', $record->spo2 ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Respiration Rate</label>
                <input type="text" name="respiration_rate" value="{{ old('respiration_rate', $record->respiration_rate ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>
        </div>
    </div>

    <!-- 4. Vision Examination -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/10 text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">4. Vision Examination</h3>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <!-- Right Eye -->
            <div class="space-y-4 rounded-2xl bg-secondary/10 p-4">
                <h4 class="font-black text-[10px] uppercase text-primary/60 tracking-widest border-b border-primary/10 pb-2">Right Eye</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase">Specs</label>
                        <input type="text" name="right_eye_specs" value="{{ old('right_eye_specs', $record->right_eye_specs ?? '') }}"
                               class="w-full rounded-lg border-border bg-background py-1.5 px-3 text-xs">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase">Near Vision</label>
                        <input type="text" name="near_vision_right" value="{{ old('near_vision_right', $record->near_vision_right ?? '') }}"
                               class="w-full rounded-lg border-border bg-background py-1.5 px-3 text-xs">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase">Distant Vision</label>
                        <input type="text" name="distant_vision_right" value="{{ old('distant_vision_right', $record->distant_vision_right ?? '') }}"
                               class="w-full rounded-lg border-border bg-background py-1.5 px-3 text-xs">
                    </div>
                </div>
            </div>
            <!-- Left Eye -->
            <div class="space-y-4 rounded-2xl bg-secondary/10 p-4">
                <h4 class="font-black text-[10px] uppercase text-primary/60 tracking-widest border-b border-primary/10 pb-2">Left Eye</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase">Specs</label>
                        <input type="text" name="left_eye_specs" value="{{ old('left_eye_specs', $record->left_eye_specs ?? '') }}"
                               class="w-full rounded-lg border-border bg-background py-1.5 px-3 text-xs">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase">Near Vision</label>
                        <input type="text" name="near_vision_left" value="{{ old('near_vision_left', $record->near_vision_left ?? '') }}"
                               class="w-full rounded-lg border-border bg-background py-1.5 px-3 text-xs">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-muted-foreground uppercase">Distant Vision</label>
                        <input type="text" name="distant_vision_left" value="{{ old('distant_vision_left', $record->distant_vision_left ?? '') }}"
                               class="w-full rounded-lg border-border bg-background py-1.5 px-3 text-xs">
                    </div>
                </div>
            </div>
            <div class="space-y-2 md:col-span-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Colour Vision</label>
                <input type="text" name="colour_vision" value="{{ old('colour_vision', $record->colour_vision ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>
        </div>
    </div>

    <!-- 5. Local Examination -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/10 text-orange-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">5. Local Examination</h3>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-5">
            @foreach(['eye' => 'Eye', 'nose' => 'Nose', 'ear' => 'Ear', 'conjunctiva' => 'Conjunctiva', 'tongue' => 'Tongue', 'nails' => 'Nails', 'throat' => 'Throat', 'skin' => 'Skin', 'teeth' => 'Teeth', 'pefr' => 'PEFR', 'eczema' => 'Eczema', 'cyanosis' => 'Cyanosis', 'jaundice' => 'Jaundice', 'anaemia' => 'Anaemia', 'oedema' => 'Oedema', 'clubbing' => 'Clubbing', 'allergy_status' => 'Allergy'] as $key => $label)
                <div class="space-y-2">
                    <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. {{ $label }}</label>
                    <input type="text" name="{{ $key }}" value="{{ old($key, $record->$key ?? '') }}"
                           class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                </div>
            @endforeach
        </div>
    </div>

    <!-- 6. Medical History Examination -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 2v20M2 12h20"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">6. Medical History Examination</h3>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach(['hypertension' => 'Hypertension', 'diabetes' => 'Diabetes', 'dyslipidemia' => 'Dyslipidemia', 'radiation_effect' => 'Radiation Effect', 'vertigo' => 'Vertigo', 'tuberculosis' => 'Tuberculosis', 'thyroid_disorder' => 'Thyroid Disorder', 'epilepsy' => 'Epilepsy', 'asthma' => 'Bronchial Asthma (Br_Asthma)', 'heart_disease' => 'Heart Disease'] as $key => $label)
                <div class="space-y-2">
                    <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. {{ $label }}</label>
                    <input type="text" name="{{ $key }}" value="{{ old($key, $record->$key ?? '') }}"
                           class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                </div>
            @endforeach
        </div>
    </div>

    <!-- 7. History Details -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-500/10 text-violet-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">7. History Details</h3>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Past History</label>
                <textarea name="past_history" rows="2" class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">{{ old('past_history', $record->past_history ?? '') }}</textarea>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Present Complaint</label>
                <textarea name="present_complain" rows="2" class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">{{ old('present_complain', $record->present_complain ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <!-- 8. Family History -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-500/10 text-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">8. Family History</h3>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach(['family_father' => 'Father', 'family_mother' => 'Mother', 'family_brother' => 'Brother', 'family_sister' => 'Sister'] as $key => $label)
                <div class="space-y-2">
                    <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. {{ $label }}</label>
                    <input type="text" name="{{ $key }}" value="{{ old($key, $record->$key ?? '') }}"
                           class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                </div>
            @endforeach
        </div>
    </div>

    <!-- 9. Systemic Examination -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M12 12v9"/><path d="m8 17 4 4 4-4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">9. Systemic Examination</h3>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach(['resp_system' => 'Respiratory System', 'genito_urinary' => 'Genito Urinary System', 'cvs' => 'CVS (Cardiovascular System)', 'cns' => 'CNS (Central Nervous System)', 'per_abdomen' => 'Per Abdomen', 'ent' => 'ENT'] as $key => $label)
                <div class="space-y-2">
                    <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. {{ $label }}</label>
                    <input type="text" name="{{ $key }}" value="{{ old($key, $record->$key ?? '') }}"
                           class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                </div>
            @endforeach
        </div>
    </div>

    <!-- 10. Investigations -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">10. Investigations</h3>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach(['pft' => 'PFT (Pulmonary Function Test)', 'xray_chest' => 'X-Ray Chest', 'vertigo_test' => 'Vertigo Test', 'audiometry' => 'Audiometry', 'ecg' => 'ECG'] as $key => $label)
                <div class="space-y-2">
                    <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. {{ $label }}</label>
                    <input type="text" name="{{ $key }}" value="{{ old($key, $record->$key ?? '') }}"
                           class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                </div>
            @endforeach
        </div>
    </div>

    <!-- 11. Laboratory Tests -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-500/10 text-pink-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M14.5 2v17.5c0 1.4-1.1 2.5-2.5 2.5h-1c-1.4 0-2.5-1.1-2.5-2.5V2"/><path d="M8.5 2h7"/><path d="M14.5 16h-5"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">11. Laboratory Tests</h3>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach([
                'hb' => 'Hemoglobin (HB)',
                'wbc_tc' => 'WBC Count',
                'parasite_dc' => 'Parasite (MP)',
                'rbc' => 'RBC Count',
                'platelet' => 'Platelet Count',
                'esr' => 'ESR',
                'fbs' => 'FBS (Fasting Blood Sugar)',
                'pp2bs' => 'PP2BS',
                'sgpt' => 'SGPT',
                's_creatinine' => 'Serum Creatinine',
                'rbs' => 'RBS (Random Blood Sugar)',
                's_chol' => 'Serum Cholesterol',
                's_trg' => 'Serum Triglycerides (TRG)',
                's_hdl' => 'Serum HDL',
                's_ldl' => 'Serum LDL',
                'ch_ratio' => 'C/H Ratio'
            ] as $key => $label)
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-muted-foreground uppercase tracking-wider">{{ $labelCounter++ }}. {{ $label }}</label>
                    <input type="text" name="{{ $key }}" value="{{ old($key, $record->$key ?? '') }}"
                           class="w-full rounded-xl border-border bg-secondary/30 py-2 px-4 text-xs transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                </div>
            @endforeach
        </div>
    </div>

    <!-- 12. Urine Report -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-500/10 text-yellow-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">12. Urine Report</h3>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach([
                'urine_colour' => 'Colour',
                'urine_reaction' => 'Reaction (pH)',
                'urine_albumin' => 'Albumin',
                'urine_sugar' => 'Sugar',
                'urine_pus_cell' => 'Pus Cells',
                'urine_rbc' => 'Urine RBC',
                'urine_epi_cell' => 'EpiCell',
                'urine_crystal' => 'Crystals'
            ] as $key => $label)
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-muted-foreground uppercase tracking-wider">{{ $labelCounter++ }}. {{ $label }}</label>
                    <input type="text" name="{{ $key }}" value="{{ old($key, $record->$key ?? '') }}"
                           class="w-full rounded-xl border-border bg-secondary/30 py-2 px-4 text-xs transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                </div>
            @endforeach
        </div>
    </div>

    <!-- 13. Final Assessment -->
    <div class="rounded-[2.5rem] border-2 border-primary/20 bg-primary/5 p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-primary/20 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">13. Final Assessment</h3>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Health Status</label>
                <select name="health_status" class="w-full rounded-xl border-border bg-background py-2.5 px-4 text-sm focus:ring-2 focus:ring-primary/20">
                    <option value="">Select Health Status</option>
                    <option value="Fit" {{ old('health_status', $record->health_status) == 'Fit' ? 'selected' : '' }}>Fit</option>
                    <option value="Unfit" {{ old('health_status', $record->health_status) == 'Unfit' ? 'selected' : '' }}>Unfit</option>
                    <option value="Fit with Restrictions" {{ old('health_status', $record->health_status) == 'Fit with Restrictions' ? 'selected' : '' }}>Fit with Restrictions</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 14. Doctor Details -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600/10 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 16v6"/><path d="M22 19h-6"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">14. Doctor Details</h3>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Doctor Name</label>
                <input type="text" name="doctor_name" value="{{ old('doctor_name', $record->doctor_name ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Qualification</label>
                <input type="text" name="doctor_qualification" value="{{ old('doctor_qualification', $record->doctor_qualification ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Doctor Signature</label>
                <input type="text" name="doctor_signature" value="{{ old('doctor_signature', $record->doctor_signature ?? '') }}" placeholder="e.g. Digitally Signed"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Seal of Doctor</label>
                <input type="text" name="doctor_seal" value="{{ old('doctor_seal', $record->doctor_seal ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>
        </div>
    </div>

    <!-- 15. Job & Advice -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">15. Job & Advice</h3>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Job Restriction</label>
                <input type="text" name="job_restriction" value="{{ old('job_restriction', $record->job_restriction ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Doctor Remarks</label>
                <textarea name="doctor_remarks" rows="2" class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">{{ old('doctor_remarks', $record->doctor_remarks ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <!-- 16. Review Section & Administrative -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-500/10 text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-foreground">16. Review Section & Administrative</h3>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Reviewed By</label>
                <input type="text" name="reviewed_by" value="{{ old('reviewed_by', $record->reviewed_by ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>
            
            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Record Status <span class="text-red-500">*</span></label>
                <select name="status" required
                        class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
                    <option value="active" {{ old('status', $record->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $record->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Hazardous Process</label>
                <input type="text" name="hazardous_process" value="{{ old('hazardous_process', $record->hazardous_process ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Dangerous Operation</label>
                <input type="text" name="dangerous_operation" value="{{ old('dangerous_operation', $record->dangerous_operation ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-foreground/80">{{ $labelCounter++ }}. Materials Exposed</label>
                <input type="text" name="materials_exposed" value="{{ old('materials_exposed', $record->materials_exposed ?? '') }}"
                       class="w-full rounded-xl border-border bg-secondary/30 py-2.5 px-4 text-sm transition-focus focus:bg-background focus:ring-2 focus:ring-primary/20">
            </div>
        </div>
    </div>

    <!-- 17. Documents -->
    <div class="rounded-[2.5rem] border border-border bg-card p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-border/50 pb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-500/10 text-teal-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-foreground">17. Documents</h3>
                <p class="text-xs text-muted-foreground mt-0.5">Upload reports, lab results, X-rays or any relevant files (PDF, DOC, JPG, PNG — max 10 MB each)</p>
            </div>
        </div>

        {{-- Existing documents (edit mode) --}}
        @if(isset($record) && $record->exists && $record->relationLoaded('documents') && $record->documents->count())
            <div class="space-y-2">
                <p class="text-sm font-bold text-foreground/70">Uploaded Documents</p>
                <ul class="divide-y divide-border rounded-2xl border border-border overflow-hidden">
                    @foreach($record->documents as $doc)
                        <li class="flex items-center justify-between gap-4 px-4 py-3 bg-secondary/10 hover:bg-secondary/20 transition">
                            <div class="flex items-center gap-3 min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 text-teal-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                </svg>
                                <span class="text-sm font-medium text-foreground truncate">{{ $doc->original_name }}</span>
                                <span class="text-xs text-muted-foreground shrink-0">{{ $doc->formatted_size }}</span>
                            </div>
                            <a href="{{ Storage::url($doc->path) }}" target="_blank"
                               class="shrink-0 inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                View
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Upload input --}}
        <div class="space-y-3">
            <label for="document_upload" class="text-sm font-bold text-foreground/80">
                {{ isset($record) && $record->exists ? 'Add More Documents' : 'Select Documents' }}
            </label>

            <div id="doc-drop-zone"
                 class="relative flex flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-border bg-secondary/10 px-6 py-10 text-center transition hover:border-primary/50 hover:bg-primary/5 cursor-pointer"
                 onclick="document.getElementById('document_upload').click()">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-muted-foreground/50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M12 12v9"/><path d="m8 17 4-5 4 5"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-foreground">Click to browse or drag &amp; drop files here</p>
                    <p class="text-xs text-muted-foreground mt-1">PDF, DOC, DOCX, JPG, JPEG, PNG — max 10 MB per file</p>
                </div>
                <input type="file" id="document_upload" name="documents[]" multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                       class="sr-only"
                       onchange="handleDocumentFiles(this.files)">
            </div>

            {{-- Preview list of selected files --}}
            <ul id="doc-preview-list" class="space-y-2 hidden"></ul>

            @error('documents')   <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            @error('documents.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<script>
  (function() {
    /* ── BMI calculator ── */
    const heightInput = document.getElementById('Height');
    const weightInput = document.getElementById('Weight');
    const bmiInput = document.getElementById('BMI');

    window.calculateBMI = function() {
      if (!heightInput || !weightInput || !bmiInput) return;
      const height = parseFloat(heightInput.value);
      const weight = parseFloat(weightInput.value);
      if (height > 0 && weight > 0) {
        const heightInMeters = height / 100;
        bmiInput.value = (weight / (heightInMeters * heightInMeters)).toFixed(2);
      } else {
        bmiInput.value = '';
      }
    };

    if (heightInput && weightInput) {
        heightInput.addEventListener('input', window.calculateBMI);
        weightInput.addEventListener('input', window.calculateBMI);
    }

    /* ── Company name sync & Employee ID Autogen ── */
    const companySelect = document.getElementById('company_id');
    const companyNameInput = document.getElementById('company_name');
    const employeeIdInput = document.getElementById('employee_id');
    const employeeIdLoading = document.getElementById('employee_id_loading');

    // Store the last auto-generated ID so we can safely overwrite it if the user changes the company
    // without manually editing the field.
    let lastGeneratedId = employeeIdInput ? employeeIdInput.value : '';

    if (companySelect) {
        companySelect.addEventListener('change', function() {
            if (companyNameInput && this.selectedIndex >= 0) {
                companyNameInput.value = this.options[this.selectedIndex].text;
            }

            const companyId = this.value;
            
            // Only auto-generate if the field is empty, or if it matches the last generated ID (meaning the user hasn't typed their own)
            if (companyId && employeeIdInput && (employeeIdInput.value === '' || employeeIdInput.value === lastGeneratedId)) {
                if (employeeIdLoading) employeeIdLoading.classList.remove('hidden');
                
                fetch(`/health-records/next-employee-id?company_id=${companyId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.next_id && (employeeIdInput.value === '' || employeeIdInput.value === lastGeneratedId)) {
                            employeeIdInput.value = data.next_id;
                            lastGeneratedId = data.next_id;
                        }
                    })
                    .catch(error => console.error('Error fetching next employee ID:', error))
                    .finally(() => {
                        if (employeeIdLoading) employeeIdLoading.classList.add('hidden');
                    });
            }
        });

        // Trigger on load if there's a selected company and the employee ID is empty
        if (companySelect.value && employeeIdInput && employeeIdInput.value === '') {
            companySelect.dispatchEvent(new Event('change'));
        }
    }

    /* ── Document drop zone ── */
    const zone = document.getElementById('doc-drop-zone');
    if (zone) {
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            zone.classList.add('border-primary', 'bg-primary/10');
        });
        zone.addEventListener('dragleave', function() {
            zone.classList.remove('border-primary', 'bg-primary/10');
        });
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            zone.classList.remove('border-primary', 'bg-primary/10');
            const input = document.getElementById('document_upload');
            const dt = new DataTransfer();
            if (input.files) Array.from(input.files).forEach(f => dt.items.add(f));
            Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
            input.files = dt.files;
            handleDocumentFiles(input.files);
        });
    }

    /* ── File preview list ── */
    window.handleDocumentFiles = function(files) {
        const list = document.getElementById('doc-preview-list');
        if (!list) return;
        list.innerHTML = '';
        if (!files || files.length === 0) { list.classList.add('hidden'); return; }
        list.classList.remove('hidden');
        const maxBytes = 10 * 1024 * 1024;
        Array.from(files).forEach(function(file) {
            const size = file.size >= 1048576
                ? (file.size / 1048576).toFixed(1) + ' MB'
                : (file.size / 1024).toFixed(1) + ' KB';
            const tooLarge = file.size > maxBytes;
            const li = document.createElement('li');
            li.className = 'flex items-center justify-between gap-3 rounded-xl px-4 py-2.5 text-sm ' +
                (tooLarge ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-secondary/20 border border-border text-foreground');
            li.innerHTML =
                '<div class="flex items-center gap-2 min-w-0">' +
                '<svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>' +
                '<span class="truncate font-medium">' + file.name + '</span>' +
                '</div>' +
                '<span class="shrink-0 text-xs font-semibold">' + size + (tooLarge ? ' — too large' : '') + '</span>';
            list.appendChild(li);
        });
    };
  })();
</script>
