<?php

namespace XelentAbrar\HospitalAccounts\Http\Controllers;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalAccounts\Models\AccountSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountSettingController extends Controller
{
    public function index()
    {
        $settings = AccountSetting::all()->pluck('value', 'key');
        $accountCodes = ChartOfAccount::all(['id', 'acc_code'])->map(function ($account) {
            return ['id' => $account->id, 'name' => $account->acc_code];
        });

        return Inertia::render('AcountSettings', [
            'settings' => $settings,
            'accountCodes' => $accountCodes,
        ]);
    }


    /**
     * Store or update the account settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
{
    // dd($request->all());

    $fixedKeys = ['Advance', 'Cash_In_Hand', 'LAB_RECEIPTS', 'MISC_RECEIPTS', 'ZF_Patients','DISCOUNT'];
    $settingsToUpdate = [];
    foreach ($fixedKeys as $key) {
        if ($request->has($key)) {
            $value = $request->input($key);

            if ($value && is_array($value)) {
                $value = $value['name'];
            }

            if ($value !== null) {
                $settingsToUpdate[] = [
                    'key' => $key,
                    'value' => $value,
                ];
            }
        }
    }
    if (!empty($settingsToUpdate)) {
        foreach ($settingsToUpdate as $setting) {
            AccountSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
    return redirect()->back()->with('success', 'Settings updated successfully.');
}


}
