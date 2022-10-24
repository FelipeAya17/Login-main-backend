<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Validation\Rule;


class TercerosFormRequest extends FormRequest{
    public function authorize(){
        return true;
    }

    public function rules()
    {
        return [
            'tipo_documento_id' => 'required',
			'numero_documento' => [
				'required',
				'max:30',
				Rule::unique('terceros', 'numero_documento')->ignore($this->tercero)
			],
			'nombres' => 'required|max:80',
			'apellidos' => 'required|max:80',
			'correo_electronico' => [
				'required',
				'regex:/(.+)@(.+)\.(.+)/i',
				'max:80',
				Rule::unique('terceros', 'correo_electronico')->ignore($this->tercero)
			],
			'numero_celular' => 'required|max:10',
			'tipo_tercero' => 'required',
        ];
    }
    public function messages(){
		return [
			'tipo_documento_id.required' => 'Debe seleccionar un tipo de documento',
			'numero_documento.required' => 'Debe ingresar un número de documento',
			'numero_documento.max' => 'El número de documento no debe superar los 30 carácteres',
			'numero_documento.unique' => 'El número de documento ya se encuentra registrado',
			'nombres.required' => 'Debe ingresar los nombres del tercero',
			'nombres.max' => 'Los nombres del tercero no debe superar los 80 carácteres',
			'apellidos.required' => 'Debe ingresar los apellidos del tercero',
			'apellidos.max' => 'Los apellidos del tercero no debe superar los 80 carácteres',
			'correo_electronico.required' => 'Debe ingresar un correo electrónico',
			'correo_electronico.regex' => 'Debe ingresar un correo electrónico válido',
			'correo_electronico.max' => 'El correo electrónico no debe superar los 80 carácteres',
			'correo_electronico.unique' => 'El correo electrónico ya se encuentra registrado',
			'numero_celular.unique' => 'Debe ingresar un número celular de contácto',
			'numero_celular.max' => 'El número celular no debe superar los 10 carácteres',
			'tipo_tercero.required' => 'Debe seleccionar un tipo de tercero'
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
