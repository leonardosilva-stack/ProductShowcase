<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nutritionalTable'                   => 'nullable|array',
            'nutritionalTable.portionSize'       => 'required_with:nutritionalTable|string|max:10',
            'nutritionalTable.values'            => 'nullable|array',
            'nutritionalTable.values.*.name'     => 'required_with:nutritionalTable.values|string|max:255',
            'nutritionalTable.values.*.quantity' => 'required_with:nutritionalTable.values|string|max:50',
            'nutritionalTable.values.*.unit'     => 'nullable|string|max:10',
            'nutritionalTable.values.*.vd'       => 'nullable|numeric|min:0|max:100',
            'brandId'                            => 'required|exists:brands,_id',
        ];


        // Defina as regras de validação específicas para o método de requisição
        if ($this->isMethod('post')) {
            // Regras para criação
            $rules = array_merge($rules, [
                'title'                              => 'required|string|max:255',
                'image'                              => 'required|image|mimes:jpeg,png,jpg|dimensions:width=700,height=700|max:2048',
            ]);
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Regras para atualização
            $rules = array_merge($rules, [
                'title'                              => 'nullable|string|max:255',
                'image'                              => 'nullable|image|mimes:jpeg,png,jpg|dimensions:width=700,height=700|max:2048',
            ]);
        }


        return $rules;
    }

    public function messages()
    {
        return [
            'image.dimensions'                                 => 'A imagem deve ter exatamente 700x700 pixels.',
            'nutritionalTable.portionSize.required_with'       => 'O tamanho da porção é obrigatório quando a tabela nutricional está presente.',
            'nutritionalTable.values.*.name.required_with'     => 'O nome do valor nutricional é obrigatório.',
            'nutritionalTable.values.*.quantity.required_with' => 'A quantidade do valor nutricional é obrigatória.',
        ];
    }
}
