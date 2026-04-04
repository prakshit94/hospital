@php
    $selectedRoles = old('roles', isset($user) ? $user->roles->pluck('id')->all() : []);
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(380px,0.9fr)]">
    <div class="space-y-6">
        <!-- Personal Information -->
        <x-ui.card class="space-y-6">
            <div class="border-b border-border/50 pb-4">
                <h2 class="text-lg font-bold text-foreground">Personal Profile</h2>
                <p class="text-sm text-muted-foreground">Basic identity information and avatar.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-12">
                <div class="ui-field sm:col-span-4">
                    <label for="first_name" class="ui-label">First Name</label>
                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" class="ui-input @error('first_name') !border-danger ring-2 ring-danger/10 @enderror" placeholder="e.g. Jane">
                    @error('first_name') <p class="mt-1 text-[10px] font-bold text-danger uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>
                <div class="ui-field sm:col-span-4">
                    <label for="middle_name" class="ui-label">Middle Name</label>
                    <input id="middle_name" name="middle_name" type="text" value="{{ old('middle_name', $user->middle_name) }}" class="ui-input" placeholder="Optional">
                </div>
                <div class="ui-field sm:col-span-4">
                    <label for="last_name" class="ui-label">Last Name</label>
                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" class="ui-input @error('last_name') !border-danger ring-2 ring-danger/10 @enderror" placeholder="e.g. Doe">
                    @error('last_name') <p class="mt-1 text-[10px] font-bold text-danger uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>

                <div class="ui-field sm:col-span-8">
                    <label for="name" class="ui-label">Display Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="ui-input @error('name') !border-danger ring-2 ring-danger/10 @enderror">
                    @error('name') <p class="mt-1 text-[10px] font-bold text-danger uppercase tracking-wider">{{ $message }}</p> @else <p class="ui-hint">Auto-generated from First and Last Name if left blank.</p> @enderror
                </div>

                <div class="ui-field sm:col-span-4">
                    <label for="gender" class="ui-label">Gender</label>
                    <select id="gender" name="gender" class="ui-select">
                        <option value="">Select...</option>
                        <option value="Male" @selected(old('gender', $user->gender) === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender', $user->gender) === 'Female')>Female</option>
                        <option value="Other" @selected(old('gender', $user->gender) === 'Other')>Other</option>
                    </select>
                </div>

                <div class="ui-field sm:col-span-12">
                    <label for="profile_image" class="ui-label">Profile Image <span class="text-muted-foreground font-normal">(Optional)</span></label>
                    <div class="flex items-center gap-4">
                        @if(isset($user) && $user->profile_image)
                            <img src="{{ $user->profile_image }}" alt="Avatar" class="size-12 rounded-full border border-border object-cover bg-secondary">
                        @else
                            <div class="flex size-12 items-center justify-center rounded-full bg-primary/10 text-primary font-bold shadow-sm ring-1 ring-primary/20">
                                {{ substr(old('first_name', $user->first_name ?: $user->name ?: 'U'), 0, 1) }}
                            </div>
                        @endif
                        <input id="profile_image" name="profile_image" type="file" accept="image/*" class="ui-input w-full">
                    </div>
                </div>
            </div>
        </x-ui.card>

        <!-- Organizational Details -->
        <x-ui.card class="space-y-6">
            <div class="border-b border-border/50 pb-4">
                <h2 class="text-lg font-bold text-foreground">Contact & Organization</h2>
                <p class="text-sm text-muted-foreground">Corporate directory layout and reachability.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="ui-field">
                    <label for="email" class="ui-label">Email Address <span class="text-danger">*</span></label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="ui-input @error('email') !border-danger ring-2 ring-danger/10 @enderror" required placeholder="name@domain.com">
                    @error('email') <p class="mt-1 text-[10px] font-bold text-danger uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>
                <div class="ui-field">
                    <label for="phone" class="ui-label">Phone Number</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" class="ui-input @error('phone') !border-danger ring-2 ring-danger/10 @enderror" placeholder="+1 (555) 000-0000">
                    @error('phone') <p class="mt-1 text-[10px] font-bold text-danger uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>
                <div class="ui-field">
                    <label for="department" class="ui-label">Department</label>
                    <input id="department" name="department" type="text" value="{{ old('department', $user->department) }}" class="ui-input" placeholder="e.g. Engineering">
                </div>
                <div class="ui-field">
                    <label for="job_title" class="ui-label">Job Title</label>
                    <input id="job_title" name="job_title" type="text" value="{{ old('job_title', $user->job_title) }}" class="ui-input" placeholder="e.g. Senior Developer">
                </div>
                <div class="ui-field sm:col-span-2">
                    <label for="location" class="ui-label">Location / City</label>
                    <input id="location" name="location" type="text" value="{{ old('location', $user->location) }}" class="ui-input" placeholder="e.g. New York, HQ">
                </div>
            </div>
        </x-ui.card>

        <!-- Security -->
        <x-ui.card class="space-y-6">
            <div class="border-b border-border/50 pb-4">
                <h2 class="text-lg font-bold text-foreground">Security & Access State</h2>
                <p class="text-sm text-muted-foreground">Manage password and overall account enablement.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="ui-field">
                    <label for="password" class="ui-label">{{ $user->exists ? 'New Password' : 'Password' }}</label>
                    <input id="password" name="password" type="password" class="ui-input @error('password') !border-danger ring-2 ring-danger/10 @enderror" {{ $user->exists ? '' : 'required' }}>
                    @error('password') <p class="mt-1 text-[10px] font-bold text-danger uppercase tracking-wider">{{ $message }}</p> @else <p class="ui-hint">{{ $user->exists ? 'Leave blank to keep existing password.' : 'Required for initial sign-in.' }}</p> @enderror
                </div>
                <div class="ui-field">
                    <label for="password_confirmation" class="ui-label">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="ui-input" {{ $user->exists ? '' : 'required' }}>
                </div>
                <div class="ui-field sm:col-span-2">
                    <label for="status" class="ui-label">Account Status</label>
                    <select id="status" name="status" class="ui-select">
                        <option value="active" @selected(old('status', $user->status ?? 'active') === 'active')>🟢 Active (Allowed to sign in)</option>
                        <option value="inactive" @selected(old('status', $user->status ?? 'active') === 'inactive')>🔴 Inactive (Suspended from systems)</option>
                    </select>
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Right Sidebar -->
    <div class="space-y-6">
        <x-ui.card class="space-y-5">
            <div class="border-b border-border/50 pb-4">
                <h2 class="text-lg font-bold text-foreground">Role Assignments</h2>
                <p class="text-sm text-muted-foreground">Select roles to automatically inherit permissions.</p>
            </div>

            <div class="space-y-4 max-h-[38rem] overflow-y-auto pr-2 custom-scrollbar">
                @error('roles') <p class="mb-4 text-[10px] font-bold text-danger uppercase tracking-wider p-3 bg-danger/5 rounded-lg border border-danger/20">{{ $message }}</p> @enderror
                @foreach($roles as $role)
                    <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border border-border bg-card p-4 transition-all hover:border-primary/50 hover:bg-primary/[0.02] [&:has(:checked)]:border-primary/50 [&:has(:checked)]:bg-primary/5 [&:has(:checked)]:shadow-sm">
                        <div class="pt-0.5">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="size-5 rounded border-input text-primary shadow-sm focus:ring-primary" @checked(in_array($role->id, $selectedRoles))>
                        </div>
                        <div class="flex-1 space-y-2">
                            <div>
                                <span class="block text-sm font-bold text-foreground">{{ $role->name }}</span>
                                <span class="block mt-0.5 text-xs text-muted-foreground leading-relaxed">{{ $role->description ?: 'System role' }}</span>
                            </div>
                            @if($role->permissions->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 pt-2 border-t border-border/50">
                                    @foreach($role->permissions->take(8) as $permission)
                                        <span class="inline-flex items-center rounded-md bg-secondary px-2 py-0.5 text-[10px] font-semibold text-secondary-foreground">{{ explode('.', $permission->slug)[0] ?? $permission->slug }}</span>
                                    @endforeach
                                    @if($role->permissions->count() > 8)
                                        <span class="inline-flex items-center rounded-md bg-muted px-2 py-0.5 text-[10px] font-semibold text-muted-foreground">+{{ $role->permissions->count() - 8 }} more</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card class="space-y-3 sticky top-6">
            <x-ui.button class="w-full justify-center shadow-sm">{{ $submitLabel }}</x-ui.button>
            @if($modalMode ?? false)
                <button type="button" data-modal-close class="inline-flex w-full items-center justify-center gap-2 rounded-[1.2rem] border border-border bg-secondary px-4 py-2.5 text-sm font-semibold text-foreground transition duration-300 active:scale-[0.98] hover:bg-accent focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                    Cancel
                </button>
            @else
                <x-ui.button variant="secondary" href="{{ route('users.index') }}" class="w-full justify-center">Cancel</x-ui.button>
            @endif
        </x-ui.card>
    </div>
</div>
