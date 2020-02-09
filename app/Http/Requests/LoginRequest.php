<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'remember_me' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'El email es necesario',
            'email.email' => 'Un email valido es prueba@dominio.com',
            'password.required' => 'La contraseña es necesaria',
            'password.min' => 'La contraseña debe tener minimo 6 caracteres',
        ];
    }
}
