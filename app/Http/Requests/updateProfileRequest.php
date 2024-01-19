<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class updateProfileRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'id'=>['required'],
            'email' => ['regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/',Rule::unique('users')->ignore(request()->route('id'))],
            'name' => ['required', 'string', 'between:3,255'],
            'photo' => ['max:1024', 'mimes:png,jpg,jpeg'],
            'phone' =>   ['required', 'string', 'between:3,255',Rule::unique('users')->ignore(request()->route('id'))],
        ];
    }
}
