<?php

namespace XelentAbrar\HospitalAccounts\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use XelentAbrar\HospitalAccounts\Http\Requests\Accounts\AccountCodeRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\SubHeadAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\AccountCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AccountCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:account-codes.index')->only('index');
        $this->middleware('checkPermission:account-codes.create')->only('create', 'store');
        $this->middleware('checkPermission:account-codes.show')->only('show');
        $this->middleware('checkPermission:account-codes.edit')->only('edit', 'update');
        $this->middleware('checkPermission:account-codes.destroy')->only('destroy');
    }
    public function index()
    {
        $account_codes = AccountCode::with('subHeadAccount:id,acc_desc')->orderBy('acc_code', 'asc')->paginate(100);
        return Inertia::render('Accounts/AccountCodes/Index', [
            'account_codes' => $account_codes,
        ]);
    }

    public function create()
    {
        $sub_head_accounts = SubHeadAccount::select('id', 'acc_desc')->get();
        return Inertia::render(
            'Accounts/AccountCodes/Create',
            [
                'sub_head_accounts' => $sub_head_accounts
            ]
        );
    }

    public function store(AccountCodeRequest $request)
    {
        DB::beginTransaction();

        $account_code = new AccountCode();
        $formData = $request->only($account_code->getFillable());
        $formData['created_by'] = Auth::user()->id;

        AccountCode::create($formData);

        DB::commit();

        return redirect()->route('account-codes.index');
    }


    public function edit(SubHeadAccount $account_code)
    {
        $sub_head_accounts = SubHeadAccount::select('id', 'acc_desc')->get();
        return Inertia::render('Accounts/AccountCodes/Create', [
            'account_code' => $account_code,
            'sub_head_accounts' => $sub_head_accounts,
        ]);
    }


    public function update(AccountCodeRequest $request, AccountCode $account_code)
    {
        DB::beginTransaction();

        $formData = $request->only($account_code->getFillable());
        $formData['updated_by'] = Auth::user()->id;
        $account_code->update($formData);

        DB::commit();

        return redirect()->route('account-codes.index')->with('message', 'Sub head account updated successfully.');
    }


    public function destroy($id)
    {
        $account_code = AccountCode::findOrFail($id);
        $account_code->delete();

        return redirect()->route('account-codes.index')->with('success', 'Sub head account deleted successfully.');
    }
}
