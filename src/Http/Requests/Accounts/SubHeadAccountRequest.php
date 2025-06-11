<?php

namespace XelentAbrar\HospitalAccounts\Http\Requests\Accounts;

use XelentAbrar\HospitalAccounts\Models\Accounts\SubControlAccount;
use Illuminate\Foundation\Http\FormRequest;

class SubHeadAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'coa2_id' => 'required|exists:' . SubControlAccount::class . ',id',
            'acc_desc' => 'required|string',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'coa2_id' => 'sub control account',
        ];
    }
}
