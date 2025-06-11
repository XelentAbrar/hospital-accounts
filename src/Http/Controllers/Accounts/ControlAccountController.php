<?php

namespace XelentAbrar\HospitalAccounts\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use XelentAbrar\HospitalAccounts\Http\Requests\Accounts\ControlAccountRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\ControlAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ControlAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:control-accounts.index')->only('index');
        $this->middleware('checkPermission:control-accounts.create')->only('create', 'store');
        $this->middleware('checkPermission:control-accounts.show')->only('show');
        $this->middleware('checkPermission:control-accounts.edit')->only('edit', 'update');
        $this->middleware('checkPermission:control-accounts.destroy')->only('destroy');
    }
    public function index()
    {
        $control_accounts = ControlAccount::orderBy('acc_code', 'asc')->paginate(100);
        return Inertia::render('Accounts/ControlAccounts/Index', [
            'control_accounts' => $control_accounts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Accounts/ControlAccounts/Create');
    }

    public function store(ControlAccountRequest $request)
    {
        DB::beginTransaction();

        $control_account = new ControlAccount();
        $formData = $request->only($control_account->getFillable());
        $formData['created_by'] = Auth::user()->id;
        // $formData['acc_code'] = ControlAccount::max('acc_code') + 1;

        ControlAccount::create($formData);

        DB::commit();

        return redirect()->route('control-accounts.index');
    }


    public function edit(ControlAccount $control_account)
    {
        return Inertia::render('Accounts/ControlAccounts/Create', [
            'control_account' => $control_account,
        ]);
    }


    public function update(ControlAccountRequest $request, ControlAccount $control_account)
    {
        DB::beginTransaction();

        $formData = $request->only($control_account->getFillable());
        $formData['updated_by'] = Auth::user()->id;
        $control_account->update($formData);

        DB::commit();

        return redirect()->route('control-accounts.index')->with('message', 'Control account updated successfully.');
    }


    public function destroy($id)
    {
        $control_account = ControlAccount::findOrFail($id);
        $control_account->delete();

        return redirect()->route('control-accounts.index')->with('success', 'Control account deleted successfully.');
    }
}
