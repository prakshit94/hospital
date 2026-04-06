<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerAddressController extends Controller
{
    public function store(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'type' => 'required|in:billing,shipping,both',
            'label' => 'nullable|string|max:100',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'village' => 'nullable|string|max:255',
            'taluka' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'post_office' => 'nullable|string|max:255',
            'village_id' => 'nullable|exists:villages,id',
            'is_default' => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($validated, $customer, $request) {

            $isDefault = $validated['is_default'] ?? false;

            // Reset previous default addresses
            if ($isDefault) {
                $customer->addresses()
                    ->whereNull('deleted_at')
                    ->update(['is_default' => false]);
            }

            // Create address
            $address = CustomerAddress::create([
                ...$validated,
                'customer_id' => $customer->id,
                'country' => $validated['country'] ?? 'India',
                'created_by' => auth()->id(),
            ]);

            // Set primary address if needed
            if ($isDefault || !$customer->primary_address_id) {
                $customer->update(['primary_address_id' => $address->id]);
            }

            // Activity log
            ActivityLogService::logWithChanges(
                auth()->user(),
                $address,
                'customer_address.created',
                "Added address to customer: {$customer->display_name}"
            );

            $msg = "Address added successfully for {$customer->display_name}.";

            if (!$request->ajax()) {
                session()->flash('status', $msg);
            }

            return response()->json([
                'status' => 'success',
                'message' => $msg,
                'address' => $address->load('village')
            ]);
        });
    }

    public function update(Request $request, CustomerAddress $address): JsonResponse
    {
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'type' => 'required|in:billing,shipping,both',
            'label' => 'nullable|string|max:100', // Home, Office, Farm, Warehouse
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'village' => 'nullable|string|max:255',
            'taluka' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'post_office' => 'nullable|string|max:255',
            'village_id' => 'nullable|exists:villages,id',
            'is_default' => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($validated, $address, $request) {

            $customer = $address->customer;
            $isDefault = $validated['is_default'] ?? false;

            // Reset other default addresses
            if ($isDefault) {
                $customer->addresses()
                    ->where('id', '!=', $address->id)
                    ->whereNull('deleted_at')
                    ->update(['is_default' => false]);
            }

            // Update address
            $address->fill([
                ...$validated,
                'updated_by' => auth()->id(),
            ]);

            $address->save();

            // Update primary address
            if ($isDefault) {
                $customer->update(['primary_address_id' => $address->id]);
            }

            // Activity log
            ActivityLogService::logWithChanges(
                auth()->user(),
                $address,
                'customer_address.updated',
                "Updated address for customer: {$customer->display_name}"
            );

            $msg = "Address updated for {$customer->display_name}.";

            if (!$request->ajax()) {
                session()->flash('status', $msg);
            }

            return response()->json([
                'status' => 'success',
                'message' => $msg
            ]);
        });
    }

    public function destroy(CustomerAddress $address): JsonResponse
    {
        return DB::transaction(function () use ($address) {

            $customer = $address->customer;
            $addressName = $address->address_line1;

            $address->delete();

            // If deleted address was primary, assign new one
            if ($customer->primary_address_id === $address->id) {
                $newPrimary = $customer->addresses()
                    ->whereNull('deleted_at')
                    ->first();

                $customer->update([
                    'primary_address_id' => $newPrimary?->id
                ]);
            }

            // Activity log
            ActivityLogService::log(
                auth()->user(),
                'customer_address.deleted',
                null,
                "Deleted address: {$addressName} for customer: {$customer->display_name}"
            );

            $msg = "Address '{$addressName}' has been removed.";

            session()->flash('status', $msg);

            return response()->json([
                'status' => 'success',
                'message' => $msg
            ]);
        });
    }
}