<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeriesFormRequest extends FormRequest
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
            'nome' => ['required', 'min:3', 'max:128'],
            'cover' => ['image', 'mimes:png,jpg']
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.min' => 'O campo nome deve ter pelo menos :min caracteres.',
            'nome.max' => 'O campo nome deve ter no máximo :max caracteres.',
            'cover.image' => 'O arquivo deve ser uma imagem.',
            'cover.mimes' => 'A imagem deve ser dos tipos .PNG ou .JPG.',
        ];
    }
}
