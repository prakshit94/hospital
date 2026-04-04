@extends('layouts.app')

@php
    $pageTitle = 'Edit Permission';
    $submitLabel = 'Update Permission';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Edit Permission</h1>
                <p class="mt-2 text-sm text-muted-foreground">Adjust labels and grouping for {{ $permission->slug }}.</p>
            </div>
            <x-ui.button variant="ghost" href="{{ route('permissions.show', $permission) }}">View Permission</x-ui.button>
        </div>

        <form method="POST" action="{{ route('permissions.update', $permission) }}">
            @csrf
            @method('PUT')
            @include('permissions._form')
        </form>
    </div>
@endsection
