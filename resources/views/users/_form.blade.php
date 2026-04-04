@php
    $selectedRoles = old('roles', isset($user) ? $user->roles->pluck('id')->all() : []);
@endphp

<div class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.8fr)]">
    <x-ui.card class="space-y-6">
        <div>
            <div class="section-kicker">Account Details</div>
            <h2 class="section-title">Identity and access basics</h2>
            <p class="section-copy">Configure the core profile, sign-in information, and current account status for this user.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="ui-field">
                <label for="name" class="ui-label">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="ui-input" required>
            </div>
            <div class="ui-field">
                <label for="email" class="ui-label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="ui-input" required>
            </div>
            <div class="ui-field">
                <label for="password" class="ui-label">{{ $user->exists ? 'New Password' : 'Password' }}</label>
                <input id="password" name="password" type="password" class="ui-input" {{ $user->exists ? '' : 'required' }}>
                <p class="ui-hint">{{ $user->exists ? 'Leave blank to keep the current password.' : 'Use a strong password for the initial sign-in.' }}</p>
            </div>
            <div class="ui-field">
                <label for="password_confirmation" class="ui-label">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="ui-input" {{ $user->exists ? '' : 'required' }}>
            </div>
            <div class="ui-field md:col-span-2">
                <label for="status" class="ui-label">Status</label>
                <select id="status" name="status" class="ui-select">
                    <option value="active" @selected(old('status', $user->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $user->status ?? 'active') === 'inactive')>Inactive</option>
                </select>
            </div>
        </div>
    </x-ui.card>

    <div class="space-y-6">
        <x-ui.card class="space-y-5">
            <div>
                <div class="section-kicker">Role Assignment</div>
                <h2 class="section-title">Attach access profiles</h2>
                <p class="section-copy">Choose one or more roles to grant permissions through RBAC inheritance.</p>
            </div>

            <div class="space-y-3 max-h-[28rem] overflow-y-auto pr-1">
                @foreach($roles as $role)
                    <label class="ui-checkbox-card">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="mt-1 h-4 w-4" @checked(in_array($role->id, $selectedRoles))>
                        <span>
                            <span class="block text-sm font-semibold text-foreground">{{ $role->name }}</span>
                            <span class="mt-1 block text-xs text-muted-foreground">{{ $role->description ?: 'No description provided.' }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card class="space-y-3">
            <x-ui.button class="w-full justify-center">{{ $submitLabel }}</x-ui.button>
            @if($modalMode ?? false)
                <button type="button" data-modal-close class="inline-flex w-full items-center justify-center gap-2 rounded-[1.2rem] border border-border bg-secondary px-4 py-2.5 text-sm font-semibold text-foreground transition duration-300 active:scale-[0.98] hover:bg-accent">
                    Cancel
                </button>
            @else
                <x-ui.button variant="secondary" href="{{ route('users.index') }}" class="w-full justify-center">Cancel</x-ui.button>
            @endif
        </x-ui.card>
    </div>
</div>
