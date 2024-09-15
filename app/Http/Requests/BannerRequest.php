<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'desktopImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tabletImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'mobileImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'link' => 'nullable|url',
            'expirationDate' => 'nullable|date',
        ];
    }
}
