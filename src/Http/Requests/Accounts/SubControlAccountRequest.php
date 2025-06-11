<?php

namespace XelentAbrar\HospitalAccounts\Http\Requests\Accounts;

use XelentAbrar\HospitalAccounts\Models\Accounts\ControlAccount;
use Illuminate\Foundation\Http\FormRequest;

class SubControlAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'coa1_id' => 'required|exists:' . ControlAccount::class . ',id',
            // 'acc_code' => 'required|string|max:255',
            'acc_desc' => 'required|string',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'coa1_id' => 'control account',
        ];
    }
}
