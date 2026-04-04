@php
    $selectedRoles = old('roles', isset($user) ? $user->roles->pluck('id')->all() : []);
@endphp

<div class="grid gap-6 xl:grid-cols-3">
    <x-ui.card class="space-y-6 xl:col-span-2">
        <div>
            <h2 class="font-heading text-xl font-bold">Account Details</h2>
            <p class="mt-1 text-sm text-muted-foreground">Identity, login, and account state for this user.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
            </div>
            <div>
                <label for="email" class="mb-2 block text-sm font-semibold">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" required>
            </div>
            <div>
                <label for="password" class="mb-2 block text-sm font-semibold">{{ $user->exists ? 'New password' : 'Password' }}</label>
                <input id="password" name="password" type="password" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" {{ $user->exists ? '' : 'required' }}>
            </div>
            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-semibold">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20" {{ $user->exists ? '' : 'required' }}>
            </div>
            <div>
                <label for="status" class="mb-2 block text-sm font-semibold">Status</label>
                <select id="status" name="status" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    <option value="active" @selected(old('status', $user->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $user->status ?? 'active') === 'inactive')>Inactive</option>
                </select>
            </div>
        </div>
    </x-ui.card>

    <div class="space-y-6">
        <x-ui.card class="space-y-4">
            <div>
                <h2 class="font-heading text-xl font-bold">Roles</h2>
                <p class="mt-1 text-sm text-muted-foreground">Attach one or more roles to grant permissions through RBAC.</p>
            </div>

            <div class="space-y-3">
                @foreach($roles as $role)
                    <label class="flex items-start gap-3 rounded-2xl border border-border/60 bg-secondary/25 px-4 py-3">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="mt-1 h-4 w-4 rounded border-border text-primary focus:ring-primary" @checked(in_array($role->id, $selectedRoles))>
                        <span>
                            <span class="block text-sm font-semibold">{{ $role->name }}</span>
                            <span class="mt-1 block text-xs text-muted-foreground">{{ $role->description ?: 'No description provided.' }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </x-ui.card>

        <x-ui.card class="space-y-3">
            <x-ui.button class="w-full justify-center">{{ $submitLabel }}</x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('users.index') }}" class="w-full justify-center">Cancel</x-ui.button>
        </x-ui.card>
    </div>
</div>
