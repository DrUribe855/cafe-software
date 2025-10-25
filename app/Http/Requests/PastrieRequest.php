<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PastrieRequest extends FormRequest
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
        return [
            'establishment_id' => 'required|integer',
            'user_id' => 'required|integer',
            'schedule' => 'required|string',
            // 'file' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages(){
        return [
            'establishment_id.required' => 'El ID del establecimiento es obligatorio',
            'establishment_id.integer' => 'El ID del establecimiento debe ser un número entero',
            'user_id.required' => 'El ID del usuario es obligatorio',
            'user_id.integer' => 'El ID del usuario debe ser un número entero',
            'schedule.required' => 'El horario es obligatorio',
            'schedule.string' => 'El horario debe ser una cadena de texto',
            'file.required' => 'La imagen es obligatoria',
            'file.image' => 'El archivo debe ser una imagen',
            'file.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg',
            // 'file.max' => 'La imagen no debe superar los 2MB',
        ];
    }
}
