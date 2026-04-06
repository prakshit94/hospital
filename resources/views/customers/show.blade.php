@extends('layouts.app')

@php
    $pageTitle = 'Customer Profile';
    $user = auth()->user();
    $hideSidebar = true;
@endphp

@push('styles')
<style>
    .premium-scrollbar::-webkit-scrollbar { width: 4px; }
    .premium-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.05); border-radius: 10px; }
    .premium-scrollbar::-webkit-scrollbar-thumb { background: rgba(16,185,129,0.2); border-radius: 10px; transition: all 0.3s; }
    .premium-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(16,185,129,0.5); }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .emerald-pulse {
        text-shadow: 0 0 15px rgba(16,185,129,0.4);
    }
    
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
    <div class="page-stack" x-data="{
        activeTab: 'overview',
        showAddressModal: false,
        isEditingAddress: false,
        currentAddress: null,
        addressForm: {
            address_line1: '', address_line2: '', label: '',
            type: 'shipping', contact_name: '', contact_phone: '',
            village: '', taluka: '', district: '', state: '', 
            country: 'India', pincode: '', post_office: '', is_default: false
        },
        openAddressModal(address = null) {
            if (address) {
                this.isEditingAddress = true;
                this.currentAddress = address;
                this.addressForm = { ...address };
            } else {
                this.isEditingAddress = false;
                this.currentAddress = null;
                this.addressForm = {
                    address_line1: '', address_line2: '', label: 'Farm',
                    type: 'shipping', 
                    village: '', taluka: '', district: '', state: '',
                    country: 'India', pincode: '', post_office: '',
                    contact_name: '{{ $customer->display_name }}', contact_phone: '{{ $customer->mobile }}',
                    is_default: false
                };
            }
            this.showAddressModal = true;
        },
        async submitAddress() {
            const url = this.isEditingAddress 
                ? `{{ url('customer-addresses') }}/${this.currentAddress.id}`
                : `{{ route('customers.addresses.store', $customer->uuid) }}`;
            
            const method = this.isEditingAddress ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        ...this.addressForm,
                        _method: method
                    })
                });

                const data = await response.json();
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error saving address');
                }
            } catch (e) { console.error(e); }
        },
        async deleteAddress(addressId) {
            if (!confirm('Are you sure you want to delete this address?')) return;

            try {
                const response = await fetch(`{{ url('customer-addresses') }}/${addressId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    const data = await response.json();
                    alert(data.message || 'Error deleting address');
                }
            } catch (e) { console.error(e); }
        },

        {{-- 🛒 Order Hub & Cart Orchestration --}}
        catalogQuery: '',
        catalogResults: [],
        isSearchingCatalog: false,
        cartItems: [],
        showCart: false,

        async searchCatalog() {
            if (this.catalogQuery.length < 2) { this.catalogResults = []; return; }
            this.isSearchingCatalog = true;
            try {
                const r = await fetch(`{{ route('products.search') }}?q=${this.catalogQuery}`);
                this.catalogResults = await r.json();
            } finally { this.isSearchingCatalog = false; }
        },

        addToCart(product) {
            const existing = this.cartItems.find(item => item.id === product.id);
            if (existing) {
                existing.quantity++;
            } else {
                this.cartItems.push({
                    id: product.id,
                    name: product.name,
                    sku: product.sku,
                    price: product.base_price || 0,
                    quantity: 1,
                    image: product.image_url || null
                });
            }
            this.showCart = true;
        },

        removeFromCart(sku) {
            this.cartItems = this.cartItems.filter(item => item.sku !== sku);
        },

        updateQty(sku, delta) {
            const item = this.cartItems.find(i => i.sku === sku);
            if (item) {
                item.quantity = Math.max(1, item.quantity + delta);
            }
        },

        cartTotal() {
            return this.cartItems.reduce((acc, item) => acc + (item.price * item.quantity), 0);
        },

        async placeOrder() {
            if (this.cartItems.length === 0) return;
            // Future Order Logic...
            alert('Initiating Order Flow for ' + this.cartItems.length + ' items...');
        },

        mockProducts: [
            { id: 1, name: 'Premium Ultra-Grow NPK', sku: 'NPK-1001', base_price: 1250.00, image_url: 'https://images.unsplash.com/photo-1542332606-b3d27038670b?q=80&w=100&h=100&fit=crop' },
            { id: 2, name: 'Bio-Elite Organic Pesticide', sku: 'BIO-2022', base_price: 855.00, image_url: 'https://images.unsplash.com/photo-1590779033100-9f60705a2f3b?q=80&w=100&h=100&fit=crop' },
            { id: 3, name: 'Precision Drip Irrigation Hub', sku: 'IRR-3033', base_price: 4500.00, image_url: 'https://images.unsplash.com/photo-1563206767-5b18f218e0de?q=80&w=100&h=100&fit=crop' },
            { id: 4, name: 'Genetic-Gold Tomato Seeds', sku: 'SEED-4044', base_price: 220.00, image_url: 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?q=80&w=100&h=100&fit=crop' }
        ],

        showInteractionModal: false,
        interactionForm: {
            interaction_type: 'Call',
            notes: '',
            next_follow_up: ''
        },

        async submitInteraction() {
            if (!this.interactionForm.interaction_type) return;
            try {
                const r = await fetch(`{{ route('customers.interactions.store', $customer->uuid) }}`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(this.interactionForm)
                });
                
                if (r.ok) {
                    const res = await r.json();
                    if (res.redirect) {
                        window.location.href = res.redirect;
                    } else {
                        window.location.href = '{{ route('dashboard') }}';
                    }
                } else {
                    // Fallback to manual redirect if something fails but we want to close
                    window.location.href = '{{ route('dashboard') }}';
                }
            } catch (e) {
                window.location.href = '{{ route('dashboard') }}';
            }
        },

        openInteractionModal() {
            this.showInteractionModal = true;
        }
    }">
        <!-- 💠 THEME HERO PANEL -->
        <section class="hero-panel">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="size-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 shadow-sm transition-transform hover:scale-105">
                         <span class="text-xl font-bold italic">{{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                             <span class="hero-kicker">Customer</span>
                             <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-500 font-bold text-[9px] uppercase tracking-wider">{{ $customer->customer_code }}</span>
                        </div>
                        <h1 class="hero-title !text-3xl">{{ $customer->display_name }}</h1>
                        <div class="flex flex-wrap gap-4 mt-2">
                             <span class="flex items-center gap-1.5 text-xs text-muted-foreground transition-colors hover:text-emerald-600"><svg class="size-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2"/></svg>{{ $customer->mobile }}</span>
                             <span class="flex items-center gap-1.5 text-xs text-muted-foreground transition-colors hover:text-emerald-600"><svg class="size-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2"/></svg>{{ $customer->email ?: 'No Email' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <x-ui.button variant="secondary" href="{{ route('customers.edit', $customer->uuid) }}" data-modal-open class="h-10 px-6 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm bg-white border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors">
                         Edit Profile
                    </x-ui.button>
                    <x-ui.button variant="secondary" @click="openInteractionModal()" class="h-10 px-6 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm bg-white border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors">
                         Tag & Close Profile
                    </x-ui.button>
                    <x-ui.button variant="primary" @click="activeTab = 'products'" class="h-10 px-8 rounded-lg bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider shadow-md hover:bg-emerald-700 transition-colors">
                         New Order
                    </x-ui.button>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-4 border-t border-slate-100 pt-8">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 shadow-sm group hover:border-emerald-500/20 transition-all">
                     <div class="text-[9px] font-bold uppercase tracking-widest text-muted-foreground mb-1 italic">Lifetime Purchases</div>
                     <div class="text-xl font-bold text-foreground">₹{{ number_format($customer->lifetime_value ?? 0, 2) }}</div>
                     <div class="mt-1 text-[8px] text-emerald-600 font-bold uppercase">{{ $customer->orders_count ?? 0 }} Orders Processed</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 shadow-sm group hover:border-emerald-500/20 transition-all">
                     <div class="text-[9px] font-bold uppercase tracking-widest text-muted-foreground mb-1 italic">Outstanding Exposure</div>
                     <div class="text-xl font-bold text-rose-600">₹{{ number_format($customer->outstanding_balance, 2) }}</div>
                     <div class="mt-1 text-[8px] text-rose-500 font-bold uppercase">Immediate Settlement Req</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 shadow-sm group hover:border-emerald-500/20 transition-all">
                     <div class="text-[9px] font-bold uppercase tracking-widest text-muted-foreground mb-1 italic">Primary Hub</div>
                     <div class="text-lg font-bold text-foreground truncate uppercase">{{ $customer->primaryAddress->village->village_name ?? 'Not Assigned' }}</div>
                     <div class="mt-1 text-[8px] text-muted-foreground font-bold uppercase">{{ $customer->primaryAddress->pincode ?? '---' }} Region</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 shadow-sm group hover:border-emerald-500/20 transition-all text-center">
                     <div class="text-[9px] font-bold uppercase tracking-widest text-amber-600 mb-1 italic">Portfolio Assets</div>
                     <div class="text-xl font-bold text-foreground">{{ $customer->land_area ?: '0.00' }} <span class="text-[10px] uppercase">{{ $customer->land_unit ?: 'Acres' }}</span></div>
                </div>
            </div>

            <div class="mt-8 flex gap-1.5 p-1 bg-slate-100 border border-slate-200 rounded-xl w-fit">
                @foreach([
                    'overview' => 'Overview',
                    'products' => 'New Order',
                    'loyalty' => 'Points',
                    'history' => 'Activity'
                ] as $tid => $label)
                    <button @click="activeTab = '{{ $tid }}'" 
                            :class="activeTab === '{{ $tid }}' ? 'bg-white text-emerald-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700'"
                            class="px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all border border-transparent">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </section>
        {{-- 🔷 CONTENT AREA --}}
        <div class="mt-12">
            {{-- Tab 1: Overview --}}
            <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 xl:grid-cols-[1fr_350px] gap-8">
                <div class="space-y-8">
                    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 group hover:border-emerald-500/30 transition-all">
                             <div class="text-[9px] font-bold uppercase tracking-wider text-muted-foreground mb-3">Customer Balance</div>
                             <div class="text-2xl font-bold text-foreground">₹{{ number_format($customer->lifetime_value ?? 0, 2) }}</div>
                             <div class="mt-2 text-[10px] font-medium text-emerald-600">{{ $customer->orders_count ?? 0 }} Total Orders</div>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 group hover:border-emerald-500/30 transition-all">
                             <div class="text-[9px] font-bold uppercase tracking-wider text-muted-foreground mb-3">Assigned Territory</div>
                             <div class="text-xl font-bold text-foreground truncate">{{ $customer->primaryAddress->village->village_name ?? ($customer->primaryAddress->village ?? 'Not Assigned') }}</div>
                             <div class="mt-2 text-[10px] font-medium text-slate-500">{{ $customer->primaryAddress->district ?? '---' }} Region</div>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 group hover:border-emerald-500/30 transition-all">
                             <div class="text-[9px] font-bold uppercase tracking-wider text-muted-foreground mb-3">Portfolio Size</div>
                             <div class="text-2xl font-bold text-foreground">{{ $customer->land_area ?: '0.00' }} <span class="text-xs text-muted-foreground">{{ $customer->land_unit ?: 'Acres' }}</span></div>
                             <div class="mt-2 text-[10px] font-medium text-slate-500 italic">Managed Agriculture Assets</div>
                        </div>
                    </section>

                    @if($lastInteraction)
                        <section class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 mb-10">
                             <div class="flex items-center justify-between mb-4">
                                  <div class="flex items-center gap-3">
                                       <div class="size-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></div>
                                       <h4 class="text-xs font-bold uppercase tracking-wider text-foreground">Most Recent Interaction</h4>
                                  </div>
                                  <span class="text-[10px] font-bold text-emerald-600 uppercase">{{ $lastInteraction->created_at->diffForHumans() }}</span>
                             </div>
                             <div class="space-y-3">
                                  <div class="flex items-center gap-2">
                                       <span class="px-2 py-0.5 rounded bg-white border border-emerald-200 text-emerald-700 text-[9px] font-bold uppercase">{{ $lastInteraction->properties['interaction_type'] ?? 'Call' }}</span>
                                       <span class="text-[10px] text-emerald-600/70 italic">By {{ $lastInteraction->causer->name ?? 'System' }}</span>
                                  </div>
                                  <p class="text-xs text-slate-700 font-medium leading-relaxed">{{ $lastInteraction->properties['notes'] ?: 'No summarized notes provided.' }}</p>
                             </div>
                        </section>
                    @endif

                    {{-- 👤 CUSTOMER PROFILE INFO --}}
                    <section class="p-8 rounded-2xl bg-white border border-slate-200 shadow-sm mb-10">
                        <div class="flex items-center gap-3 mb-8">
                             <div class="size-8 rounded-lg bg-slate-900 flex items-center justify-center text-white shadow-md"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                             <h3 class="text-sm font-bold uppercase tracking-wider text-foreground">Customer Profile Details</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                             {{-- Col 1: Identity --}}
                             <div class="space-y-6">
                                 <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Identity & Tax</div>
                                 <div class="grid grid-cols-2 gap-6">
                                     <div>
                                         <div class="text-[9px] font-bold text-muted-foreground uppercase mb-1">PAN Number</div>
                                         <div class="text-xs font-bold text-foreground">{{ $customer->pan_number ?: '---' }}</div>
                                     </div>
                                     <div>
                                         <div class="text-[9px] font-bold text-muted-foreground uppercase mb-1">Aadhaar (Last 4)</div>
                                         <div class="text-xs font-bold text-foreground">{{ $customer->aadhaar_last4 ?: '---' }}</div>
                                     </div>
                                     <div class="col-span-2">
                                         <div class="text-[9px] font-bold text-muted-foreground uppercase mb-1">GST Registration</div>
                                         <div class="text-xs font-bold text-foreground">{{ $customer->gst_number ?: 'Not Registered' }}</div>
                                     </div>
                                 </div>
                             </div>

                             {{-- Col 2: Agricultural --}}
                             <div class="space-y-6">
                                 <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Agriculture Profile</div>
                                 <div>
                                     <div class="text-[9px] font-bold text-muted-foreground uppercase mb-2">Primary Crops</div>
                                     <div class="flex flex-wrap gap-2">
                                         @forelse($customer->crops ?? [] as $crop)
                                             <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-[9px] font-bold uppercase border border-emerald-100">{{ $crop }}</span>
                                         @empty
                                             <span class="text-[10px] font-medium text-slate-400 italic">No crops listed</span>
                                         @endforelse
                                     </div>
                                 </div>
                                 <div class="grid grid-cols-2 gap-6">
                                     <div>
                                         <div class="text-[9px] font-bold text-muted-foreground uppercase mb-1">Land Area</div>
                                         <div class="text-xs font-bold text-foreground">{{ $customer->land_area ?: '0.00' }} {{ strtoupper($customer->land_unit ?: 'Acres') }}</div>
                                     </div>
                                     <div>
                                         <div class="text-[9px] font-bold text-muted-foreground uppercase mb-1">Lead Status</div>
                                         <div class="text-xs font-bold text-emerald-600 uppercase">{{ $customer->lead_status ?: 'Active' }}</div>
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </section>

                    <section class="data-shell">
                        <div class="section-header !mb-6">
                            <div>
                                <div class="section-kicker">Addresses</div>
                                <h3 class="section-title !text-lg">Customer Shipping & Billing Addresses</h3>
                                <p class="section-copy">Manage all registered locations for this customer.</p>
                            </div>
                            <x-ui.button variant="primary" @click="openAddressModal()" class="h-10 px-6 rounded-lg text-xs font-bold uppercase tracking-wider">
                                Add Address
                            </x-ui.button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($customer->addresses as $address)
                                <div class="relative group p-6 rounded-2xl border {{ $address->is_default ? 'border-emerald-500 bg-emerald-50/20' : 'border-border bg-white shadow-sm' }} transition-all">
                                    @if($address->is_default) <span class="absolute -top-3 left-6 px-3 py-1 rounded-full bg-emerald-600 text-[8px] font-black uppercase text-white shadow-lg tracking-widest italic">Default Address</span> @endif
                                    
                                    <div class="flex justify-between items-start mb-4">
                                         <div>
                                              <span class="text-[9px] font-black uppercase text-muted-foreground bg-secondary/10 px-2.5 py-1 rounded-lg border border-border/50">{{ $address->label ?: 'UNLABELED' }} • {{ strtoupper($address->type) }}</span>
                                         </div>
                                         <div class="flex gap-2">
                                              <button @click="openAddressModal({{ json_encode($address) }})" class="size-8 rounded-lg bg-white border border-border shadow-sm flex items-center justify-center hover:text-emerald-600 text-muted-foreground transition-colors"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                              @if(!$address->is_default)
                                                  <button @click="deleteAddress({{ $address->id }})" class="size-8 rounded-lg bg-white border border-border shadow-sm flex items-center justify-center hover:text-rose-600 text-muted-foreground transition-colors"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                              @endif
                                         </div>
                                    </div>

                                    <div class="space-y-2">
                                         <div class="text-sm font-bold text-foreground underline decoration-emerald-500/30 underline-offset-4">
                                              {{ $address->address_line1 }}
                                              @if($address->address_line2)
                                                   <span class="text-muted-foreground">, {{ $address->address_line2 }}</span>
                                              @endif
                                         </div>
                                         <div class="text-[11px] font-bold text-foreground tracking-tight leading-relaxed">
                                              <span class="uppercase">{{ $address->village->village_name ?? ($address->village ?? 'VILLAGE UNSET') }}</span>, 
                                              <span class="uppercase">{{ $address->taluka }}</span>, 
                                              <span class="uppercase">{{ $address->district }}</span> - 
                                              <span>{{ $address->pincode ?? '---' }}</span>
                                         </div>
                                         @if($address->post_office)
                                              <div class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest flex items-center gap-1.5 italic">
                                                   <span class="size-1 rounded-full bg-emerald-500"></span> Post Office: {{ $address->post_office }}
                                              </div>
                                         @endif
                                         <div class="flex items-center gap-4 pt-3 border-t border-border/40 mt-2">
                                              <div class="flex items-center gap-1.5 text-[9px] font-bold text-foreground italic"><svg class="size-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4" stroke-width="2"/></svg>{{ $address->contact_name }}</div>
                                              <div class="flex items-center gap-1.5 text-[9px] font-bold text-foreground italic"><svg class="size-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2"/></svg>{{ $address->contact_phone }}</div>
                                         </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 py-16 flex flex-col items-center justify-center bg-secondary/5 border-2 border-dashed border-border/60 rounded-2xl text-center">
                                     <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest italic">No logistical data registered</p>
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>

                <div class="space-y-8">
                     <aside class="sticky top-8 space-y-8 text-[10px]">
                         {{-- Financial DNA --}}
                         <div class="p-6 rounded-2xl bg-slate-900 border border-white/5 relative overflow-hidden shadow-xl shadow-slate-950">
                             <div class="absolute -top-12 -right-12 size-32 rounded-full bg-emerald-500/[0.03] blur-[80px]"></div>
                             <div class="relative z-10">
                                 <div class="flex items-center justify-between mb-6">
                                     <h4 class="text-[9px] font-bold text-emerald-600 tracking-widest uppercase">Billing Info</h4>
                                     <span class="animate-pulse size-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                                 </div>
                                 
                                 <div class="space-y-6">
                                     <div>
                                         <div class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-2 italic">Total Exposure</div>
                                         <div class="text-3xl font-black text-white italic tracking-tighter emerald-pulse">₹{{ number_format($customer->outstanding_balance, 2) }}</div>
                                     </div>

                                     <div class="grid grid-cols-1 gap-4">
                                         <div class="p-4 rounded-xl bg-white/[0.03] border border-white/5 group hover:border-emerald-500/20 transition-all">
                                             <div class="flex items-center justify-between mb-2 text-[8px] font-black uppercase text-slate-500 tracking-widest italic">
                                                 <span>Approved Credit</span>
                                                 <span class="text-emerald-500">{{ number_format($customer->credit_limit > 0 ? ($customer->outstanding_balance / $customer->credit_limit * 100) : 0, 1) }}% Utilized</span>
                                             </div>
                                             <div class="text-lg font-black text-white italic tracking-tighter">₹{{ number_format($customer->credit_limit, 2) }}</div>
                                             <div class="mt-3 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                                                 <div class="h-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]" style="width: {{ min(100, $customer->credit_limit > 0 ? ($customer->outstanding_balance / $customer->credit_limit * 100) : 0) }}%"></div>
                                             </div>
                                         </div>
                                         
                                         <div class="p-4 rounded-xl bg-rose-500/[0.03] border border-rose-500/10 group hover:border-rose-500/30 transition-all">
                                             <div class="text-[8px] font-black uppercase text-rose-500/60 mb-2 tracking-widest italic">Severe Overdue</div>
                                             <div class="text-lg font-black text-rose-500 italic tracking-tighter">₹{{ number_format($customer->overdue_amount, 2) }}</div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         {{-- Assignment & Stewardship --}}
                         <div class="p-8 rounded-2xl bg-secondary/5 border border-border/40 backdrop-blur-sm">
                              <div class="text-[9px] font-bold uppercase tracking-wider text-muted-foreground mb-8 flex items-center gap-3">
                                  Account Agent <span class="h-px flex-1 bg-border/40"></span>
                              </div>
                              <div class="flex flex-col items-center text-center p-6 rounded-2xl bg-white border border-border/60 shadow-xl shadow-secondary/10">
                                  <div class="size-16 rounded-2xl bg-slate-100 border-2 border-white shadow flex items-center justify-center text-emerald-600 text-xl font-bold mb-6">
                                      {{ substr($customer->assignedTo->name ?? 'S', 0, 1) }}
                                  </div>
                                  <h5 class="text-lg font-bold text-foreground mb-1">{{ $customer->assignedTo->name ?? 'Not Assigned' }}</h5>
                                  <p class="text-[10px] font-bold text-muted-foreground uppercase mb-6 italic">Assigned Representative</p>
                                  <button class="w-full py-3 rounded-lg bg-slate-900 text-white text-[10px] font-bold uppercase tracking-widest">Change Agent</button>
                              </div>
                          </div>
                     </aside>
                </div>
            </div>

            <div x-show="activeTab === 'products'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 xl:grid-cols-[1fr_380px] gap-8">
                <div class="space-y-8">
                    <section class="data-shell">
                        <div class="section-header !mb-6">
                            <div>
                                <div class="section-kicker">Order Products</div>
                                <h3 class="section-title !text-lg">Available Catalog</h3>
                                <p class="section-copy">Search and add products to the customer order.</p>
                            </div>
                            <div class="flex items-center gap-2.5 px-4 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-[9px] font-black italic uppercase tracking-widest">
                                 <span class="animate-pulse size-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span> Live Synchronization
                            </div>
                        </div>

                        <div class="relative group mb-8 transform-gpu">
                            <input type="text" x-model="catalogQuery" @input.debounce.300ms="searchCatalog()" class="w-full h-14 px-12 rounded-xl bg-secondary/5 border-2 border-border/40 focus:border-emerald-500/40 focus:ring-0 text-lg font-black italic tracking-tighter transition-all shadow-inner group-focus-within:bg-white" placeholder="Search by Product Name, SKU, or Category...">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground/30 group-focus-within:text-emerald-500 transition-colors"><svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                            <div x-show="isSearchingCatalog" class="absolute right-4 top-1/2 -translate-y-1/2"><span class="flex size-5 rounded-full border-2 border-emerald-500/20 border-t-emerald-500 animate-spin"></span></div>
                        </div>

                        {{-- Product Grid: High-Fidelity Rendering --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <template x-for="product in (catalogQuery ? catalogResults : mockProducts)" :key="product.id">
                                <div class="group p-5 rounded-2xl bg-white border border-border/60 hover:border-emerald-500/30 transition-all relative overflow-hidden hover:-translate-y-0.5">
                                     <div class="flex items-start gap-4">
                                          <div class="size-16 rounded-xl bg-secondary/5 flex items-center justify-center shrink-0 overflow-hidden border border-border/20">
                                               <img :src="product.image_url" class="size-full object-cover grayscale group-hover:grayscale-0 transition-all" alt="Product">
                                          </div>
                                          <div class="flex-1 min-w-0">
                                               <div class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1 italic" x-text="product.sku"></div>
                                               <h5 class="text-sm font-black text-foreground italic leading-tight truncate" x-text="product.name"></h5>
                                               <div class="mt-3 flex items-center justify-between">
                                                    <div class="text-base font-black italic text-foreground tracking-tighter" x-text="'₹' + Number(product.base_price).toLocaleString()"></div>
                                                    <button @click="addToCart(product)" class="size-10 rounded-xl bg-slate-900 text-white flex items-center justify-center hover:bg-emerald-500 shadow-md transition-all">
                                                         <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                    </button>
                                               </div>
                                          </div>
                                     </div>
                                </div>
                            </template>
                        </div>
                    </section>
                </div>

                {{-- Side Cart: Executive Review Stage --}}
                <div class="space-y-12">
                     <aside class="sticky top-12 space-y-12">
                         <div class="p-10 rounded-[3rem] bg-slate-900 border border-white/5 relative overflow-hidden shadow-2xl shadow-slate-950">
                             <div class="absolute -top-12 -left-12 size-40 rounded-full bg-emerald-500/[0.04] blur-[100px]"></div>
                             <div class="relative z-10">
                                 <div class="flex items-center justify-between mb-10">
                                      <h4 class="text-xs font-black italic text-white tracking-[0.3em] uppercase">Active Transaction</h4>
                                      <div class="text-[10px] font-black text-emerald-500 italic bg-emerald-500/10 px-4 py-1.5 rounded-full border border-emerald-500/20" x-text="cartItems.length + ' ITEM UNITS'"></div>
                                 </div>

                                 <div class="space-y-6 max-h-[500px] overflow-y-auto premium-scrollbar pr-4">
                                     <template x-for="item in cartItems" :key="item.sku">
                                         <div class="p-6 rounded-[2rem] bg-white/[0.03] border border-white/5 hover:border-emerald-500/20 transition-all group relative overflow-hidden">
                                              <div class="flex items-start gap-5 relative z-10">
                                                   <div class="size-16 rounded-2xl bg-white/5 flex items-center justify-center text-white shrink-0 overflow-hidden border border-white/10">
                                                        <img :src="item.image" class="size-full object-cover" alt="Item">
                                                   </div>
                                                   <div class="flex-1 min-w-0">
                                                        <div class="text-sm font-black italic text-white truncate mb-3" x-text="item.name"></div>
                                                        <div class="flex items-center justify-between text-[11px] font-black tracking-[0.1em] italic">
                                                             <div class="flex items-center gap-3 bg-white/5 p-1 rounded-xl border border-white/5">
                                                                 <button @click="updateQty(item.sku, -1)" class="size-7 rounded-lg bg-white/5 hover:bg-rose-500 text-white flex items-center justify-center transition-all">-</button>
                                                                 <span class="text-white min-w-[20px] text-center" x-text="item.quantity"></span>
                                                                 <button @click="updateQty(item.sku, 1)" class="size-7 rounded-lg bg-white/5 hover:bg-emerald-500 text-white flex items-center justify-center transition-all">+</button>
                                                             </div>
                                                             <div class="text-emerald-500 text-base" x-text="'₹' + (item.price * item.quantity).toLocaleString()"></div>
                                                        </div>
                                                   </div>
                                                   <button @click="removeFromCart(item.sku)" class="text-white/10 hover:text-rose-500 transition-colors p-1"><svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                              </div>
                                              <div class="absolute inset-0 bg-emerald-500/0 group-hover:bg-emerald-500/[0.02] transition-colors"></div>
                                         </div>
                                     </template>

                                     <template x-if="cartItems.length === 0">
                                         <div class="py-24 flex flex-col items-center justify-center text-center">
                                             <div class="size-20 rounded-full bg-white/5 border-2 border-dashed border-white/10 flex items-center justify-center text-white/5 mb-6"><svg class="size-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
                                             <p class="text-[11px] font-black uppercase text-white/10 tracking-[0.3em] italic">TRANSACTION BUFFER EMPTY</p>
                                         </div>
                                     </template>
                                 </div>

                                 <div x-show="cartItems.length > 0" class="mt-12 pt-10 border-t border-white/5 space-y-6">
                                      <div class="flex items-center justify-between text-[11px] font-black uppercase text-slate-500 tracking-[0.2em] italic">
                                           <span>Subtotal Value</span>
                                           <span class="text-white" x-text="'₹' + cartTotal().toLocaleString()"></span>
                                      </div>
                                      <div class="flex items-center justify-between text-3xl font-black italic text-emerald-500 tracking-tighter">
                                           <span>Grand Total</span>
                                           <span x-text="'₹' + cartTotal().toLocaleString()"></span>
                                      </div>
                                      <button @click="placeOrder()" class="w-full h-20 mt-8 rounded-[2rem] bg-emerald-500 text-[12px] font-black uppercase tracking-[0.3em] italic text-white shadow-[0_30px_60px_-10px_rgba(16,185,129,0.4)] hover:scale-[1.02] active:scale-95 transition-all">Authorize Order Flow</button>
                                 </div>
                             </div>
                         </div>
                     </aside>
                </div>
            </div>

            {{-- 💎 TAB 3: POINTS & REFERRAL --}}
            <div x-show="activeTab === 'loyalty'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <section class="data-shell">
                    <div class="section-header !mb-6 pb-4 border-b border-slate-100">
                         <div>
                             <div class="section-kicker">Loyalty Points</div>
                             <h3 class="section-title !text-lg text-emerald-600">Customer Points Balance</h3>
                         </div>
                    </div>

                    <div class="space-y-8">
                        <div class="p-6 rounded-2xl bg-emerald-500/5 border border-emerald-500/10 relative overflow-hidden group hover:border-emerald-500/30 transition-all">
                             <div class="absolute -top-12 -right-12 size-32 rounded-full bg-emerald-500/10 blur-[80px]"></div>
                             <div class="text-[9px] font-bold uppercase text-emerald-600/60 tracking-widest mb-3 italic">Available Balance</div>
                             <div class="text-4xl font-black italic text-emerald-600 tracking-tighter emerald-pulse">0.00</div>
                             <div class="mt-6 flex items-center justify-between text-[10px] font-bold uppercase text-slate-500 tracking-widest italic leading-none">
                                 <span>Next Tier Unlock</span>
                                 <span class="text-emerald-600">5,000 PTS REQUIRED</span>
                             </div>
                             <div class="mt-3 h-1.5 w-full bg-emerald-500/10 rounded-full overflow-hidden">
                                 <div class="h-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]" style="width: 15%"></div>
                             </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                             <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
                                 <div class="text-[9px] font-bold uppercase text-muted-foreground mb-1 italic">Lifetime Accrued</div>
                                 <div class="text-lg font-bold text-foreground">0.00</div>
                             </div>
                             <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
                                 <div class="text-[9px] font-bold uppercase text-muted-foreground mb-1 italic">Redeemed</div>
                                 <div class="text-lg font-bold text-rose-500">0.00</div>
                             </div>
                        </div>
                    </div>
                </section>

                <section class="data-shell">
                    <div class="section-header !mb-6 pb-4 border-b border-slate-100">
                         <div>
                             <div class="section-kicker">Growth Engine</div>
                             <h3 class="section-title !text-lg">Affiliate & Distribution Networking</h3>
                         </div>
                    </div>

                    <div class="space-y-8">
                         <div class="p-6 rounded-xl bg-slate-50 border border-slate-200 group hover:border-emerald-500/20 transition-all">
                             <div class="text-[10px] font-bold uppercase text-slate-500 tracking-widest mb-4 italic">Unique Affiliate Identifier</div>
                             <div class="flex items-center gap-3">
                                 <code class="flex-1 px-4 py-3 bg-white border border-slate-200 rounded-lg font-bold text-xs text-emerald-600 italic tracking-[0.2em] shadow-inner">CRM-{{ strtoupper(substr($customer->uuid, 0, 8)) }}</code>
                                 <button class="h-10 px-4 rounded-lg bg-emerald-600 text-white shadow-md hover:bg-emerald-700 transition-all flex items-center justify-center border-none"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg></button>
                             </div>
                             <p class="mt-4 text-[11px] font-medium italic text-slate-500/80 leading-relaxed">Broadcast this identifier to trigger auto-referral point incentives during account registration.</p>
                         </div>
                    </div>
                </section>
            </div>

            {{-- 📡 TAB 4: SYSTEM PULSE (HISTORY) --}}
            <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                <section class="data-shell">
                    <div class="section-header !mb-8">
                         <div>
                             <div class="section-kicker">Activity Log</div>
                             <h3 class="section-title">Customer Interactions</h3>
                             <p class="section-copy">Timeline of all recorded conversations and field visits.</p>
                         </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($activities->where('action', 'customer.interaction') as $activity)
                            <div class="p-6 rounded-xl border border-slate-100 bg-white shadow-sm ring-1 ring-slate-200">
                                <div class="flex items-start justify-between gap-6">
                                    <div class="flex items-start gap-4">
                                        <div class="size-10 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm mt-1">
                                             <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="text-xs font-bold text-foreground">Conversation: {{ $activity->properties['interaction_type'] ?? 'Call' }}</span>
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[9px] font-bold uppercase tracking-wider">Logged by {{ $activity->causer->name ?? 'System' }}</span>
                                            </div>
                                            <p class="text-xs text-slate-600 leading-relaxed max-w-2xl">{{ $activity->properties['notes'] ?: 'No summary provided.' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                         <div class="text-[10px] font-bold text-foreground">{{ $activity->created_at->format('M d, Y') }}</div>
                                         <div class="text-[9px] font-medium text-slate-400 mt-1 uppercase">{{ $activity->created_at->format('H:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 flex flex-col items-center justify-center text-center bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200">
                                 <p class="text-xs font-bold text-slate-400 uppercase tracking-widest italic">No interactions recorded yet</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        {{-- 🔷 SIMPLE ADDRESS MODAL --}}
        <div x-show="showAddressModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div @click.away="showAddressModal = false" class="relative w-full max-w-4xl overflow-hidden rounded-2xl border border-border bg-white shadow-2xl">
                {{-- Modal Header --}}
                <div class="px-8 py-6 border-b border-border bg-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-foreground" x-text="isEditingAddress ? 'Edit Address' : 'Add New Address' "></h3>
                        <p class="text-xs text-muted-foreground mt-1">Provide the location details for the customer.</p>
                    </div>
                    <button @click="showAddressModal = false" class="text-muted-foreground hover:text-foreground transition-colors p-2"><svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"/></svg></button>
                </div>

                {{-- Modal Body --}}
                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto premium-scrollbar">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- ⬅️ Basic Info --}}
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5 focus-within:text-emerald-600 transition-colors">
                                    <label class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground">Address Type *</label>
                                    <select x-model="addressForm.type" class="w-full h-11 px-3 rounded-xl bg-white border border-border focus:border-emerald-500 focus:ring-0 text-sm font-medium">
                                        <option value="shipping">Shipping Address</option>
                                        <option value="billing">Billing Address</option>
                                        <option value="both">Both</option>
                                    </select>
                                </div>
                                <div class="space-y-1.5 focus-within:text-emerald-600 transition-colors">
                                    <label class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground">Address Label</label>
                                    <select x-model="addressForm.label" class="w-full h-11 px-3 rounded-xl bg-white border border-border focus:border-emerald-500 focus:ring-0 text-sm font-medium">
                                        <option value="Farm">Farm</option>
                                        <option value="Home">Home</option>
                                        <option value="Office">Office</option>
                                        <option value="Warehouse">Warehouse</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-1.5 focus-within:text-emerald-600 transition-colors">
                                <label class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground">Address Line 1 *</label>
                                <input type="text" x-model="addressForm.address_line1" class="w-full h-11 px-4 rounded-xl bg-white border border-border focus:border-emerald-500 focus:ring-0 text-sm" placeholder="House no, Building, Road name">
                            </div>

                            <div class="space-y-1.5 focus-within:text-emerald-600 transition-colors">
                                <label class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground">Nearby Landmark</label>
                                <input type="text" x-model="addressForm.address_line2" class="w-full h-11 px-4 rounded-xl bg-white border border-border focus:border-emerald-500 focus:ring-0 text-sm" placeholder="Optional landmark...">
                            </div>

                            <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-100">
                                <input type="checkbox" x-model="addressForm.is_default" class="size-4 rounded text-emerald-600 focus:ring-emerald-500 border-emerald-300">
                                <span class="text-xs font-semibold text-emerald-800">Set as Primary Address</span>
                            </div>
                        </div>

                        {{-- ➡️ Geographical Info --}}
                        <div class="space-y-6">
                            <div class="relative" x-data="{ query: '', results: [], show: false, async search() { if (this.query.length < 2) { this.results = []; return; } const r = await fetch(`{{ route('villages.search') }}?q=${this.query}`); this.results = await r.json(); this.show = true; }, select(v) { addressForm.village = v.village_name; addressForm.pincode = v.pincode; addressForm.taluka = v.taluka_name; addressForm.district = v.district_name; addressForm.state = v.state_name; addressForm.post_office = v.post_so_name; addressForm.village_id = v.id; this.show = false; this.query = ''; } }">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Search Village (Autocomplete)</label>
                                    <div class="relative">
                                        <input type="text" x-model="query" @input.debounce.300ms="search()" @focus="show = results.length > 0" class="w-full h-11 pl-11 pr-4 rounded-xl bg-emerald-50/30 border border-emerald-200 focus:border-emerald-500 focus:ring-0 text-sm" placeholder="Type village or pincode...">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5"/></svg></div>
                                    </div>
                                </div>
                                
                                <div x-show="show" @click.away="show = false" class="absolute z-[150] top-full left-0 right-0 mt-2 p-1 bg-white border border-border shadow-xl rounded-xl max-h-60 overflow-y-auto premium-scrollbar" x-transition.opacity>
                                    <template x-for="v in results" :key="v.id">
                                        <button @click="select(v)" class="w-full text-left p-3 rounded-lg hover:bg-emerald-50 transition-colors flex items-center justify-between group">
                                             <div>
                                                  <div class="text-sm font-bold text-foreground uppercase" x-text="v.village_name"></div>
                                                  <div class="text-[10px] text-muted-foreground uppercase" x-text="v.taluka_name + ', ' + v.district_name"></div>
                                             </div>
                                             <div class="text-xs font-bold text-emerald-600" x-text="v.pincode"></div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5 opacity-80">
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Village</label>
                                    <input type="text" x-model="addressForm.village" class="w-full h-11 px-3 rounded-xl bg-slate-50 border border-border text-sm" readonly>
                                </div>
                                <div class="space-y-1.5 opacity-80">
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Pincode</label>
                                    <input type="text" x-model="addressForm.pincode" class="w-full h-11 px-3 rounded-xl bg-slate-50 border border-border text-sm" readonly>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5 opacity-80">
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Post Office</label>
                                    <input type="text" x-model="addressForm.post_office" class="w-full h-11 px-3 rounded-xl bg-slate-50 border border-border text-sm" readonly>
                                </div>
                                <div class="space-y-1.5 opacity-80">
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Taluka</label>
                                    <input type="text" x-model="addressForm.taluka" class="w-full h-11 px-3 rounded-xl bg-slate-50 border border-border text-sm" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-8 py-6 bg-slate-50 border-t border-border flex items-center justify-end gap-3">
                    <button @click="showAddressModal = false" class="px-6 py-2.5 text-xs font-bold text-muted-foreground hover:text-foreground transition-colors uppercase tracking-widest">Cancel</button>
                    <button @click="submitAddress()" class="px-10 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold uppercase tracking-widest shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-colors">
                         <span x-text="isEditingAddress ? 'Update Address' : 'Save Address' "></span>
                    </button>
                </div>
            </div>
        </div>
        {{-- 🔷 TAG & CLOSE MODAL --}}
        <div x-show="showInteractionModal" class="fixed inset-0 z-[140] flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div @click.away="showInteractionModal = false" class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-border bg-white shadow-2xl">
                <div class="px-6 py-5 border-b border-border bg-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-foreground">Tag & Close Profile</h3>
                        <p class="text-[11px] text-muted-foreground mt-0.5">Log the conversation outcome before exiting.</p>
                    </div>
                    <button @click="showInteractionModal = false" class="text-muted-foreground hover:text-foreground transition-colors"><svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"/></svg></button>
                </div>

                <div class="p-6 space-y-5">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground">Interaction Type *</label>
                        <select x-model="interactionForm.interaction_type" class="w-full h-10 px-3 rounded-lg bg-white border border-border focus:border-emerald-500 focus:ring-0 text-sm font-medium">
                            <option value="Call">Phone Call</option>
                            <option value="WhatsApp">WhatsApp / Chat</option>
                            <option value="Visit">Field Visit</option>
                            <option value="Office">Office Meeting</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground">Conversation Summary</label>
                        <textarea x-model="interactionForm.notes" rows="3" class="w-full p-3 rounded-lg bg-white border border-border focus:border-emerald-500 focus:ring-0 text-sm" placeholder="What was discussed?"></textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground">Next Follow-up Date</label>
                        <input type="date" x-model="interactionForm.next_follow_up" class="w-full h-10 px-3 rounded-lg bg-white border border-border focus:border-emerald-500 focus:ring-0 text-sm">
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-border flex items-center justify-end gap-3">
                    <button type="button" @click="showInteractionModal = false" class="text-xs font-bold text-muted-foreground hover:text-foreground uppercase tracking-widest">Keep Open</button>
                    <button type="button" @click="submitInteraction()" class="px-6 py-2 rounded-lg bg-slate-900 text-white text-xs font-bold uppercase tracking-widest shadow-md hover:bg-black transition-colors">
                         Submit & Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
