<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'selling_price' => 'required|numeric',
            'mrp' => 'required|numeric|gte:selling_price',
            'label' => 'required|array|min:8',
            'images' => 'required|array|min:1',
            // 'multipleImages' => 'image|mimes:jpeg,png,jpg,gif,svg|array|min:1',
            'sku' => 'required|string|max:255',
            'publication' => 'required|string|max:255',
            'author_name' => 'required|string',
            'edition' => 'required|string|max:255',
            'binding' => 'required|string|max:255',
            'condition' => 'required|string|max:255',
            'pages' => 'required|string',
            'language' => 'required|string|max:255',
        ];
    }
}
