@extends('layouts.app')

@php
    $pageTitle = 'Create Role';
    $submitLabel = 'Save Role';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div>
            <h1 class="font-heading text-3xl font-black tracking-tight">Create Role</h1>
            <p class="mt-2 text-sm text-muted-foreground">Bundle permissions into a reusable access profile.</p>
        </div>

        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            @include('roles._form')
        </form>
    </div>
@endsection
