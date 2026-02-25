<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\V1\Auth\AuthRequest;
class RegistrationRequest extends AuthRequest
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
        $rules = parent::rules();

        $rules['email'][] = 'unique:users,email';
        $rules['password'][] = 'confirmed';

        $rules['first_name']   = ['required', 'string', 'max:255'];
        $rules['last_name']    = ['required', 'string', 'max:255'];
        $rules['phone_number'] = ['required', 'string', 'max:20'];
        $rules['birth_day']    = ['required', 'date'];
        $rules['street']       = ['required', 'string'];
        $rules['city']         = ['required', 'string'];
        $rules['state']        = ['required', 'string'];
        $rules['country']      = ['required', 'string'];
        $rules['zip_code']     = ['required', 'string', 'max:20'];
        return $rules;
    }
}
