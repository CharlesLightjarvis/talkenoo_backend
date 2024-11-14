<?php

namespace App\Http\Requests\auth;

use App\Http\Requests\BaseRequest;

class OtpRequest extends BaseRequest
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
            'otp_code' => 'required|numeric|digits:6',
            'user_id' => 'required|exists:users,id'
        ];
    }
}
