<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('product')->id;
        return [
            'category_id' => 'sometimes|required|exists:categories,id',
            'name'        => 'sometimes|required|string|max:255',
            // Trik unique: unique:table,column,except_id
            'sku'          => 'sometimes|required|string|unique:products,sku,' . $productId,
            'description' => 'nullable|string',
            'stock'       => 'sometimes|required|integer|min:0',
            'price'       => 'sometimes|required|numeric|min:0',
            'min_stock'   => 'sometimes|integer|min:0',
        ];
    }
}
