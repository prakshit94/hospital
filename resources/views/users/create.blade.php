@extends('layouts.app')

@php
    $pageTitle = 'Create User';
    $submitLabel = 'Save User';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <span class="hero-kicker">Provision Access</span>
            <h1 class="hero-title">Create a new user</h1>
            <p class="hero-copy">Provision a new account and assign one or more access roles with the upgraded responsive form experience.</p>
        </section>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('users._form')
        </form>
    </div>
@endsection
