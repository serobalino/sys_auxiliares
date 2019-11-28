<?php

namespace App\Http\Requests\Comprobantes\Subir;

use Illuminate\Foundation\Http\FormRequest;

class XmlRequest extends FormRequest
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
            'archivos'      => 'required|array',
            'archivos.*'    => 'file|mimes:xml',
            'cliente'       => 'required|exists:clientes,id_cl',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'archivos' => 'XMLs',
            'cliente' => 'Cliente',
        ];
    }
}
