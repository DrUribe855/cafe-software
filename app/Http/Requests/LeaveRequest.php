<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
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
            'type'      => 'required|string|in:Vacaciones,Permiso',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:startDate',
            'reason'    => 'nullable|string|max:500'
        ];
    }

    /* Mensajes retornados */

    public function messages(){
        return [
            'type.required'            => 'El tipo de solicitud es obligatorio.',
            'type.in'                  => 'El tipo de solicitud debe ser Vacaciones o Permiso.',
            'startDate.required'       => 'La fecha de inicio es obligatoria.',
            'startDate.date'           => 'La fecha de inicio debe ser una fecha válida.',
            'startDate.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',
            'endDate.required'         => 'La fecha de fin es obligatoria.',
            'endDate.date'             => 'La fecha de fin debe ser una fecha válida.',
            'endDate.after_or_equal'   => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
            'reason.max'               => 'La razón no puede exceder los 500 cáracteres.',
        ];
    }
}
