<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReleaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if($this->type == 'completed'){
            return [
                'attachment' => 'required',
                'attachment.*' => 'mimes:pdf|max:2000'
            ];
        }else{
            return [
                'lists' => 'required|min:1',
                'dv' => 'required',
                'dv_no' => 'required_if:dv,true',
                'total' => 'required'
            ];
        }
    }
}
