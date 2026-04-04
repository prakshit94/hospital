<div class="table-toolbar">
    <div class="table-toolbar-copy">
        <div class="table-toolbar-title">User Directory</div>
        <p>Review account access, status, and role assignments in one place.</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <span class="table-toolbar-stat">{{ $users->total() }} total users</span>
        @if(auth()->user()?->hasPermission('users.create'))
            <x-ui.button href="{{ route('users.create') }}" data-modal-open>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>
                Add User
            </x-ui.button>
        @endif
    </div>
</div>

<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Status</th>
                <th>Last Login</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td data-label="Name">
                        <div>
                            <div class="table-primary">{{ $user->name }}</div>
                            <div class="mt-1 text-xs text-muted-foreground">User #{{ $user->id }}</div>
                        </div>
                    </td>
                    <td data-label="Email" class="table-secondary">{{ $user->email }}</td>
                    <td data-label="Roles">
                        <div class="flex flex-wrap gap-2 md:justify-end lg:justify-start">
                            @forelse($user->roles as $role)
                                <span class="ui-chip">{{ $role->name }}</span>
                            @empty
                                <span class="ui-chip-muted">No role</span>
                            @endforelse
                        </div>
                    </td>
                    <td data-label="Status">
                        <span class="{{ $user->status === 'active' ? 'ui-status-success' : 'ui-status-danger' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td data-label="Last Login" class="table-secondary">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                    <td data-label="Actions" class="actions-cell">
                        <div class="table-actions">
                            <x-ui.table-action href="{{ route('users.show', $user) }}" label="View" data-modal-open>
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2.06 12.35a1 1 0 0 1 0-.7C3.42 8.6 6.47 6 12 6s8.58 2.6 9.94 5.65a1 1 0 0 1 0 .7C20.58 15.4 17.53 18 12 18s-8.58-2.6-9.94-5.65Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </x-ui.table-action>
                            @if(auth()->user()?->hasPermission('users.update'))
                                <x-ui.table-action href="{{ route('users.edit', $user) }}" label="Edit" tone="primary" data-modal-open>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 20h9"/>
                                        <path d="m16.5 3.5 4 4L7 21H3v-4z"/>
                                    </svg>
                                </x-ui.table-action>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">No users matched the current filters.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="text-sm text-muted-foreground">
        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} records.
        Page {{ $users->currentPage() }} of {{ $users->lastPage() }}.
    </div>
    <div>{{ $users->links() }}</div>
</div>
