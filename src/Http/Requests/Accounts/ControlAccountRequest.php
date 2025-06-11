<?php

namespace XelentAbrar\HospitalAccounts\Http\Requests\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class ControlAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'acc_desc' => 'required|string',
        ];

        return $rules;
    }
}
