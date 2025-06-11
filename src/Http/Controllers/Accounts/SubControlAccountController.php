<?php

namespace XelentAbrar\HospitalAccounts\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use XelentAbrar\HospitalAccounts\Http\Requests\Accounts\SubControlAccountRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\ControlAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\SubControlAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SubControlAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:sub-control-accounts.index')->only('index');
        $this->middleware('checkPermission:sub-control-accounts.create')->only('create', 'store');
        $this->middleware('checkPermission:sub-control-accounts.show')->only('show');
        $this->middleware('checkPermission:sub-control-accounts.edit')->only('edit', 'update');
        $this->middleware('checkPermission:sub-control-accounts.destroy')->only('destroy');
    }
    public function index()
    {
        $sub_control_accounts = SubControlAccount::with('controlAccount:id,acc_desc')->orderBy('acc_code', 'asc')->paginate(100);
        return Inertia::render('Accounts/SubControlAccounts/Index', [
            'sub_control_accounts' => $sub_control_accounts,
        ]);
    }

    public function create()
    {
        $control_accounts = ControlAccount::select('id', 'acc_desc')->get();
        return Inertia::render(
            'Accounts/SubControlAccounts/Create',
            [
                'control_accounts' => $control_accounts
            ]
        );
    }

    public function store(SubControlAccountRequest $request)
    {
        DB::beginTransaction();

        $sub_control_account = new SubControlAccount();
        $formData = $request->only($sub_control_account->getFillable());
        $formData['created_by'] = Auth::user()->id;

        SubControlAccount::create($formData);

        DB::commit();

        return redirect()->route('sub-control-accounts.index');
    }


    public function edit(SubControlAccount $sub_control_account)
    {
        $control_accounts = ControlAccount::select('id', 'acc_desc')->get();
        return Inertia::render('Accounts/SubControlAccounts/Create', [
            'sub_control_account' => $sub_control_account,
            'control_accounts' => $control_accounts,
        ]);
    }


    public function update(SubControlAccountRequest $request, SubControlAccount $sub_control_account)
    {
        DB::beginTransaction();

        $formData = $request->only($sub_control_account->getFillable());
        $formData['updated_by'] = Auth::user()->id;
        $sub_control_account->update($formData);

        DB::commit();

        return redirect()->route('sub-control-accounts.index')->with('message', 'Sub control account updated successfully.');
    }


    public function destroy($id)
    {
        $sub_control_account = SubControlAccount::findOrFail($id);
        $sub_control_account->delete();

        return redirect()->route('sub-control-accounts.index')->with('success', 'Sub control account deleted successfully.');
    }
}
