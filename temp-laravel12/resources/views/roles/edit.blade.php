@extends('layouts.app')

@php
    $pageTitle = 'Edit Role';
    $submitLabel = 'Update Role';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="font-heading text-3xl font-black tracking-tight">Edit Role</h1>
                <p class="mt-2 text-sm text-muted-foreground">Adjust permissions and metadata for {{ $role->name }}.</p>
            </div>
            <x-ui.button variant="ghost" href="{{ route('roles.show', $role) }}">View Role</x-ui.button>
        </div>

        <form method="POST" action="{{ route('roles.update', $role) }}">
            @csrf
            @method('PUT')
            @include('roles._form')
        </form>
    </div>
@endsection
