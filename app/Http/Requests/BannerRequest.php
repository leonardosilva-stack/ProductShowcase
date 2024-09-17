<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BannerRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    $rules = [
      'description'    => 'nullable|string',
      'link'           => 'nullable|url',
      'expirationDate' => 'nullable|date',
    ];

    // Defina as regras de validação específicas para o método de requisição
    if ($this->isMethod('post')) {
      // Regras para criação
      $rules = array_merge($rules, [
        'title'          => 'required|string',
        'desktopImage'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'tabletImage'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'mobileImage'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
      ]);
    } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
      // Regras para atualização
      $rules = array_merge($rules, [
        'title'          => 'nullable|string',
        'desktopImage'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'tabletImage'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'mobileImage'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      ]);
    }

    return $rules;
  }
}
