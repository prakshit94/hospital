@extends('layouts.app')

@php
    $pageTitle = 'Edit User';
    $submitLabel = 'Update User';
@endphp

@section('content')
    <div class="page-stack">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="hero-kicker">User Maintenance</span>
                    <h1 class="hero-title">Edit user</h1>
                    <p class="hero-copy">Update identity details, status, and assigned roles for {{ $user->email }}.</p>
                </div>
                <x-ui.button variant="ghost" href="{{ route('users.show', $user) }}" data-modal-open>View Profile</x-ui.button>
            </div>
        </section>

        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('users._form')
        </form>
    </div>
@endsection
