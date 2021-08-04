<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarcaCreateUpdateRequest extends FormRequest
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
            'nome' => 'required|unique:marcas|min:3',
            'imagem' => 'required|file|mimes:png,jpg,jpeg'
        ];

        /*
          Parametros do unique
            tabela
            nome da coluna que sera pesquisada, se for null e o mesmo do input
            3 id do registro que sera desconsiderado na pesquisa
        */
    }

    public function messages()
    {
        return [
            'required' => 'O campo :attribute e obrigatorio',
            'nome.unique' => 'O nome da marca ja existe',
            'nome.min' => 'O tamanho minimo para a marca e de 3 digitos',
            'imagem.file' => 'O campo :attribute precisa ser um arquivo',
            'imagem.mimes' => 'O arquivo precisa ser do tipo, png, jpg ou jpeg'
        ];
    }


}
