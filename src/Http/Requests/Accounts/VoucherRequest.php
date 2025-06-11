<?php

namespace XelentAbrar\HospitalAccounts\Http\Requests\Accounts;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherType;
use App\Rules\DateRequest;
use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'voucher_type_id' => 'required|exists:' . VoucherType::class . ',id',
            'voucher_date' =>  ['required', 'date', new DateRequest],
            'voucher_no' => 'required|string',
            'narration' => 'nullable',
            'is_printed' => 'nullable',
        ];

        $rules['voucher_details'] = ['required', 'array', 'min:2'];
        $rules['voucher_details.*.chart_of_account.id'] = ['required', 'exists:' . ChartOfAccount::class . ',id'];
        $rules['voucher_details.*.dr'] = ['nullable'];
        $rules['voucher_details.*.cr'] = ['nullable'];
        $rules['voucher_details.*.transaction_type'] = ['nullable'];
        $rules['voucher_details.*.transaction_no'] = ['nullable', 'string'];
        $rules['voucher_details.*.remarks'] = ['nullable', 'string'];

        $rules['voucher_details'] = ['required', function ($attribute, $value, $fail) {
            $totalDr = collect($value)->sum('dr');
            $totalCr = collect($value)->sum('cr');

            if ($totalDr != $totalCr) {
                $fail("The sum of debit and credit amounts must be equal.");
            }
        }];

        return $rules;
    }

    public function attributes()
    {
        return [
            'voucher_type_id' => 'voucher type',
            'voucher_no' => 'voucher number',
            'voucher_details.*.chart_of_account.id' => 'account',
            'voucher_details.*.dr' => 'debit',
            'voucher_details.*.cr' => 'credit',
            'voucher_details.*.transaction_type' => 'transaction type',
            'voucher_details.*.transaction_no' => 'transaction #',
            'voucher_details.*.remarks' => 'remarks',
        ];
    }
}
