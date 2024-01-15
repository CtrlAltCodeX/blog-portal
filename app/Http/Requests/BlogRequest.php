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
            'label' => 'required|array|min:1',
            // 'images' => 'required|array|min:1',
            // 'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sku' => 'required|string|max:255',
            'publication' => 'required|string|max:255',
            'author_name' => 'required|string',
            'about_author' => 'required|string',
            'search_key' => 'required|string',
            'edition' => 'required|string|max:255',
            'medium' => 'required|string|max:255',
            'pages' => 'required|integer|min:1',
            'isbn_10' => 'required|string|max:255',
            'isbn_13' => 'required|string|max:255',
            'weight' => 'required|string|max:255',
            'country_origin' => 'required|string|max:255',
            'language' => 'required|string|max:255',
        ];
    }
}
