<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScholarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if($this->type == 'create'){
            return [
                'program_id' => 'sometimes|required',
                'subprogram_id' => 'sometimes|required',
                'category_id' => 'sometimes|required',
                'awarded_year' => 'sometimes|required',
                'started_year' => 'sometimes|required',
                'firstname' => 'sometimes|required',
                'middlename' => 'sometimes|required',
                'lastname' => 'sometimes|required',
                'suffix' => 'sometimes|nullable',
                'account_no' => 'sometimes|required',
                'contact_no' => 'sometimes|required',
                'email' => 'sometimes|required',
                'sex' => 'sometimes|required',
                'birthday' => 'sometimes|required',
                'school_id' => 'sometimes|required',
                'course_id' => 'sometimes|required',
                'level_id' => 'sometimes|required',
                'region_code' => 'sometimes|required',
                'province_code' => 'sometimes|required',
                'municipality_code' => 'sometimes|required',
                'barangay_code' => 'sometimes|required',
                'address' => 'sometimes|required',
            ];
        }else{
            return [];
        }
    }
}
