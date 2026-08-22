<?php

namespace App\Http\Requests\Backend\Products;

use App\Models\Backend\Products\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:191'],
            'type' => ['required', Rule::in(array_keys(Product::TYPES))],
            'gift_for' => ['nullable', Rule::in(array_keys(Product::GIFT_FOR))],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],

            // qty is edited directly here now (no more stock_movements log)
            'qty' => ['required', 'integer', 'min:0'],

            'meta_title' => ['nullable', 'string', 'max:191'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],

            'colors' => ['nullable', 'array'],
            'colors.*.color_name' => ['nullable', 'string', 'max:100'],
            'colors.*.color_code' => ['nullable', 'string', 'max:20'],
            'colors.*.images' => ['nullable', 'array'],
            'colors.*.images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
