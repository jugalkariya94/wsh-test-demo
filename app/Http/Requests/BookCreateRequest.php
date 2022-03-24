<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'title' => 'required',
            'author' => 'required',
            'price' => 'required|numeric',
            'compare_at_price' => 'numeric|nullable|gt:price',
            'no_of_pages' => 'required|numeric',
            'wholesale_price' => 'required|numeric|lt:price',
            'body_html' => 'required',
            'image' => 'required|image'
        ];
    }
}
