@extends('layouts.app')

@php
    $pageTitle = 'Customer Profile';
@endphp

@section('content')
    <div class="page-stack" x-data="{
        showAddressModal: false,
        isEditingAddress: false,
        currentAddress: null,
        addressForm: {
            address_line1: '',
            address_line2: '',
            type: 'shipping',
            village_id: '',
            contact_name: '',
            contact_phone: '',
            is_default: false
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
                    address_line1: '',
                    address_line2: '',
                    type: 'shipping',
                    village_id: '',
                    contact_name: '{{ $customer->display_name }}',
                    contact_phone: '{{ $customer->mobile }}',
                    is_default: false
                };
            }
            this.showAddressModal = true;
        },
        async submitAddress() {
            const url = this.isEditingAddress ? `{{ url('customer-addresses') }}/${this.currentAddress.uuid}` : `{{ route('customers.addresses.store', $customer->uuid) }}`;
            const method = this.isEditingAddress ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.addressForm)
                });

                const result = await response.json();
                if (response.ok) {
                    window.location.reload();
                } else {
                    throw new Error(result.message || 'Validation failed');
                }
            } catch (error) {
                window.dispatchEvent(new CustomEvent('toast-notify', {
                    detail: { type: 'error', title: 'Error', message: error.message }
                }));
            }
        },
        async deleteAddress(uuid) {
            if (!confirm('Are you sure you want to delete this address?')) return;
            try {
                const response = await fetch(`{{ url('customer-addresses') }}/${uuid}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) window.location.reload();
            } catch (error) {
                console.error(error);
            }
        }
    }">
        <section class="hero-panel">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="flex flex-wrap gap-2 items-center mb-1">
                        <span class="{{ $customer->status === 'active' ? 'ui-status-success' : 'ui-status-danger' }}">{{ ucfirst($customer->status) }} account</span>
                        <span class="badge-secondary text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded">{{ $customer->customer_code }}</span>
                    </div>
                    <h1 class="hero-title">{{ $customer->display_name }}</h1>
                    <p class="hero-copy">{{ $customer->mobile }} • {{ $customer->email ?? 'No email' }}</p>
                    <p class="hero-copy mt-1 font-black italic uppercase text-[10px] text-muted-foreground">{{ ucfirst($customer->type) }} Profile ({{ ucfirst($customer->category) }})</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <x-ui.button variant="secondary" href="{{ route('customers.edit', $customer->uuid) }}" data-modal-open>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        Edit Profile
                    </x-ui.button>
                    @if(!$customer->trashed())
                        <form method="POST" action="{{ route('customers.destroy', $customer->uuid) }}" onsubmit="return confirm('Archive this customer?')">
                            @csrf
                            @method('DELETE')
                            <x-ui.button variant="secondary" class="text-danger border-danger/20 hover:bg-danger/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                Archive
                            </x-ui.button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('customers.restore', $customer->uuid) }}">
                            @csrf
                            <x-ui.button variant="secondary" class="text-emerald-600 border-emerald-200 hover:bg-emerald-50/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                                Restore Profile
                            </x-ui.button>
                        </form>
                    @endif
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(320px,0.8fr)]">
            <x-ui.card>
                <div class="section-header">
                    <div>
                        <div class="section-kicker">CRM Overview</div>
                        <h2 class="section-title">Customer snapshot</h2>
                        <p class="section-copy">Business profile, territory details, and engagement status.</p>
                    </div>
                </div>

                <div class="detail-grid mt-6">
                    <div class="detail-tile">
                        <div class="detail-label">Mobile</div>
                        <div class="detail-value text-emerald-600 font-bold tracking-tight">{{ $customer->mobile }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Type</div>
                        <div class="detail-value">{{ ucfirst($customer->type) }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Category</div>
                        <div class="detail-value">{{ ucfirst($customer->category) }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Lead Status</div>
                        <div class="detail-value">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-tight {{ $customer->lead_status == 'converted' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-amber-500/10 text-amber-600' }}">
                                <span class="size-1.5 rounded-full {{ $customer->lead_status == 'converted' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                {{ ucfirst($customer->lead_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Land Area</div>
                        <div class="detail-value font-black italic text-foreground">{{ $customer->land_area ?? '0.00' }} {{ ucfirst($customer->land_unit ?? 'Acre') }}</div>
                    </div>
                    <div class="detail-tile">
                        <div class="detail-label">Crops Tracked</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @forelse($customer->crops ?? [] as $crop)
                                <span class="ui-chip text-[9px] font-black italic uppercase tracking-tighter">{{ $crop }}</span>
                            @empty
                                <span class="text-xs text-muted-foreground italic">No crops recorded</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Address Directory Section --}}
                <div class="mt-12 pt-12 border-t border-border/60">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="font-heading text-xl font-black italic tracking-tight text-foreground">Address Directory</h3>
                            <p class="text-xs text-muted-foreground font-medium uppercase tracking-widest mt-0.5">Shipping and billing locations</p>
                        </div>
                        <x-ui.button variant="secondary" size="sm" @click="openAddressModal()">
                             <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14m-7-7v14"/></svg>
                             Add Address
                        </x-ui.button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        @forelse($customer->addresses as $address)
                            <div class="group relative overflow-hidden rounded-2xl border border-border bg-muted/10 p-5 transition-all hover:bg-muted/20 @if($customer->primary_address_id == $address->id) ring-2 ring-primary ring-offset-4 ring-offset-background @endif">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-muted-foreground">{{ $address->type }}</span>
                                        @if($address->is_default) <span class="ui-status-success text-[8px] py-0 px-1.5 h-auto">Primary</span> @endif
                                    </div>
                                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="openAddressModal({{ json_encode($address) }})" class="ui-icon-button-ghost"><svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        <button @click="deleteAddress('{{ $address->uuid }}')" class="ui-icon-button-danger-ghost"><svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M10 11v6"/><path d="M14 11v6"/></svg></button>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="font-black text-foreground italic text-sm">{{ $address->address_line1 }}</div>
                                    <div class="text-[10px] font-bold text-muted-foreground uppercase">{{ $address->village->village_name ?? 'N/A' }} ({{ $address->village->pincode ?? '---' }})</div>
                                    <div class="pt-3 flex items-center gap-2 text-[10px] font-black text-muted-foreground">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        {{ $address->contact_name }} • {{ $address->contact_phone }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-12 rounded-2xl border-2 border-dashed border-border/60 bg-muted/5 font-medium italic text-muted-foreground text-sm">
                                <p>No addresses recorded for this customer.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </x-ui.card>

            <div class="space-y-6">
                <x-ui.card class="space-y-6">
                    <div>
                        <div class="section-kicker">Finance</div>
                        <h2 class="section-title">Ledger Summary</h2>
                        <p class="section-copy">Credit limit and payment statistics.</p>
                    </div>
                    
                    <div class="space-y-5">
                        <div class="p-4 rounded-2xl bg-danger/5 border border-danger/10 text-center">
                            <div class="text-[10px] text-danger/80 font-black uppercase tracking-widest mb-1.5">Outstanding Balance</div>
                            <div class="text-3xl font-black text-danger italic tracking-tight">₹{{ number_format($customer->outstanding_balance, 2) }}</div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="detail-tile">
                                <div class="detail-label">Credit Limit</div>
                                <div class="detail-value text-xs font-bold text-foreground">₹{{ number_format($customer->credit_limit, 2) }}</div>
                            </div>
                            <div class="detail-tile">
                                <div class="detail-label">Overdue</div>
                                <div class="detail-value text-xs font-bold text-danger">₹{{ number_format($customer->overdue_amount, 2) }}</div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-label">KYC Status</div>
                            <div class="mt-2">
                                <span class="{{ $customer->kyc_status == 'approved' ? 'ui-status-success' : 'ui-status-danger' }} uppercase text-[9px]">{{ $customer->kyc_status }}</span>
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <div>
                        <div class="section-kicker">Ownership</div>
                        <h2 class="section-title">Assignment</h2>
                    </div>
                    <div class="mt-4 space-y-4 text-sm font-medium italic">
                         <div class="detail-tile">
                            <div class="detail-label">Assigned To</div>
                            <div class="detail-value">{{ $customer->assignedTo->name ?? 'Unassigned' }}</div>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </section>

        {{-- Recent Activity Section --}}
        <x-ui.card class="mt-6">
            <div class="section-header">
                <div>
                    <div class="section-kicker">Activity Log</div>
                    <h2 class="section-title">Latest events around this customer</h2>
                    <p class="section-copy">Recent actions performed on this profile.</p>
                </div>
            </div>
            <div class="mt-8 space-y-3">
                @forelse($activities as $activity)
                    <div class="list-card rounded-2xl border border-border/40 hover:border-primary/20 hover:bg-primary/5 transition-all p-4">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-secondary text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 20h9"/><path d="m16.5 3.5 4 4L7 21H3v-4z"/></svg>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-foreground leading-snug">{{ $activity->description ?: $activity->action }}</div>
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="px-1.5 py-0.5 rounded-md bg-secondary text-muted-foreground text-[8px] font-black uppercase tracking-widest">{{ $activity->action }}</span>
                                        <span class="text-[10px] font-medium text-muted-foreground italic">By {{ $activity->causer->name ?? 'System' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">{{ $activity->created_at?->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">No activity recorded yet for this customer.</div>
                @endforelse
            </div>
        </x-ui.card>
        
        {{-- Address Modal --}}
        <div x-show="showAddressModal" class="fixed inset-0 z-[130] flex items-center justify-center p-4 bg-slate-950/45 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div @click.away="showAddressModal = false" class="relative w-full max-w-lg bg-popover rounded-[2rem] border border-border shadow-2xl animate-in fade-in zoom-in-95 duration-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-border bg-muted/30 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black italic tracking-tight" x-text="isEditingAddress ? 'Edit Location' : 'New Address Record'"></h3>
                        <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mt-1">Village & Territory Linkage</p>
                    </div>
                    <button @click="showAddressModal = false" class="ui-icon-button-ghost"><svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="ui-label text-[10px] font-black uppercase tracking-widest">Address Type</label>
                            <select x-model="addressForm.type" class="ui-select py-2.5">
                                <option value="shipping">Shipping</option>
                                <option value="billing">Billing</option>
                                <option value="both">Both</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="ui-label text-[10px] font-black uppercase tracking-widest">Village Master</label>
                            <select x-model="addressForm.village_id" class="ui-select py-2.5">
                                <option value="">Select Territory</option>
                                @foreach(\App\Models\Village::orderBy('village_name')->get() as $village)
                                    <option value="{{ $village->id }}">{{ $village->village_name }} ({{ $village->pincode }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="ui-label text-[10px] font-black uppercase tracking-widest">Street Address</label>
                        <input type="text" x-model="addressForm.address_line1" class="ui-input py-3" placeholder="House/Shop no, Landmark">
                    </div>
                    <div class="grid grid-cols-2 gap-6 pt-2">
                        <div class="space-y-2">
                            <label class="ui-label text-[10px] font-black uppercase tracking-widest">Contact Name</label>
                            <input type="text" x-model="addressForm.contact_name" class="ui-input py-3">
                        </div>
                        <div class="space-y-2">
                            <label class="ui-label text-[10px] font-black uppercase tracking-widest">Contact Phone</label>
                            <input type="text" x-model="addressForm.contact_phone" class="ui-input py-3">
                        </div>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer group pt-2">
                        <input type="checkbox" x-model="addressForm.is_default" class="ui-checkbox size-5 border-2">
                        <span class="text-xs font-bold text-foreground group-hover:text-primary transition-colors">Mark as Primary Address</span>
                    </label>
                </div>
                <div class="px-8 py-6 bg-muted/30 border-t border-border flex justify-end gap-3">
                    <button @click="showAddressModal = false" class="ui-button-secondary py-3 px-6 h-auto">Cancel</button>
                    <button @click="submitAddress()" class="ui-button-primary py-3 px-8 h-auto" x-text="isEditingAddress ? 'Update Record' : 'Save Address'"></button>
                </div>
            </div>
        </div>
    </div>
@endsection
