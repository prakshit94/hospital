<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customer = $this->route('customer');
        $customerId = is_object($customer) ? $customer->id : $customer;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'mobile' => ['required', 'string', Rule::unique('customers')->ignore($customerId)],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers')->ignore($customerId)],
            'alternate_email' => ['nullable', 'email', 'max:255'],
            'phone_number_2' => ['nullable', 'string', 'max:20'],
            'relative_phone' => ['nullable', 'string', 'max:20'],
            'type' => ['required', 'in:farmer,buyer,vendor,dealer'],
            'category' => ['required', 'in:individual,business'],
            'customer_group' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'required_if:category,business', 'string', 'max:255'],
            'gst_number' => ['nullable', 'string', 'max:20', Rule::unique('customers')->ignore($customerId)],
            'pan_number' => ['nullable', 'string', 'max:10'],
            'land_area' => ['nullable', 'numeric', 'min:0'],
            'land_unit' => ['nullable', 'string', 'max:20'],
            'irrigation_type' => ['nullable', 'string', 'max:255'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'payment_terms_days' => ['nullable', 'integer', 'min:0'],
            'aadhaar_last4' => ['nullable', 'string', 'size:4'],
            'lead_status' => ['nullable', 'in:lead,converted,inactive'],
            'crops' => ['nullable', 'array'],
            'crops.*' => ['string', 'max:255'],
            'crops_input' => ['nullable', 'string'],
            'internal_notes' => ['nullable', 'string'],
        ];
    }
}
