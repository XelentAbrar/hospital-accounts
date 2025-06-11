<?php

namespace XelentAbrar\HospitalAccounts\Http\Requests\Accounts;

use XelentAbrar\HospitalAccounts\Models\Accounts\SubHeadAccount;
use Illuminate\Foundation\Http\FormRequest;

class AccountCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'coa3_id' => 'required|exists:' . SubHeadAccount::class . ',id',
            'acc_desc' => 'required|string',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'coa3_id' => 'sub control account',
        ];
    }
}
