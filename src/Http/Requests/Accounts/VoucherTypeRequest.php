<?php

namespace XelentAbrar\HospitalAccounts\Http\Requests\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class VoucherTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'voucher_name' => 'required|string|max:255',
            'voucher_abrv' => 'required|string',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'voucher_abrv' => 'abbreviation',
        ];
    }
}
