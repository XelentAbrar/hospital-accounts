<?php

namespace XelentAbrar\HospitalAccounts\Http\Requests\Accounts;

use XelentAbrar\HospitalAccounts\Models\Accounts\AccountCode;
use Illuminate\Foundation\Http\FormRequest;

class ChartOfAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'coa4_id' => 'required|exists:' . AccountCode::class . ',id',
            'acc_desc' => 'required|string',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'coa4_id' => 'account code',
        ];
    }
}
