<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        /* Recuperamos el ID del usuario enviado por la ruta */

        $userId = $this->route('id'); 


        /* Validamos los campos del formulario, a su vez, validamos el tipo de petición para
        los campos de documento, email y password, para asi saber que hacer dependiendo
        el tipo de petición */

        return [
            'document' => [
                            'required', 
                            'numeric',
                            $this->isMethod('post')
                                    ? Rule::unique('users', 'document')
                                    : Rule::unique('users', 'document')->ignore($userId)
                        ],
            'name'     => 'required | max:60',
            'email'    => [
                            'required',
                            'email',
                            'max:50',
                            $this->isMethod('post')
                                    ? Rule::unique('users', 'email')
                                    : Rule::unique('users', 'email')->ignore($userId)
                        ],
            'role'     => 'required',
            'status'   => 'required | max:250',
            'password' => $this->isMethod('post') ? 'required' : 'nullable',
        ];
    }

    /* Mensajes retornados */

    public function messages(){
        return [
            'document.required' => 'El DNI es obligatorio',
            'document.numeric'  => 'Los datos ingresados no corresponden a un DNI',
            'document.unique'   => 'El DNI ya se encuentra registrado',
            'name.required'     => 'El nombre es obligatorio',
            'name.max'          => 'El nombre no puede ser mayor a 60 caracteres',
            'email.required'    => 'El email es obligatorio',
            'email.email'       => 'El email ingresado no corresponde a un correo válido',
            'email.unique'      => 'El email ya se encuentra registrado',
            'role.required'     => 'Debe indicar un rol para el usuario',
            'status.required'   => 'Debe indicar un estado para el usuario',
            'password.required' => 'La contraseña es obligatoria',
        ];
    }
}
