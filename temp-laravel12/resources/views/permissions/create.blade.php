@extends('layouts.app')

@php
    $pageTitle = 'Create Permission';
    $submitLabel = 'Save Permission';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div>
            <h1 class="font-heading text-3xl font-black tracking-tight">Create Permission</h1>
            <p class="mt-2 text-sm text-muted-foreground">Add a new granular capability that roles can inherit.</p>
        </div>

        <form method="POST" action="{{ route('permissions.store') }}">
            @csrf
            @include('permissions._form')
        </form>
    </div>
@endsection
