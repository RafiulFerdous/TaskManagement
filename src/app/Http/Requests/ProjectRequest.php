<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProjectRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'sometimes',
        ];
    }

    public function persist(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status ?? 1,
            'deadline' => $this->deadline,
            'priority_level' => $this->priority_level??1

        ];
    }
}
