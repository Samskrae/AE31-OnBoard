<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpotRequest extends FormRequest
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
            'nombre' => 'sometimes|required|string|max:255',
            'lat' => 'sometimes|required|numeric|between:-90,90',
            'lon' => 'sometimes|required|numeric|between:-180,180',
            'descripcion' => 'sometimes|required|string|max:1000',
            'nivel' => 'sometimes|required|string|in:beginner,intermediate,advanced,expert',
            'imagen' => 'nullable|string|url|max:2048',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del spot es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'lat.required' => 'La latitud es obligatoria.',
            'lat.numeric' => 'La latitud debe ser un número.',
            'lat.between' => 'La latitud debe estar entre -90 y 90.',
            'lon.required' => 'La longitud es obligatoria.',
            'lon.numeric' => 'La longitud debe ser un número.',
            'lon.between' => 'La longitud debe estar entre -180 y 180.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser un texto.',
            'descripcion.max' => 'La descripción no puede exceder 1000 caracteres.',
            'nivel.required' => 'El nivel es obligatorio.',
            'nivel.string' => 'El nivel debe ser un texto.',
            'nivel.in' => 'El nivel debe ser uno de: beginner, intermediate, advanced, expert.',
            'imagen.url' => 'La imagen debe ser una URL válida.',
            'imagen.max' => 'La URL de la imagen no puede exceder 2048 caracteres.',
        ];
    }
}
