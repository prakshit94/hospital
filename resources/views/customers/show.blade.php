@extends('layouts.app')

@php
    $pageTitle = 'Customer Profile';
@endphp

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
            if (!confirm('Are you sure you want to delete this logistical point?')) return;

            try {
                const response = await fetch(`{{ url('customer-addresses') }}/${addressId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    window.location.reload();
                }
            } catch (e) { console.error(e); }
        }
    }">
        {{-- 🔷 HERO (Elite Header) --}}
        <section class="p-8 rounded-[2.5rem] bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 border border-white/5 shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(16,185,129,0.1),transparent_50%)]"></div>
            <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex items-start gap-6">
                    <div class="size-24 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0">
                         <span class="text-3xl font-black italic text-emerald-500">{{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                             <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $customer->status === 'active' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">{{ $customer->status }} Profile</span>
                             <span class="text-emerald-500/60 font-black italic text-xs tracking-widest">{{ $customer->customer_code }}</span>
                        </div>
                        <h1 class="text-4xl font-black italic tracking-tighter text-white mb-2 leading-none">{{ $customer->display_name }}</h1>
                        <div class="flex flex-wrap gap-4 text-sm font-medium text-slate-400 italic">
                             <span class="flex items-center gap-2"><svg class="size-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>{{ $customer->mobile }}</span>
                             <span class="flex items-center gap-2"><svg class="size-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>{{ $customer->email ?: 'no-email@system.com' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-4">
                    <x-ui.button variant="secondary" href="{{ route('customers.edit', $customer->uuid) }}" data-modal-open class="bg-white/5 border-white/10 text-white hover:bg-white/10 h-14 px-8 rounded-2xl tracking-widest uppercase font-black italic text-xs">
                         <svg class="size-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                         Refine Profile
                    </x-ui.button>
                    <x-ui.button variant="secondary" @click="openAddressModal()" class="bg-white/5 border-white/10 text-white hover:bg-white/10 h-14 px-8 rounded-2xl tracking-widest uppercase font-black italic text-xs">
                         <svg class="size-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                         Quick Logistics
                    </x-ui.button>
                     <x-ui.button class="h-14 px-10 rounded-2xl bg-emerald-500 shadow-2xl shadow-emerald-500/20 tracking-[0.2em] uppercase font-black italic text-xs">
                         Create Order
                    </x-ui.button>
                </div>
            </div>

            {{-- 🟢 Tab Navigation --}}
            <nav class="mt-12 flex gap-2 p-1.5 bg-black/20 backdrop-blur-xl rounded-2xl border border-white/5 w-fit">
                @foreach([
                    'overview' => ['Overview', 'M20 13a8 8 0 0 1 0 16 8 8 0 0 1 0-16z'],
                    'products' => ['Products', 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                    'loyalty' => ['Points & Referral', 'M13 10V3L4 14h7v7l9-11h-7z'],
                    'rewards' => ['Rewards', 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                    'history' => ['Activity Log', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z']
                ] as $tid => $data)
                    <button @click="activeTab = '{{ $tid }}'" 
                            :class="activeTab === '{{ $tid }}' ? 'bg-emerald-500 text-white shadow-xl' : 'text-slate-400 hover:text-white hover:bg-white/5'"
                            class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 italic">
                        <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $tid === 'overview' ? 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' : $data[1] }}"/></svg>
                        {{ $data[0] }}
                    </button>
                @endforeach
            </nav>
        </section>

        {{-- 🔷 CONTENT AREA --}}
        <div class="mt-8">
            {{-- Tab 1: Overview --}}
            <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 xl:grid-cols-[1fr_350px] gap-8">
                <div class="space-y-8">
                    <section class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="p-6 rounded-3xl bg-secondary/10 border border-border/50 group hover:border-emerald-500/30 transition-all">
                             <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-4">Total Purchases</div>
                             <div class="text-3xl font-black italic text-foreground leading-none">₹0.00</div>
                             <div class="mt-2 text-[10px] font-bold text-emerald-600 uppercase">0 Orders processed</div>
                        </div>
                        <div class="p-6 rounded-3xl bg-secondary/10 border border-border/50 group hover:border-emerald-500/30 transition-all">
                             <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-4">Primary Territory</div>
                             <div class="text-xl font-black italic text-foreground truncate">{{ $customer->primaryAddress->village->village_name ?? 'Not Assigned' }}</div>
                             <div class="mt-2 text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{{ $customer->primaryAddress->village->pincode ?? '---' }}</div>
                        </div>
                        <div class="p-6 rounded-3xl bg-amber-500/5 border border-amber-500/10 group hover:border-amber-500/30 transition-all text-center flex flex-col items-center justify-center">
                             <div class="text-[10px] font-black uppercase tracking-widest text-amber-600 mb-2">Portfolio Metrics</div>
                             <div class="text-2xl font-black italic text-foreground leading-none">{{ $customer->land_area ?: '0.00' }}</div>
                             <div class="text-[9px] font-bold text-amber-600/60 uppercase tracking-widest mt-1">{{ $customer->land_unit ?: 'Acres' }} Managed</div>
                        </div>
                    </section>

                    {{-- 🌾 AGRICULTURE & COMPLIANCE BLOCK --}}
                    <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                         <x-ui.card class="bg-secondary/5 border-border/40">
                             <div class="flex items-center gap-3 mb-6">
                                 <div class="size-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg></div>
                                 <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-foreground italic">Agriculture Portfolio</h4>
                             </div>
                             <div class="space-y-6">
                                 <div>
                                     <div class="text-[8px] font-black uppercase text-muted-foreground mb-3 tracking-widest italic">Crop Specialization</div>
                                     <div class="flex flex-wrap gap-2">
                                         @forelse($customer->crops ?? [] as $crop)
                                             <span class="px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-[9px] font-black italic uppercase tracking-tighter">{{ $crop }}</span>
                                         @empty
                                             <span class="text-[10px] font-medium italic text-muted-foreground">Not recorded</span>
                                         @endforelse
                                     </div>
                                 </div>
                                 <div>
                                     <div class="text-[8px] font-black uppercase text-muted-foreground mb-3 tracking-widest italic">Irrigation Support</div>
                                     <div class="flex flex-wrap gap-2">
                                         @forelse($customer->irrigation_type ?? [] as $type)
                                             <span class="px-3 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-600 text-[9px] font-black italic uppercase tracking-tighter">{{ $type }}</span>
                                         @empty
                                             <span class="text-[10px] font-medium italic text-muted-foreground">Not recorded</span>
                                         @endforelse
                                     </div>
                                 </div>
                             </div>
                         </x-ui.card>

                         <x-ui.card class="bg-secondary/5 border-border/40">
                             <div class="flex items-center gap-3 mb-6">
                                 <div class="size-8 rounded-lg bg-rose-500/10 flex items-center justify-center text-rose-600"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-7.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                                 <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-foreground italic">Statutory & Compliance</h4>
                             </div>
                             <div class="grid grid-cols-2 gap-4">
                                  <div>
                                       <div class="text-[8px] font-black uppercase text-muted-foreground mb-1 italic">PAN Number</div>
                                       <div class="text-xs font-black italic text-foreground">{{ $customer->pan_number ?: '---' }}</div>
                                  </div>
                                  <div>
                                       <div class="text-[8px] font-black uppercase text-muted-foreground mb-1 italic">Aadhaar (Last 4)</div>
                                       <div class="text-xs font-black italic text-foreground">{{ $customer->aadhaar_last4 ? '**** **** '.$customer->aadhaar_last4 : '---' }}</div>
                                  </div>
                                  <div class="col-span-2 pt-2">
                                       <div class="text-[8px] font-black uppercase text-muted-foreground mb-1 italic">Government Registration (GST)</div>
                                       <div class="text-xs font-black italic text-foreground">{{ $customer->gst_number ?: 'NOT REGISTERED' }}</div>
                                  </div>
                                  <div class="col-span-2 pt-2 border-t border-border/40 mt-2">
                                       <div class="text-[8px] font-black uppercase text-muted-foreground mb-1 italic">Customer Classification</div>
                                       <div class="text-[9px] font-black uppercase italic text-emerald-600 tracking-widest">{{ ucfirst($customer->category) ?: 'INDIVIDUAL' }} ACCOUNT</div>
                                  </div>
                             </div>
                         </x-ui.card>
                    </section>

                    <x-ui.card class="overflow-hidden border-border/40">
                        <div class="flex items-center justify-between mb-8 border-b border-border/40 pb-6">
                            <div>
                                <h3 class="text-2xl font-black italic tracking-tight text-foreground">Review & Logistics</h3>
                                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mt-1">Verified Shipping & Billing Points</p>
                            </div>
                            <x-ui.button variant="primary" size="sm" @click="openAddressModal()" class="rounded-xl font-black tracking-widest shadow-xl shadow-primary/20">
                                <svg class="size-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Add Distribution Point
                            </x-ui.button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($customer->addresses as $address)
                                <div class="relative group p-6 rounded-2xl border-2 {{ $address->is_default ? 'border-emerald-500/20 bg-emerald-500/[0.02]' : 'border-border/60 bg-muted/5' }} transition-all hover:border-emerald-500/40">
                                    @if($address->is_default) <span class="absolute -top-3 left-6 px-3 py-0.5 rounded-full bg-emerald-500 text-[8px] font-black uppercase text-white shadow-lg">Primary Destination</span> @endif
                                    <div class="flex justify-between items-start mb-4">
                                         <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground">{{ $address->type }} Location</div>
                                         <div class="flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                             <button @click="openAddressModal({{ json_encode($address) }})" class="p-1.5 rounded-lg bg-background shadow-sm hover:text-emerald-500 transition-colors" title="Edit Logistical Details"><svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                             @if(!$address->is_default)
                                                 <button @click="deleteAddress({{ $address->id }})" class="p-1.5 rounded-lg bg-background shadow-sm hover:text-rose-500 transition-colors" title="Delete Point"><svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                             @endif
                                         </div>
                                    </div>
                                    <h4 class="text-lg font-black italic text-foreground mb-1">{{ $address->address_line1 }}</h4>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-6">{{ $address->village->village_name ?? 'Unknown Territory' }} ({{ $address->village->pincode }})</p>
                                    <div class="pt-4 border-t border-border/40 flex items-center gap-4 text-xs font-bold text-muted-foreground italic">
                                         <span class="flex items-center gap-2"><svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/></svg>{{ $address->contact_name }}</span>
                                         <span class="flex items-center gap-2"><svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>{{ $address->contact_phone }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 py-10 flex flex-col items-center justify-center bg-muted/5 border-2 border-dashed border-border/60 rounded-3xl">
                                     <p class="text-sm font-medium italic text-muted-foreground uppercase tracking-widest">No Logistical Data Found</p>
                                </div>
                            @endforelse
                        </div>
                    </x-ui.card>
                </div>

                {{-- Sidebar: Finance & Assignment --}}
                <div class="space-y-8">
                     <x-ui.card class="bg-slate-900 border-white/5 overflow-hidden relative">
                         <div class="absolute -top-12 -right-12 size-32 rounded-full bg-emerald-500/10 blur-3xl"></div>
                         <div class="relative z-10">
                             <div class="text-[9px] font-black uppercase tracking-[0.2em] text-emerald-500 mb-6">Financial Ledger</div>
                             <div class="space-y-6">
                                 <div>
                                     <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 italic">Outstanding Total</div>
                                     <div class="text-3xl font-black text-white italic tracking-tighter">₹{{ number_format($customer->outstanding_balance, 2) }}</div>
                                 </div>
                                 <div class="grid grid-cols-2 gap-4">
                                     <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                                         <div class="text-[8px] font-black uppercase text-slate-500 mb-1">Credit Limit</div>
                                         <div class="text-xs font-black text-emerald-500 italic">₹{{ number_format($customer->credit_limit, 2) }}</div>
                                     </div>
                                     <div class="p-4 rounded-xl bg-rose-500/5 border border-rose-500/10">
                                         <div class="text-[8px] font-black uppercase text-slate-500 mb-1 font-bold">Overdue</div>
                                         <div class="text-xs font-black text-rose-500 italic">₹{{ number_format($customer->overdue_amount, 2) }}</div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </x-ui.card>
                     <x-ui.card class="bg-secondary/10 border-border/40">
                         <div class="text-[9px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-4">Ownership</div>
                         <div class="flex items-center gap-4">
                             <div class="size-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-black italic">{{ substr($customer->assignedTo->name ?? 'U', 0, 1) }}</div>
                             <div>
                                 <div class="text-xs font-black italic text-foreground">{{ $customer->assignedTo->name ?? 'System Unassigned' }}</div>
                                 <div class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest">Account Executive</div>
                             </div>
                         </div>
                     </x-ui.card>
                </div>
            </div>

            {{-- Tab 2: Products --}}
            <div x-show="activeTab === 'products'" x-transition:enter="transition duration-200" class="space-y-6">
                <x-ui.card class="text-center py-20 bg-muted/5 border-border/40">
                     <div class="max-w-xs mx-auto">
                         <div class="size-16 rounded-3xl bg-secondary mx-auto mb-6 flex items-center justify-center text-muted-foreground/40">
                              <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                         </div>
                         <h3 class="text-xl font-black italic text-foreground mb-2">Order History Empty</h3>
                         <p class="text-xs font-medium text-muted-foreground uppercase tracking-widest italic">No tracked inventory or transaction logs available for this fiscal cycle.</p>
                     </div>
                </x-ui.card>
            </div>

            {{-- Tab 3: Loyalty & Referrals --}}
            <div x-show="activeTab === 'loyalty'" x-transition:enter="transition duration-200" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <x-ui.card class="relative overflow-hidden group">
                     <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                     <div class="flex items-center justify-between mb-8">
                         <h3 class="text-2xl font-black italic text-foreground tracking-tighter">Point Mechanics</h3>
                         <span class="px-4 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest">Elite Tier</span>
                     </div>
                     <div class="space-y-8 relative">
                          <div class="flex items-baseline gap-4">
                               <div class="text-6xl font-black italic text-foreground tracking-tighter">0</div>
                               <div class="text-[10px] font-black uppercase text-muted-foreground/60 tracking-[0.2em] italic mb-2">Loyalty Points Available</div>
                          </div>
                          <div class="pt-8 border-t border-border/40 grid grid-cols-3 gap-6">
                               <div><div class="text-[8px] font-black uppercase tracking-widest text-muted-foreground mb-1 italic">Lifetime Earned</div><div class="text-sm font-black italic text-foreground">0</div></div>
                               <div><div class="text-[8px] font-black uppercase tracking-widest text-muted-foreground mb-1 italic">Total Redeemed</div><div class="text-sm font-black italic text-foreground">0</div></div>
                               <div><div class="text-[8px] font-black uppercase tracking-widest text-muted-foreground mb-1 italic">Next Goal</div><div class="text-sm font-black italic text-emerald-500">100</div></div>
                          </div>
                     </div>
                </x-ui.card>
                <x-ui.card>
                     <h3 class="text-2xl font-black italic text-foreground tracking-tighter mb-8 italic">Referral Gateway</h3>
                     <div class="p-6 rounded-3xl bg-secondary/10 border border-border/60">
                         <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-4 italic">Unique Affiliate Code</div>
                         <div class="flex items-center gap-3">
                             <code class="flex-1 px-4 py-3 bg-background border border-border/60 rounded-xl font-black text-sm text-primary italic tracking-[0.2em]">CRM-{{ strtoupper(substr($customer->uuid, 0, 8)) }}</code>
                             <button class="p-3.5 rounded-xl bg-primary text-white shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg></button>
                         </div>
                     </div>
                     <div class="mt-6 p-4 text-[10px] font-black uppercase italic text-emerald-600/60 text-center tracking-widest">Share this code to earn 10 points for every verified registration.</div>
                </x-ui.card>
            </div>

            {{-- Tab 4: Rewards --}}
            <div x-show="activeTab === 'rewards'" x-transition:enter="transition duration-200" class="space-y-8">
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                      @foreach([['SAVE10', '10% OFF Seeds', 'Active'], ['FASTSHIP', 'Free Local Logistics', 'Standard'], ['FARMER500', '₹500 Cash Back', 'Locked']] as $c)
                      <div class="group relative p-8 rounded-[2.5rem] bg-secondary/10 border border-border/40 overflow-hidden hover:border-primary/40 transition-all">
                           <div class="absolute -top-4 -right-4 size-20 rounded-full bg-primary/5 blur-2xl group-hover:bg-primary/20 transition-all"></div>
                           <span class="block text-[8px] font-black uppercase italic tracking-widest text-muted-foreground mb-4">{{ $c[2] }} Reward</span>
                           <h4 class="text-3xl font-black italic text-foreground mb-1">{{ $c[0] }}</h4>
                           <p class="text-xs font-bold text-primary italic uppercase tracking-tighter mb-8">{{ $c[1] }}</p>
                           <button class="w-full py-4 rounded-2xl bg-secondary/20 border border-border/60 text-[10px] font-black uppercase tracking-widest italic hover:bg-primary hover:text-white hover:border-primary transition-all">Redeem Value</button>
                      </div>
                      @endforeach
                 </div>
            </div>

            {{-- Tab 5: History --}}
            <div x-show="activeTab === 'history'" x-transition:enter="transition duration-200" class="space-y-6">
                <x-ui.card class="bg-slate-900 border-white/5">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-2xl font-black italic text-white tracking-tighter">System Pulse</h3>
                        <span class="text-[9px] font-black uppercase italic tracking-widest text-slate-500">Showing last 10 events</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($activities as $activity)
                            <div class="p-6 rounded-3xl bg-white/[0.03] border border-white/5 hover:border-emerald-500/20 transition-all group">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-5">
                                        <div class="size-12 rounded-2xl bg-white/5 flex items-center justify-center text-emerald-500">
                                             <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ str_contains($activity->action, 'update') ? 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15' : 'M12 4v16m8-8H4' }}"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-black italic text-white group-hover:text-emerald-500 transition-colors">{{ $activity->description }}</div>
                                            <div class="mt-1 flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-slate-500 italic">
                                                 <span>{{ $activity->causer->name ?? 'System' }}</span>
                                                 <span class="size-1 rounded-full bg-slate-700"></span>
                                                 <span>{{ $activity->action }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-[9px] font-black uppercase text-slate-500">{{ $activity->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center text-[10px] font-black uppercase italic text-slate-500 tracking-widest">No Pulse Data Available</div>
                        @endforelse
                    </div>
                </x-ui.card>
            </div>
        </div>

        {{-- 🔷 Address Modal --}}
        <div x-show="showAddressModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4 bg-slate-950/45 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div @click.away="showAddressModal = false" class="relative w-full max-w-5xl overflow-hidden rounded-[1.5rem] border border-border bg-popover shadow-[0_30px_90px_-40px_rgba(15,23,42,0.4)] animate-in fade-in zoom-in-95 duration-300">
                
                {{-- 📍 MODAL HEADER: Normal & Clear Language --}}
                <div class="px-8 py-6 border-b border-border bg-muted/20 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black italic tracking-tighter text-foreground uppercase" x-text="isEditingAddress ? 'Update Logistical Address' : 'Register New Address' "></h3>
                        <p class="text-[9px] font-black text-muted-foreground uppercase tracking-[0.2em] mt-1 italic flex items-center gap-2">
                             <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                             Customer 360 Logistical Hub
                        </p>
                    </div>
                    <button @click="showAddressModal = false" class="text-muted-foreground hover:text-foreground transition-all p-2 bg-secondary/50 rounded-xl"><svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12" stroke-width="2.5"/></svg></button>
                </div>

                {{-- 📍 MODAL CONTENT: High-Fidelity & Clean Words --}}
                <div class="p-8 space-y-8 bg-background/50 backdrop-blur-xl max-h-[80vh] overflow-y-auto premium-scrollbar">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- ⬅️ LEFT COLUMN: Basic Details --}}
                        <div class="space-y-6">
                            <div class="p-6 rounded-2xl bg-secondary/20 border border-border/40 backdrop-blur-sm space-y-6">
                                <div class="text-[9px] font-black uppercase text-muted-foreground tracking-[0.2em] flex items-center gap-3 italic">
                                    Address Classification <span class="h-px flex-1 bg-border/40"></span>
                                </div>
                                <div class="grid grid-cols-2 gap-5">
                                    <div class="ui-field">
                                        <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">Address Type *</label>
                                        <select x-model="addressForm.type" class="ui-select h-11 py-1 font-bold italic text-xs bg-background/50 border-border/60">
                                            <option value="shipping">Shipping Address</option>
                                            <option value="billing">Billing Address</option>
                                            <option value="both">Both (Shipping & Billing)</option>
                                        </select>
                                    </div>
                                    <div class="ui-field">
                                        <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">Address Label</label>
                                        <select x-model="addressForm.label" class="ui-select h-11 py-1 font-bold italic text-xs bg-background/50 border-border/60">
                                            <option value="Farm">Farm</option>
                                            <option value="Warehouse">Warehouse</option>
                                            <option value="Home">Home</option>
                                            <option value="Office">Office Address</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 rounded-2xl border border-border/50 bg-secondary/10 space-y-6">
                                <div class="text-[9px] font-black uppercase text-muted-foreground tracking-[0.2em] flex items-center gap-3 italic">
                                    Spatial Address Details <span class="h-px flex-1 bg-border/40"></span>
                                </div>
                                <div class="space-y-6">
                                    <div class="ui-field">
                                        <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">Street / Block / Road *</label>
                                        <input type="text" x-model="addressForm.address_line1" class="ui-input h-11 px-4 font-bold italic text-sm border-border/60 focus:bg-primary/5 transition-all" placeholder="House no, Shop no, Landmark">
                                    </div>
                                    <div class="ui-field">
                                        <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1 italic">Nearby Landmark / Area Name</label>
                                        <input type="text" x-model="addressForm.address_line2" class="ui-input h-11 px-4 font-semibold italic text-sm bg-background/30 border-border/30">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ➡️ RIGHT COLUMN: Geographical Details --}}
                        <div class="space-y-6">
                            <div class="p-6 rounded-2xl border border-border/50 bg-secondary/10 space-y-6 flex-1">
                                <div class="text-[9px] font-black uppercase text-muted-foreground tracking-[0.2em] flex items-center gap-2 italic">
                                    City & Regional Details <span class="h-px flex-1 bg-border/40"></span>
                                </div>
                                <div class="grid grid-cols-2 gap-5">
                                    <div class="ui-field">
                                        <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">Village / Locality</label>
                                        <input type="text" x-model="addressForm.village" class="ui-input h-11 px-4 font-bold italic tracking-tighter text-xs bg-background/50 border-border/60">
                                    </div>
                                    <div class="ui-field">
                                        <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">Pincode / Zip *</label>
                                        <input type="text" x-model="addressForm.pincode" class="ui-input h-11 px-4 font-bold italic tracking-widest text-xs bg-background/50 border-border/60">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-5">
                                    <div class="ui-field">
                                        <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">Taluka / Block</label>
                                        <input type="text" x-model="addressForm.taluka" class="ui-input h-11 px-4 font-bold italic tracking-tighter text-xs bg-background/50 border-border/60">
                                    </div>
                                    <div class="ui-field">
                                        <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">District Name</label>
                                        <input type="text" x-model="addressForm.district" class="ui-input h-11 px-4 font-bold italic tracking-tighter text-xs bg-background/50 border-border/60">
                                    </div>
                                </div>
                                <div class="ui-field">
                                    <label class="ui-label text-[10px] font-black tracking-widest text-muted-foreground uppercase mb-1">State / Province</label>
                                    <input type="text" x-model="addressForm.state" class="ui-input h-11 px-4 font-bold italic tracking-tighter text-xs bg-background/50 border-border/60">
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-6 bg-emerald-500/5 rounded-2xl border border-emerald-500/20 shadow-inner group hover:bg-emerald-500/10 transition-all">
                                <div class="flex items-center gap-4">
                                     <div class="size-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 transition-colors group-hover:bg-emerald-500/20"><svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                                     <div>
                                         <h5 class="text-[11px] font-black italic text-foreground uppercase tracking-tight">Set as Primary Address</h5>
                                         <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest italic">Default location for all transactions</p>
                                     </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="addressForm.is_default" class="sr-only peer">
                                    <div class="w-12 h-6 bg-muted rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-sm"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 📍 MODAL FOOTER: Perfectly Synchronized Actions --}}
                <div class="px-8 py-6 bg-secondary/10 border-t border-border flex items-center justify-end gap-3">
                    <button @click="showAddressModal = false" class="px-6 py-2 text-xs font-black uppercase tracking-widest text-muted-foreground hover:text-foreground transition-all italic underline underline-offset-8 decoration-border/40">Cancel</button>
                    <x-ui.button @click="submitAddress()" class="px-10 py-2.5 text-xs shadow-2xl shadow-emerald-500/30 bg-emerald-500 hover:bg-emerald-600">
                         <span x-text="isEditingAddress ? 'Update Address' : 'Register Address' "></span>
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
@endsection
