<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'labor_cost' => 'required|numeric|min:0',
            'notes'      => 'nullable|string|max:500',
            
            // Memastikan 'items' ada dan berbentuk array
            'items'      => 'required|array|min:1',
            
            // Validasi setiap item di dalam array
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.product_id.exists' => 'Salah satu produk yang dipilih tidak terdaftar.',
            'items.*.quantity.min'      => 'Jumlah barang minimal adalah 1.',
        ];
    }
}
