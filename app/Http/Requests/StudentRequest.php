<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|unique:users,email,' . $this->student,
            'mobile' => 'nullable|string|max:20',
            'class_id' => 'required|exists:classes,id',
            'admission_number' => 'required|unique:students,admission_number,' . $this->student,
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
        ];
    }
}