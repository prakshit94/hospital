@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-foreground">Companies</h1>
            <p class="text-sm text-muted-foreground">Manage companies for health records.</p>
        </div>
        <a href="{{ route('companies.create') }}" class="inline-flex h-10 items-center justify-center rounded-xl bg-primary px-4 text-sm font-medium text-white transition hover:bg-primary/90">
            Add Company
        </a>
    </div>

    <div class="rounded-[2rem] border border-border bg-card shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-border bg-secondary/30">
                <tr>
                    <th class="px-6 py-4 font-bold text-foreground">Company Name</th>
                    <th class="px-6 py-4 font-bold text-foreground">Code</th>
                    <th class="px-6 py-4 font-bold text-foreground">Contact</th>
                    <th class="px-6 py-4 font-bold text-foreground">Status</th>
                    <th class="px-6 py-4 font-bold text-foreground text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($companies as $company)
                    <tr class="transition hover:bg-secondary/20">
                        <td class="px-6 py-4">
                            <div class="font-bold text-foreground">{{ $company->name }}</div>
                            <div class="text-[10px] text-muted-foreground truncate max-w-xs">{{ $company->address }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded-lg bg-emerald-50 px-2 py-1 text-[10px] font-black text-emerald-600 uppercase tracking-wider">
                                {{ $company->code ?: 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-medium text-foreground">{{ $company->contact_person }}</div>
                            <div class="text-[10px] text-muted-foreground">{{ $company->contact_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[10px] font-bold {{ $company->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                <span class="size-1 rounded-full {{ $company->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                {{ $company->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('companies.edit', $company->id) }}" class="text-primary hover:underline font-bold">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-muted-foreground">No companies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="text-sm text-muted-foreground">
            Showing {{ $companies->firstItem() ?? 0 }} to {{ $companies->lastItem() ?? 0 }} of {{ $companies->total() }} records.
            Page {{ $companies->currentPage() }} of {{ $companies->lastPage() }}.
        </div>
        <nav class="flex items-center gap-2 pagination">
            @if ($companies->onFirstPage())
                <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground opacity-50 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                    Previous
                </span>
            @else
                <a href="{{ $companies->previousPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                    Previous
                </a>
            @endif

            @if ($companies->hasMorePages())
                <a href="{{ $companies->nextPageUrl() }}" class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground shadow-[0_14px_30px_-24px_rgba(15,23,42,0.18)] hover:bg-accent transition duration-300 active:scale-[0.98]">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center gap-2 rounded-[1.2rem] px-4 py-2 text-sm font-semibold border border-border bg-secondary text-foreground opacity-50 cursor-not-allowed">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                </span>
            @endif
        </nav>
    </div>
</div>
@endsection
