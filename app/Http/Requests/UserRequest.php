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

        $userId = $this->route('id') ?? $this->route('user');

        return [
            'document' => 'required | numeric',
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

    public function messages(){
        return [
            'document.required' => 'El DNI es obligatorio',
            'document.numeric'  => 'Los datos ingresados no corresponden a un DNI',
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
