<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class TercerosDireccionFormRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    
    public function rules(){
        return [
            'tercero_id' => 'required',
			'nombre_personalizado' => 'required',
			'direccion' => 'required',
			'barrio' => 'required',
			'numero_contacto' => 'required',
			'country_code' => 'required',
			'state_code' => 'required',
			'city_code' => 'required',
        ];
    }
    public function messages(){
		return [
			'tercero_id.required' => 'Debe seleccionar un tercero',
			'nombre_personalizado.required' => 'Debe ingresar nombre personalizado',
			'direccion.required' => 'Debe ingresar una dirección',
			'barrio.required' => 'Debe ingresar un barrio',
			'numero_contacto.required' => 'Debe ingresar número de contacto',
			'country_code.required' => 'Debe seleccionar un país',
			'state_code.required' => 'Debe seleccionar un estado',
			'city_code.required' => 'Debe seleccionar una ciudad',
		];
	}
    protected function failedValidation(Validator $validator){
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json([
                'response' => 500,
                'message' => 'Error en validación de los campos [' . $errors[array_key_first($errors)][0] . ']',
                'data' => $errors
            ], 500)
        );
    }
}
