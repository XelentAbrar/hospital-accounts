<?php

namespace XelentAbrar\HospitalAccounts\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use XelentAbrar\HospitalAccounts\Http\Requests\Accounts\VoucherTypeRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class VoucherTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:voucher-types.index')->only('index');
        $this->middleware('checkPermission:voucher-types.create')->only('create', 'store');
        $this->middleware('checkPermission:voucher-types.show')->only('show');
        $this->middleware('checkPermission:voucher-types.edit')->only('edit', 'update');
        $this->middleware('checkPermission:voucher-types.destroy')->only('destroy');
    }
    public function index()
    {
        $voucher_types = VoucherType::orderBy('created_at', 'desc')->paginate(100);
        return Inertia::render('Accounts/VoucherTypes/Index', [
            'voucher_types' => $voucher_types,
        ]);
    }

    public function create()
    {
        return Inertia::render('Accounts/VoucherTypes/Create');
    }

    public function store(VoucherTypeRequest $request)
    {
        DB::beginTransaction();

        $voucher_type = new VoucherType();
        $formData = $request->only($voucher_type->getFillable());
        $formData['created_by'] = Auth::user()->id;

        VoucherType::create($formData);

        DB::commit();

        return redirect()->route('voucher-types.index');
    }


    public function edit(VoucherType $voucher_type)
    {
        return Inertia::render('Accounts/VoucherTypes/Create', [
            'voucher_type' => $voucher_type,
        ]);
    }


    public function update(VoucherTypeRequest $request, VoucherType $voucher_type)
    {
        DB::beginTransaction();
        
        $formData = $request->only($voucher_type->getFillable());
        $formData['updated_by'] = Auth::user()->id;
        $voucher_type->update($formData);

        DB::commit();

        return redirect()->route('voucher-types.index')->with('message', 'Voucher type updated successfully.');
    }


    public function destroy($id)
    {
        $voucher_type = VoucherType::findOrFail($id);
        $voucher_type->delete();

        return redirect()->route('voucher-types.index')->with('success', 'Voucher type deleted successfully.');
    }
}
