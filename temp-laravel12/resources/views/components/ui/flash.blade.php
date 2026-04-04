@if(session('status'))
    <div class="px-6 pt-6 lg:px-8">
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
            {{ session('status') }}
        </div>
    </div>
@endif

@if($errors->any())
    <div class="px-6 pt-6 lg:px-8">
        <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
            <div class="font-semibold">Please fix the following issues:</div>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
