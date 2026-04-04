@extends('layouts.app')

@php
    $pageTitle = 'Create User';
    $submitLabel = 'Save User';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div>
            <h1 class="font-heading text-3xl font-black tracking-tight">Create User</h1>
            <p class="mt-2 text-sm text-muted-foreground">Provision a new account and assign one or more access roles.</p>
        </div>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('users._form')
        </form>
    </div>
@endsection
