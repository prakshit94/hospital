@extends('layouts.auth')

@section('content')
<div class="space-y-6">
    <div class="space-y-2 text-center">
        <h1 class="text-2xl font-bold tracking-tight">Two-factor Authentication</h1>
        <p class="text-sm text-muted-foreground">
            Please enter the authentication code from your device.
        </p>
    </div>

    <form method="POST" action="{{ route('two-factor.login.store') }}" class="space-y-4">
        @csrf

        <div class="space-y-2">
            <x-ui.label for="code">Authentication Code</x-ui.label>
            <x-ui.input 
                id="code" 
                name="code" 
                type="text" 
                inputmode="numeric" 
                pattern="[0-9]*" 
                autocomplete="one-time-code" 
                required 
                autofocus 
                class="text-center text-2xl tracking-[1em]"
            />
            <x-ui.input-error :messages="$errors->get('code')" />
        </div>

        <x-ui.button type="submit" class="w-full">
            Verify Code
        </x-ui.button>
    </form>

    <div class="text-center">
        <a href="{{ route('login') }}" class="text-sm text-primary hover:underline">
            Back to login
        </a>
    </div>
</div>
@endsection
