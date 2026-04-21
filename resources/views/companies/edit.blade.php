@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-foreground">Edit Company</h1>
            <p class="text-sm text-muted-foreground">Updating details for {{ $company->name }}.</p>
        </div>
        <a href="{{ route('companies.index') }}" class="text-sm font-medium text-muted-foreground hover:text-foreground">
            Cancel
        </a>
    </div>

    <form action="{{ route('companies.update', $company->id) }}" method="POST" class="rounded-[2.5rem] border border-border bg-card p-10 shadow-sm space-y-6">
        @csrf
        @method('PUT')
        
        @include('companies._form')

        <div class="flex items-center justify-between pt-6 border-t border-border">
            <button type="button" 
                    onclick="if(confirm('Are you sure you want to delete this company? This may affect linked health records.')) document.getElementById('delete-company-form').submit();"
                    class="text-sm font-bold text-red-500 hover:text-red-600 transition">
                Delete Company
            </button>
            <button type="submit" class="inline-flex h-12 items-center justify-center rounded-2xl bg-primary px-10 text-sm font-bold text-white shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                Update Company
            </button>
        </div>
    </form>

    <form id="delete-company-form" action="{{ route('companies.destroy', $company->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
