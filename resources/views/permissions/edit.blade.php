@extends('layouts.app')

@php
    $pageTitle = 'Edit Permission';
    $submitLabel = 'Update Permission';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="hero-kicker">Granular Access</span>
                    <h1 class="hero-title">Edit permission</h1>
                    <p class="hero-copy">Adjust labels and grouping for {{ $permission->slug }}.</p>
                </div>
                <x-ui.button variant="ghost" href="{{ route('permissions.show', $permission) }}" data-modal-open>View Permission</x-ui.button>
            </div>
        </section>

        <form method="POST" action="{{ route('permissions.update', $permission) }}">
            @csrf
            @method('PUT')
            @include('permissions._form')
        </form>
    </div>
@endsection
