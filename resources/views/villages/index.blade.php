@extends('layouts.app')

@php
    $pageTitle = 'Village Management';
@endphp

@section('content')
    <div class="page-stack" x-data="{
        showModal: false,
        isEditing: false,
        currentVillage: null,
        formData: {
            village_name: '',
            pincode: '',
            taluka_name: '',
            district_name: '',
            state_name: '',
            is_serviceable: true,
            status: 'active'
        },
        openCreateModal() {
            this.isEditing = false;
            this.currentVillage = null;
            this.formData = {
                village_name: '',
                pincode: '',
                taluka_name: '',
                district_name: '',
                state_name: '',
                is_serviceable: true,
                status: 'active'
            };
            this.showModal = true;
        },
        openEditModal(village) {
            this.isEditing = true;
            this.currentVillage = village;
            this.formData = { ...village };
            this.showModal = true;
        },
        async submitForm() {
            const url = this.isEditing ? `{{ url('villages') }}/${this.currentVillage.uuid}` : `{{ route('villages.store') }}`;
            const method = this.isEditing ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });

                const result = await response.json();

                if (response.ok) {
                    this.showModal = false;
                    window.dispatchEvent(new CustomEvent('toast-notify', {
                        detail: { type: 'success', title: 'Success', description: result.message }
                    }));
                    // Reload the results
                    document.querySelector('form[data-async-search]').dispatchEvent(new Event('submit'));
                } else {
                    throw new Error(result.message || 'Validation failed');
                }
            } catch (error) {
                window.dispatchEvent(new CustomEvent('toast-notify', {
                    detail: { type: 'error', title: 'Error', description: error.message }
                }));
            }
        }
    }" @edit-village.window="openEditModal($event.detail)">
        
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="hero-kicker">CRM</span>
                    <h1 class="hero-title">Villages & Locations</h1>
                    <p class="hero-copy">Manage serviceable zones, pincodes, and geographic territories.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="openCreateModal()" class="ui-button-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14m-7-7v14"/></svg>
                        Add Village
                    </button>
                </div>
            </div>
        </section>

        <section class="data-shell">
            <div class="section-header">
                <div>
                    <div class="section-kicker">Master Data</div>
                    <h2 class="section-title">Directory</h2>
                    <p class="section-copy">Filter and search for specific locations.</p>
                </div>
            </div>

            <form method="GET" class="data-toolbar lg:grid-cols-[minmax(0,1.3fr)_repeat(2,minmax(0,0.7fr))]" data-async-search data-target="#villages-results" action="{{ route('villages.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search village, pincode, district..." class="ui-input">
                
                <select name="status" class="ui-select">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>

                <select name="per_page" class="ui-select">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>{{ $size }} per page</option>
                    @endforeach
                </select>
            </form>

            <div id="villages-results">
                @include('villages.partials.results')
            </div>
        </section>

        {{-- Modal for Create/Edit --}}
        <div x-show="showModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4 bg-slate-950/45 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div @click.away="showModal = false" class="relative w-full max-w-lg bg-popover rounded-[1.5rem] border border-border shadow-2xl animate-in fade-in zoom-in-95 duration-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-border bg-muted/30 flex items-center justify-between">
                    <h3 class="text-lg font-bold" x-text="isEditing ? 'Edit Village' : 'Add New Village'"></h3>
                    <button @click="showModal = false" class="text-muted-foreground hover:text-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-1.5">
                        <label class="ui-label">Village Name</label>
                        <input type="text" x-model="formData.village_name" class="ui-input" placeholder="e.g. Rampur">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="ui-label">Pincode</label>
                            <input type="text" x-model="formData.pincode" class="ui-input" placeholder="380001">
                        </div>
                        <div class="space-y-1.5">
                            <label class="ui-label">Taluka</label>
                            <input type="text" x-model="formData.taluka_name" class="ui-input" placeholder="Taluka">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="ui-label">District</label>
                            <input type="text" x-model="formData.district_name" class="ui-input" placeholder="District">
                        </div>
                        <div class="space-y-1.5">
                            <label class="ui-label">State</label>
                            <input type="text" x-model="formData.state_name" class="ui-input" placeholder="State">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 items-center pt-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" x-model="formData.is_serviceable" id="is_serviceable" class="ui-checkbox">
                            <label for="is_serviceable" class="text-sm font-medium">Serviceable</label>
                        </div>
                        <div class="flex items-center gap-2" x-show="isEditing">
                            <select x-model="formData.status" class="ui-select text-xs py-1">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-muted/30 border-t border-border flex justify-end gap-3">
                    <button @click="showModal = false" class="ui-button-secondary">Cancel</button>
                    <button @click="submitForm()" class="ui-button-primary" x-text="isEditing ? 'Save Changes' : 'Create Village'"></button>
                </div>
            </div>
        </div>
    </div>
@endsection
