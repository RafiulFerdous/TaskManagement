<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
        ];
    }

    /**
     * persist form data
     */
    public function persist(): array
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'image'=>$this->image
        ];
    }
}
