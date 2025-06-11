<?php

namespace XelentAbrar\HospitalAccounts\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use XelentAbrar\HospitalAccounts\Http\Requests\Accounts\ChartOfAccountRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\AccountCode;
use XelentAbrar\HospitalAccounts\Models\Accounts\SubHeadAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAuditDetail;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChartOfAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:chart-of-accounts.index')->only('index');
        $this->middleware('checkPermission:chart-of-accounts.create')->only('create', 'store');
        $this->middleware('checkPermission:chart-of-accounts.show')->only('show');
        $this->middleware('checkPermission:chart-of-accounts.edit')->only('edit', 'update');
        $this->middleware('checkPermission:chart-of-accounts.destroy')->only('destroy');
    }
    // public function index()
    // {
    //     $chart_of_accounts = ChartOfAccount::with('accountCode:id,acc_desc')->orderBy('acc_code', 'asc')->paginate(100);

    //     $all_chart_of_accounts = ChartOfAccount::select('id','acc_code','acc_desc')->orderBy('acc_code', 'asc')->get();
    //     return Inertia::render('Accounts/ChartOfAccounts/Index', [
    //         'chart_of_accounts' => $chart_of_accounts,
    //         'all_chart_of_accounts' => $all_chart_of_accounts,
    //     ]);
    // }
    public function index()
    {
            $from_date = date('Y-m-d', strtotime('2021-01-01'));
            $to_date = date('Y-m-d');

            $reports = \DB::select("SELECT
                coa.acc_code AS acc_code_level1,
                coa.acc_desc AS acc_desc_level1,
                sca.acc_code AS acc_code_level2,
                sca.acc_desc AS acc_desc_level2,
                sha.acc_code AS acc_code_level3,
                sha.acc_desc AS acc_desc_level3,
                ac.acc_code AS acc_code_level4,
                ac.acc_desc AS acc_desc_level4,
                ca.acc_code AS acc_code_level5,
                ca.acc_desc AS acc_desc_level5,
                ca.opening AS ca_opening,
                ca.id AS acc_id,

                -- Opening balances
                SUM(CASE WHEN v.voucher_date < ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.dr ELSE 0 END) AS opening_debit,
                SUM(CASE WHEN v.voucher_date < ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.cr ELSE 0 END) AS opening_credit,

                -- Current period
                SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.dr ELSE 0 END) AS current_debit,
                SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.cr ELSE 0 END) AS current_credit,

                -- Closing balances will be calculated in PHP
                SUM(CASE WHEN v.voucher_date < ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.dr ELSE 0 END) +
                SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.dr ELSE 0 END) AS closing_debit,

                SUM(CASE WHEN v.voucher_date < ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.cr ELSE 0 END) +
                SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.cr ELSE 0 END) AS closing_credit

            FROM control_accounts coa
            LEFT JOIN sub_control_accounts sca ON coa.id = sca.coa1_id
            LEFT JOIN sub_head_accounts sha ON sca.id = sha.coa2_id
            LEFT JOIN account_codes ac ON sha.id = ac.coa3_id
            LEFT JOIN chart_of_accounts ca ON ac.id = ca.coa4_id
            LEFT JOIN voucher_details vd ON ca.id = vd.acc_code
            LEFT JOIN vouchers v ON vd.voucher_id = v.id

            GROUP BY coa.acc_code, coa.acc_desc, sca.acc_code, sca.acc_desc, sha.acc_code, sha.acc_desc, ac.acc_code, ac.acc_desc, ca.acc_code, ca.acc_desc, ca.opening, ca.id
          ", [
                $from_date, $from_date, // Opening balances
                $from_date, $to_date,
                $from_date, $to_date,
                $from_date,             // Closing debit/credit
                $from_date, $to_date,   // Closing debit/credit
                $from_date,             // Closing debit/credit
                $from_date, $to_date    // Closing debit/credit
            ]);
            // -- ORDER BY acc_desc_level1, acc_desc_level2, acc_desc_level3, acc_desc_level4, acc_desc_level5, acc_id", [
            // --     $from_date, $from_date, // Opening balances
            // --     $from_date, $to_date,
            // --     $from_date, $to_date,
            // --     $from_date,             // Closing debit/credit
            // --     $from_date, $to_date,   // Closing debit/credit
            // --     $from_date,             // Closing debit/credit
            // --     $from_date, $to_date    // Closing debit/credit
            // -- ]);

        if(isset($_GET['selected_accounts']) && $_GET['selected_accounts'] != ''){
            $reports = collect($reports)->where('acc_code_level4' ,$_GET['selected_accounts'])->values();
        }


        return Inertia::render('Accounts/ChartOfAccounts/Index', [
            'reports' => $reports,
            'all_chart_of_accounts' => AccountCode::select('id', 'acc_code', 'acc_desc')->orderBy('acc_code', 'asc')->get(),
            'accounts' => AccountCode::select('id', 'acc_code', 'acc_desc')->orderBy('acc_code', 'asc')->get(),
            'selected_accounts' => $_GET['selected_accounts'] ?? null
        ]);

        // $query = ChartOfAccount::with('accountCode:id,acc_desc')->orderBy('acc_code', 'asc');
        // if (isset($_GET['search']) && $_GET['search'] != '') {
        //     $search = $_GET['search'];
        //     $query->where(function ($q) use ($search) {
        //         $q->where('acc_code', 'LIKE', '%' . $search . '%')
        //           ->orWhere('acc_desc', 'LIKE', '%' . $search . '%')
        //           ->orWhereHas('accountCode', function($q) use ($search) {
        //               $q->where('acc_desc', 'LIKE', '%' . $search . '%');
        //           });
        //     });
        // }
        // $chart_of_accounts = $query->paginate(100);
        // $all_chart_of_accounts = ChartOfAccount::select('id', 'acc_code', 'acc_desc')->orderBy('acc_code', 'asc')->get();
        // return Inertia::render('Accounts/ChartOfAccounts/Index', [
        //     'chart_of_accounts' => $chart_of_accounts,
        //     'all_chart_of_accounts' => $all_chart_of_accounts,
        //     'filters' => [
        //         'search' => $_GET['search'] ?? '',
        //     ],
        // ]);
    }

    public function create()
    {
        $account_codes = AccountCode::select('id', 'acc_desc')->get();
        return Inertia::render(
            'Accounts/ChartOfAccounts/Create',
            [
                'account_codes' => $account_codes
            ]
        );
    }

    public function store(ChartOfAccountRequest $request)
    {
        DB::beginTransaction();

        $chart_of_account = new ChartOfAccount();
        $formData = $request->only($chart_of_account->getFillable());
        $formData['created_by'] = Auth::user()->id;

        ChartOfAccount::create($formData);

        DB::commit();

        return redirect()->route('chart-of-accounts.index');
    }


    public function edit(ChartOfAccount $chart_of_account)
    {
        $account_codes = SubHeadAccount::select('id', 'acc_desc')->get();
        return Inertia::render('Accounts/ChartOfAccounts/Create', [
            'chart_of_account' => $chart_of_account,
            'account_codes' => $account_codes,
        ]);
    }


    public function update(ChartOfAccountRequest $request, ChartOfAccount $chart_of_account)
    {
        DB::beginTransaction();

        $formData = $request->only($chart_of_account->getFillable());
        $formData['updated_by'] = Auth::user()->id;
        $chart_of_account->update($formData);

        DB::commit();

        return redirect()->route('chart-of-accounts.index')->with('message', 'Chart of account updated successfully.');
    }


    public function destroy($id, $type, $coa_id)
    {
        $chart_of_account = ChartOfAccount::findOrFail($id);
        if($coa_id){
            VoucherDetail::where('acc_code', $id)->update([
                'acc_code' => $coa_id
            ]);
            VoucherAuditDetail::where('acc_code', $id)->update([
                'acc_code' => $coa_id
            ]);
        }
        if($type == 'delete'){
            $chart_of_account->delete();
        }

        return redirect()->route('chart-of-accounts.index')->with('success', 'Chart of account deleted successfully.');
    }
}
