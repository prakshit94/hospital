@extends('layouts.app')

@php
    $pageTitle = 'Create Record';
@endphp

@section('content')
    <div class="space-y-6 p-6 lg:p-8">
        <div>
            <h1 class="font-heading text-3xl font-black tracking-tight">Create Record</h1>
            <p class="mt-2 text-sm text-muted-foreground">Reusable two-column form layout for products, users, workspaces, settings, or profile screens.</p>
        </div>

        <form class="grid gap-6 xl:grid-cols-3">
            <x-ui.card class="xl:col-span-2 space-y-6">
                <div>
                    <h2 class="font-heading text-xl font-bold">Primary Details</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Use this section for the main business fields.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Name</label>
                        <input type="text" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Code</label>
                        <input type="text" class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Category</label>
                        <select class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                            <option>Choose one</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Status</label>
                        <select class="block w-full rounded-2xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20">
                            <option>Active</option>
                            <option>Draft</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Description</label>
                    <textarea rows="6" class="block w-full rounded-3xl border border-border/70 bg-secondary/35 px-4 py-3 text-sm outline-none transition focus:border-primary/40 focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
            </x-ui.card>

            <div class="space-y-6">
                <x-ui.card class="space-y-4">
                    <div>
                        <h2 class="font-heading text-xl font-bold">Publishing</h2>
                        <p class="mt-1 text-sm text-muted-foreground">Quick actions and secondary metadata.</p>
                    </div>

                    <label class="flex items-center justify-between rounded-2xl border border-border/60 bg-secondary/30 px-4 py-3">
                        <span>
                            <span class="block text-sm font-semibold">Feature this record</span>
                            <span class="block text-xs text-muted-foreground">Highlight it in dashboards or lists.</span>
                        </span>
                        <input type="checkbox" class="h-4 w-4 rounded border-border text-primary focus:ring-primary">
                    </label>

                    <div class="space-y-3">
                        <x-ui.button class="w-full justify-center">Save Record</x-ui.button>
                        <x-ui.button variant="secondary" class="w-full justify-center">Save Draft</x-ui.button>
                    </div>
                </x-ui.card>

                <x-ui.card class="space-y-4">
                    <h2 class="font-heading text-xl font-bold">Notes</h2>
                    <p class="text-sm text-muted-foreground">Perfect for helper copy, validation hints, or auditing notes.</p>
                </x-ui.card>
            </div>
        </form>
    </div>
@endsection

