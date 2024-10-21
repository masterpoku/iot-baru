<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        if(!in_array($this->method(), ['PUT', 'PATCH'])) {
            return [];
        }

        $rules = [
            'name' => 'required',
            'email' => 'required',
            
        ];

        if($this->isMethod('PATCH')) {
            $rules['email'] = 'required|unique:users,email,'. $this->id;
        }

        return $rules;
    }
}
