<?php

namespace App\Http\Requests\Comprobantes\Lista;

use Illuminate\Foundation\Http\FormRequest;

class RangoRequest extends FormRequest
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
            'desde'         =>  'nullable|date',
            'hasta'         =>  'nullable|date|after:desde',
            'comprobante'   =>  'nullable|array',
            'comprobante.*' =>  'exists:tipos_comprobante,id_tc',
        ];
    }
}
