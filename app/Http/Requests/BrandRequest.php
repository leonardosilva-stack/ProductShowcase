<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function wantsJson()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|unique:brands,name',
            'products' => 'nullable|array',
        ];

        if ($this->isMethod('put')) {
            $brandId = $this->route('id');
            $rules['name'] = [
                'required',
                Rule::unique('brands', 'name')->ignore($brandId),
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The brand name is required.',
            'name.unique' => 'This brand name already exists. Please choose another name.',
        ];
    }
}
