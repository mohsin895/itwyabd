<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'sale_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            
           
        ];
    }

       public function messages()
    {
        return [
            'user_id.required' => 'Please select a customer.',
            'user_id.exists' => 'Selected customer does not exist.',
            'sale_date.required' => 'Sale date is required.',
            'sale_date.date' => 'Please provide a valid date.',
            'items.required' => 'At least one item is required.',
            'items.min' => 'At least one item is required.',
            'items.*.product_id.required' => 'Product selection is required for all items.',
            'items.*.product_id.exists' => 'One or more selected products do not exist.',
            'items.*.quantity.required' => 'Quantity is required for all items.',
            'items.*.quantity.integer' => 'Quantity must be a valid number.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.unit_price.required' => 'Unit price is required for all items.',
            'items.*.unit_price.numeric' => 'Unit price must be a valid number.',
            'items.*.unit_price.min' => 'Unit price cannot be negative.',
            'items.*.discount_percentage.numeric' => 'Discount must be a valid number.',
            'items.*.discount_percentage.min' => 'Discount cannot be negative.',
            'items.*.discount_percentage.max' => 'Discount cannot exceed 100%.',
        ];
    }

  
}
