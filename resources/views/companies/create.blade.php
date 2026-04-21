@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-foreground">Add New Company</h1>
            <p class="text-sm text-muted-foreground">Register a new company for health records.</p>
        </div>
        <a href="{{ route('companies.index') }}" class="text-sm font-medium text-muted-foreground hover:text-foreground">
            Cancel
        </a>
    </div>

    <form action="{{ route('companies.store') }}" method="POST" class="rounded-[2.5rem] border border-border bg-card p-10 shadow-sm space-y-6">
        @csrf
        
        @include('companies._form', ['company' => new \App\Models\Company()])

        <div class="flex items-center justify-end pt-6 border-t border-border">
            <button type="submit" class="inline-flex h-12 items-center justify-center rounded-2xl bg-primary px-10 text-sm font-bold text-white shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                Save Company
            </button>
        </div>
    </form>
</div>
@endsection
