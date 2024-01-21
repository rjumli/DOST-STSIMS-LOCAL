<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'sometimes|required',
            'lists' => 'sometimes|required|min:1',
            'files.*' => 'sometimes|required|mimes:pdf,docx|max:2000'
        ];
    }
}
