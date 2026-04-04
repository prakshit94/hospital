@extends('layouts.app')

@php
    $pageTitle = 'Create Permission';
    $submitLabel = 'Save Permission';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <span class="hero-kicker">Granular Access</span>
            <h1 class="hero-title">Create permission</h1>
            <p class="hero-copy">Add a new granular capability that roles can inherit across the platform.</p>
        </section>

        <form method="POST" action="{{ route('permissions.store') }}">
            @csrf
            @include('permissions._form')
        </form>
    </div>
@endsection
