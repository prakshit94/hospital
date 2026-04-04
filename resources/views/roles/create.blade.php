@extends('layouts.app')

@php
    $pageTitle = 'Create Role';
    $submitLabel = 'Save Role';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <span class="hero-kicker">Access Profiles</span>
            <h1 class="hero-title">Create role</h1>
            <p class="hero-copy">Bundle permissions into a reusable access profile with the upgraded role builder.</p>
        </section>

        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            @include('roles._form')
        </form>
    </div>
@endsection
