<?php

namespace XelentAbrar\HospitalAccounts\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use XelentAbrar\HospitalAccounts\Http\Requests\Accounts\SubHeadAccountRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\SubControlAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\SubHeadAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SubHeadAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:sub-head-accounts.index')->only('index');
        $this->middleware('checkPermission:sub-head-accounts.create')->only('create', 'store');
        $this->middleware('checkPermission:sub-head-accounts.show')->only('show');
        $this->middleware('checkPermission:sub-head-accounts.edit')->only('edit', 'update');
        $this->middleware('checkPermission:sub-head-accounts.destroy')->only('destroy');
    }
    public function index()
    {
        $sub_head_accounts = SubHeadAccount::with('subControlAccount:id,acc_desc')->orderBy('acc_code', 'asc')->paginate(100);
        return Inertia::render('Accounts/SubHeadAccounts/Index', [
            'sub_head_accounts' => $sub_head_accounts,
        ]);
    }

    public function create()
    {
        $sub_control_accounts = SubControlAccount::select('id', 'acc_desc')->get();
        return Inertia::render(
            'Accounts/SubHeadAccounts/Create',
            [
                'sub_control_accounts' => $sub_control_accounts
            ]
        );
    }

    public function store(SubHeadAccountRequest $request)
    {
        DB::beginTransaction();

        $sub_head_account = new SubHeadAccount();
        $formData = $request->only($sub_head_account->getFillable());
        $formData['created_by'] = Auth::user()->id;

        SubHeadAccount::create($formData);

        DB::commit();

        return redirect()->route('sub-head-accounts.index');
    }


    public function edit(SubHeadAccount $sub_head_account)
    {
        // dd($sub_head_account);
        $sub_control_accounts = SubControlAccount::select('id', 'acc_desc')->get();
        return Inertia::render('Accounts/SubHeadAccounts/Create', [
            'sub_head_account' => $sub_head_account,
            'sub_control_accounts' => $sub_control_accounts,
        ]);
    }


    public function update(SubHeadAccountRequest $request, SubHeadAccount $sub_head_account)
    {
        DB::beginTransaction();

        $formData = $request->only($sub_head_account->getFillable());
        $formData['updated_by'] = Auth::user()->id;
        $sub_head_account->update($formData);

        DB::commit();

        return redirect()->route('sub-head-accounts.index')->with('message', 'Sub head account updated successfully.');
    }


    public function destroy($id)
    {
        $sub_head_account = SubControlAccount::findOrFail($id);
        $sub_head_account->delete();

        return redirect()->route('sub-head-accounts.index')->with('success', 'Sub head account deleted successfully.');
    }
}
