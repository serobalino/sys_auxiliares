<?php

namespace App\Http\Requests\Comprobantes\Subir;

use Illuminate\Foundation\Http\FormRequest;

class ReporteRequest extends FormRequest
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
            'archivo'       => 'required|file|mimes:txt',
            'cliente'       => 'required|exists:clientes,id_cl',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'archivo' => 'Archivo',
            'cliente' => 'Cliente',
        ];
    }
}
