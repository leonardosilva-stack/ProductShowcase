<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigurationRequest extends FormRequest
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
    return [
      'siteTitle'       => 'required|string|max:255',
      'siteDescription' => 'nullable|string|max:1000',
      'socialLinks'     => 'nullable|array',
      'aboutText'       => 'nullable|string',
      'image'           => 'required|image|mimes:jpeg,png,jpg|max:2048',
      'logo'            => 'required|image|mimes:jpeg,png,jpg|max:2048', // Alterado para validar imagem
      'createdAt'       => 'nullable|date',
      'updatedAt'       => 'nullable|date',
    ];
  }

  public function messages()
  {
    return [
      'siteTitle.required' => 'The site title is required.',
      'siteTitle.string'   => 'The site title must be a string.',
      'image.required'     => 'The image is required.',
      'image.image'        => 'The file must be an image.',
      'image.mimes'        => 'The image must be a file of type: jpeg, png, jpg.',
      'image.max'          => 'The image may not be greater than 2048 kilobytes.',
      'logo.image'         => 'The logo must be an image.',
      'logo.mimes'         => 'The logo must be a file of type: jpeg, png, jpg.',
      'logo.max'           => 'The logo may not be greater than 2048 kilobytes.',
    ];
  }
}
