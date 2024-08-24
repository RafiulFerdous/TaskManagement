<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TaskRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'sometimes',
        ];
    }

    /**
     * persist form data
     */
    public function persist(): array
    {
        return [
            'user_id' => (int) ($this->user_id ?? Auth::user()->id),
            'project_id' => (int) ($this->project_id ?? 1),
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status ?? 1,
            'deadline' => $this->deadline,
            'priority_level' => $this->priority_level??1

        ];
    }

    /**
     * persist form data
     */
    public function persistUpdate($task): array
    {
        return [
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'deadline' => $this->deadline,
            'priority_level' => $this->priority_level
        ];
    }
}
