@extends('layouts.app')

@php
    $pageTitle = 'Edit Role';
    $submitLabel = 'Update Role';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="hero-kicker">Access Profiles</span>
                    <h1 class="hero-title">Edit role</h1>
                    <p class="hero-copy">Adjust permissions and metadata for {{ $role->name }}.</p>
                </div>
                <x-ui.button variant="ghost" href="{{ route('roles.show', $role) }}" data-modal-open>View Role</x-ui.button>
            </div>
        </section>

        <form method="POST" action="{{ route('roles.update', $role) }}">
            @csrf
            @method('PUT')
            @include('roles._form')
        </form>
    </div>
@endsection
