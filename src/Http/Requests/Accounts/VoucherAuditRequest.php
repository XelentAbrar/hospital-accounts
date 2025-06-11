<?php

namespace XelentAbrar\HospitalAccounts\Http\Requests\Accounts;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherType;
use App\Rules\DateRequest;
use Illuminate\Foundation\Http\FormRequest;

class VoucherAuditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'voucher_type_id' => 'required|exists:' . VoucherType::class . ',id',
            'voucher_date' =>  ['required', 'date'],
            'narration' => 'nullable',
        ];

        $rules['voucher_audit_details'] = ['required', 'array', 'min:2'];
        $rules['voucher_audit_details.*.chart_of_account.id'] = ['required', 'exists:' . ChartOfAccount::class . ',id'];
        $rules['voucher_audit_details.*.dr'] = ['nullable'];
        $rules['voucher_audit_details.*.cr'] = ['nullable'];
        $rules['voucher_audit_details.*.transaction_type'] = ['nullable'];
        $rules['voucher_audit_details.*.transaction_no'] = ['nullable', 'string'];
        $rules['voucher_audit_details.*.remarks'] = ['nullable', 'string'];

        $rules['voucher_audit_details'] = ['required', function ($attribute, $value, $fail) {
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
            'voucher_audit_details.*.chart_of_account.id' => 'account',
            'voucher_audit_details.*.dr' => 'debit',
            'voucher_audit_details.*.cr' => 'credit',
            'voucher_audit_details.*.transaction_type' => 'transaction type',
            'voucher_audit_details.*.transaction_no' => 'transaction #',
            'voucher_audit_details.*.remarks' => 'remarks',
        ];
    }
}
