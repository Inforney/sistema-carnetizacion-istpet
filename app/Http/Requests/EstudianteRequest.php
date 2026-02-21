<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\CedulaValidator;
use Illuminate\Validation\Validator;

class EstudianteRequest extends FormRequest
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
        $usuarioId = $this->route('id');

        return [
            'tipo_documento' => 'required|in:cedula,pasaporte',
            'cedula' => [
                'required',
                'string',
                'max:13',
                $usuarioId ? "unique:usuarios,cedula,{$usuarioId}" : 'unique:usuarios,cedula',
            ],
            'nombres' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellidos' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'nacionalidad' => 'required|string|max:50',
            'carrera' => 'required|string|max:100',
            'semestre' => 'required|in:PRIMER NIVEL,SEGUNDO NIVEL,TERCER NIVEL,CUARTO NIVEL,QUINTO NIVEL,SEXTO NIVEL',
            'correo' => [
                'required',
                'email',
                'max:255',
                $usuarioId ? "unique:usuarios,correo,{$usuarioId}" : 'unique:usuarios,correo',
            ],
            'celular' => 'required|digits:10|regex:/^09[0-9]{8}$/',
            'direccion' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date|before:-18 years',
            'password' => $usuarioId ? 'nullable|min:8' : 'required|min:8',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'cedula.required' => 'El número de cédula/pasaporte es obligatorio',
            'cedula.unique' => 'Esta cédula/pasaporte ya está registrada en el sistema',
            'nombres.required' => 'Los nombres son obligatorios',
            'nombres.regex' => 'Los nombres solo pueden contener letras y espacios',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'Debe ser un correo electrónico válido',
            'correo.unique' => 'Este correo ya está registrado en el sistema',
            'celular.required' => 'El número de celular es obligatorio',
            'celular.digits' => 'El celular debe tener exactamente 10 dígitos',
            'celular.regex' => 'El celular debe empezar con 09',
            'fecha_nacimiento.before' => 'Debe ser mayor de 18 años',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'foto_perfil.image' => 'El archivo debe ser una imagen',
            'foto_perfil.mimes' => 'La imagen debe ser JPG, JPEG o PNG',
            'foto_perfil.max' => 'La imagen no puede pesar más de 2MB',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Validar cédula ecuatoriana si el tipo es cédula
            if ($this->tipo_documento === 'cedula') {
                $cedula = $this->cedula;

                if (!CedulaValidator::validar($cedula)) {
                    $validator->errors()->add(
                        'cedula',
                        'El número de cédula ecuatoriana no es válido. Verifique que sea correcto.'
                    );
                }
            }

            // Validar pasaporte (formato básico)
            if ($this->tipo_documento === 'pasaporte') {
                $pasaporte = $this->cedula;

                // Pasaportes generalmente tienen letras y números, 6-13 caracteres
                if (!preg_match('/^[A-Z0-9]{6,13}$/i', $pasaporte)) {
                    $validator->errors()->add(
                        'cedula',
                        'El formato del pasaporte no es válido'
                    );
                }
            }
        });
    }
}
