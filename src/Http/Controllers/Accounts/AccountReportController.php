<?php

namespace XelentAbrar\HospitalAccounts\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use XelentAbrar\HospitalAccounts\Models\Accounts\AccountCode;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\Voucher;
use App\Models\UserRole;
use DateTime;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountReportController extends Controller
{
    public function trialBalance()
    {

        // $currentYear = date('Y');
        //     $currentMonth = date('m');

        //     if ($currentMonth < 7) {
        //         $from_date_default = date('Y-m-d', strtotime(($currentYear - 1) . '-07-01'));
        //         $to_date_default = date('Y-m-d', strtotime($currentYear . '-06-30'));
        //     } else {
        //         $from_date_default = date('Y-m-d', strtotime($currentYear . '-07-01'));
        //         $to_date_default = date('Y-m-d', strtotime(($currentYear + 1) . '-06-30'));
        //     }

        // $from_date = request()->input('from_date');
        // $to_date = request()->input('to_date');
        $sort_by_code = request()->input('sortByAccCode') === 'true';

        $from_date = request()->input('from_date') ? date('Y-m-d', strtotime(request()->input('from_date'))) : null;
        $to_date = request()->input('from_date') ? date('Y-m-d', strtotime(request()->input('to_date'))) : null;

        if ($from_date && $to_date) {

            $query = "
                    SELECT
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

                        -- Opening balances
                        SUM(CASE WHEN v.voucher_date < ? AND v.deleted_at IS NULL AND vd.deleted_at IS NULL AND v.is_posted='1' THEN vd.dr ELSE 0 END) AS opening_debit,
                        SUM(CASE WHEN v.voucher_date < ? AND v.deleted_at IS NULL AND vd.deleted_at IS NULL AND v.is_posted='1' THEN vd.cr ELSE 0 END) AS opening_credit,

                        -- Current period
                        SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? AND v.deleted_at IS NULL AND vd.deleted_at IS NULL AND v.is_posted='1' THEN vd.dr ELSE 0 END) AS current_debit,
                        SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? AND v.deleted_at IS NULL AND vd.deleted_at IS NULL AND v.is_posted='1' THEN vd.cr ELSE 0 END) AS current_credit,

                        -- Closing balances
                        SUM(CASE WHEN v.voucher_date < ? AND v.deleted_at IS NULL AND vd.deleted_at IS NULL AND v.is_posted='1' THEN vd.dr ELSE 0 END) +
                        SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? AND v.deleted_at IS NULL AND vd.deleted_at IS NULL AND v.is_posted='1' THEN vd.dr ELSE 0 END) AS closing_debit,

                        SUM(CASE WHEN v.voucher_date < ? AND v.deleted_at IS NULL AND vd.deleted_at IS NULL AND v.is_posted='1' THEN vd.cr ELSE 0 END) +
                        SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? AND v.deleted_at IS NULL AND vd.deleted_at IS NULL AND v.is_posted='1' THEN vd.cr ELSE 0 END) AS closing_credit

                    FROM control_accounts coa
                    LEFT JOIN sub_control_accounts sca ON coa.id = sca.coa1_id
                    LEFT JOIN sub_head_accounts sha ON sca.id = sha.coa2_id
                    LEFT JOIN account_codes ac ON sha.id = ac.coa3_id
                    LEFT JOIN chart_of_accounts ca ON ac.id = ca.coa4_id
                    LEFT JOIN voucher_details vd ON ca.id = vd.acc_code
                    LEFT JOIN vouchers v ON vd.voucher_id = v.id

                    GROUP BY coa.acc_code, coa.acc_desc, sca.acc_code, sca.acc_desc, sha.acc_code, sha.acc_desc, ac.acc_code, ac.acc_desc, ca.acc_code, ca.acc_desc, ca.opening
                ";
            if ($sort_by_code) {
                $query .= " ORDER BY coa.acc_code, sca.acc_code, sha.acc_code, ac.acc_code, ca.acc_code";
            } else {
                $query .= " ORDER BY acc_desc_level1, acc_desc_level2, acc_desc_level3, acc_desc_level4, acc_desc_level5";
            }

            // Execute the query
            $reports = \DB::select($query, [
                $from_date,
                $from_date, // Opening balances
                $from_date,
                $to_date,   // Current period
                $from_date,
                $to_date,   // Current period
                $from_date,             // Closing debit/credit
                $from_date,
                $to_date,   // Closing debit/credit
                $from_date,             // Closing debit/credit
                $from_date,
                $to_date    // Closing debit/credit
            ]);
        }
        // }
        else {
            $reports = [];
        }

        if (!$from_date && !$to_date) {
            $reports = [];
        }
        //     $reports = \DB::select("SELECT
        //         coa.acc_code AS acc_code_level1,
        //         coa.acc_desc AS acc_desc_level1,
        //         sca.acc_code AS acc_code_level2,
        //         sca.acc_desc AS acc_desc_level2,
        //         sha.acc_code AS acc_code_level3,
        //         sha.acc_desc AS acc_desc_level3,
        //         ac.acc_code AS acc_code_level4,
        //         ac.acc_desc AS acc_desc_level4,
        //         ca.acc_code AS acc_code_level5,
        //         ca.acc_desc AS acc_desc_level5,
        //         ca.opening AS ca_opening,

        //         -- Opening balances
        //         SUM(CASE WHEN v.voucher_date < ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.dr ELSE 0 END) AS opening_debit,
        //         SUM(CASE WHEN v.voucher_date < ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.cr ELSE 0 END) AS opening_credit,

        //         -- Current period
        //         SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.dr ELSE 0 END) AS current_debit,
        //         SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.cr ELSE 0 END) AS current_credit,

        //         -- Closing balances will be calculated in PHP
        //         SUM(CASE WHEN v.voucher_date < ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.dr ELSE 0 END) +
        //         SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.dr ELSE 0 END) AS closing_debit,

        //         SUM(CASE WHEN v.voucher_date < ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.cr ELSE 0 END) +
        //         SUM(CASE WHEN v.voucher_date BETWEEN ? AND ? and v.deleted_at IS NULL AND vd.deleted_at IS NULL and v.is_posted='1' THEN vd.cr ELSE 0 END) AS closing_credit

        //     FROM control_accounts coa
        //     LEFT JOIN sub_control_accounts sca ON coa.id = sca.coa1_id
        //     LEFT JOIN sub_head_accounts sha ON sca.id = sha.coa2_id
        //     LEFT JOIN account_codes ac ON sha.id = ac.coa3_id
        //     LEFT JOIN chart_of_accounts ca ON ac.id = ca.coa4_id
        //     LEFT JOIN voucher_details vd ON ca.id = vd.acc_code
        //     LEFT JOIN vouchers v ON vd.voucher_id = v.id

        //     GROUP BY coa.acc_code, coa.acc_desc, sca.acc_code, sca.acc_desc, sha.acc_code, sha.acc_desc, ac.acc_code, ac.acc_desc, ca.acc_code, ca.acc_desc, ca.opening
        //     ORDER BY acc_desc_level1, acc_desc_level2, acc_desc_level3, acc_desc_level4, acc_desc_level5", [
        //         $from_date, $from_date, // Opening balances
        //         $from_date, $to_date,   // Current period
        //         $from_date, $to_date,   // Current period
        //         $from_date,             // Closing debit/credit
        //         $from_date, $to_date,   // Closing debit/credit
        //         $from_date,             // Closing debit/credit
        //         $from_date, $to_date    // Closing debit/credit
        //     ]);

        // if ($sort_by_code) {
        //     usort($reports, function ($a, $b) {
        //         return strcmp($a->acc_code_level4, $b->acc_code_level4);
        //     });
        // }
        // }
        // else{
        //     $reports = [];
        // }

        // if(!$from_date && !$to_date){
        //     $reports = [];
        // }

        if (isset($_GET['selected_accounts']) && $_GET['selected_accounts'] != '') {
            $reports = collect($reports)->where('acc_code_level4', $_GET['selected_accounts'])->values();
        }


        //return $reports;
        return Inertia::render('Accounts/Reports/TrialBalance', [
            'reports' => $reports,
            'from_date' => $from_date ? date('d-m-Y', strtotime($from_date)) : date('d-m-Y', strtotime('1 July ' . date('Y') . ' -1 year')),
            'to_date' => $to_date ? date('d-m-Y', strtotime($to_date)) : null,
            'sort_by_code' => $sort_by_code ? 'true' : 'false',
            'accounts' => AccountCode::select('id', 'acc_code', 'acc_desc')->get(),
            'selected_accounts' => $_GET['selected_accounts'] ?? null
        ]);
    }
    public function trialBalanceDC()
    {

        // $currentYear = date('Y');
        //     $currentMonth = date('m');

        //     if ($currentMonth < 7) {
        //         $from_date_default = date('Y-m-d', strtotime(($currentYear - 1) . '-07-01'));
        //         $to_date_default = date('Y-m-d', strtotime($currentYear . '-06-30'));
        //     } else {
        //         $from_date_default = date('Y-m-d', strtotime($currentYear . '-07-01'));
        //         $to_date_default = date('Y-m-d', strtotime(($currentYear + 1) . '-06-30'));
        //     }

        $from_date = request()->input('from_date');
        $to_date = request()->input('to_date');

        if ($from_date && $to_date) {
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

            GROUP BY coa.acc_code, coa.acc_desc, sca.acc_code, sca.acc_desc, sha.acc_code, sha.acc_desc, ac.acc_code, ac.acc_desc, ca.acc_code, ca.acc_desc, ca.opening
            ORDER BY acc_desc_level1, acc_desc_level2, acc_desc_level3, acc_desc_level4, acc_desc_level5", [
                $from_date,
                $from_date, // Opening balances
                $from_date,
                $to_date,   // Current period
                $from_date,
                $to_date,   // Current period
                $from_date,             // Closing debit/credit
                $from_date,
                $to_date,   // Closing debit/credit
                $from_date,             // Closing debit/credit
                $from_date,
                $to_date    // Closing debit/credit
            ]);
        } else {
            $reports = [];
        }

        if (!$from_date && !$to_date) {
            $reports = [];
        }

        if (isset($_GET['selected_accounts']) && $_GET['selected_accounts'] != '') {
            $reports = collect($reports)->where('acc_code_level4', $_GET['selected_accounts'])->values();
        }


        //return $reports;
        return Inertia::render('Accounts/Reports/TrialBalanceDC', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'accounts' => AccountCode::select('id', 'acc_code', 'acc_desc')->get(),
            'selected_accounts' => $_GET['selected_accounts'] ?? null
        ]);
    }

    // $query = VoucherDetail::query();
    // $query =  $query->addSelect(['coa' => ChartOfAccount::whereColumn('voucher_details.acc_code', 'chart_of_accounts.id')->select('chart_of_accounts.acc_code')->limit(1)]);
    // $query = $query->latest()->limit(10)->get();
    // return $query;

    // $voucherDetails = VoucherDetail::select('voucher_details.id', 'voucher_details.dr', 'voucher_details.cr')
    //     ->addSelect(DB::raw('(SELECT acc_code FROM chart_of_accounts WHERE chart_of_accounts.id = voucher_details.acc_code) as chart_of_account_acc_code'))
    //     ->addSelect(DB::raw('(SELECT acc_code FROM account_codes WHERE account_codes.id = (SELECT coa4_id FROM chart_of_accounts WHERE chart_of_accounts.id = voucher_details.acc_code)) as account_code_acc_code'))
    //     ->addSelect(DB::raw('(SELECT acc_code FROM sub_head_accounts WHERE sub_head_accounts.id = (SELECT coa3_id FROM account_codes WHERE account_codes.id = (SELECT coa4_id FROM chart_of_accounts WHERE chart_of_accounts.id = voucher_details.acc_code))) as sub_head_account_acc_code'))
    //     // ->addSelect(DB::raw('(SELECT acc_code FROM sub_head_accounts WHERE sub_head_accounts.id = (SELECT coa3_id FROM account_codes WHERE account_codes.id = (SELECT coa4_id FROM chart_of_accounts WHERE chart_of_accounts.id = voucher_details.acc_code))) as sub_head_account_acc_code'));
    //     ->addSelect(DB::raw('(SELECT acc_code FROM sub_control_accounts WHERE sub_control_accounts.id = (SELECT coa2_id FROM sub_head_accounts WHERE sub_head_accounts.id = (SELECT coa3_id FROM account_codes WHERE account_codes.id = (SELECT coa4_id FROM chart_of_accounts WHERE chart_of_accounts.id = voucher_details.acc_code)))) as sub_control_account_acc_code'))
    //     ->addSelect(DB::raw('(SELECT acc_code FROM control_accounts WHERE control_accounts.id = (SELECT coa1_id FROM sub_control_accounts WHERE sub_control_accounts.id = (SELECT coa2_id FROM sub_head_accounts WHERE sub_head_accounts.id = (SELECT coa3_id FROM account_codes WHERE account_codes.id = (SELECT coa4_id FROM chart_of_accounts WHERE chart_of_accounts.id = voucher_details.acc_code))))) as control_account_acc_code'))->whereHas('chartOfAccount', function ($q) {
    //         $q->where('acc_code', 'LIKE', '10%');
    //     });



    // // Fetch all results
    // $voucherDetails = $voucherDetails->limit(100)->latest()->get();
    // $data = [];
    // $data['coa1Accounts'] = $voucherDetails->where('control_account_acc_code', '10')->values();


    // $reports = AccountCode::whereHas('chartOfAccount', function($q){
    //     $q->where('acc_code', 'LIKE', '10%');
    // })->with('chartOfAccount:id,coa4_id,acc_code,acc_desc')
    // ->with('chartOfAccount.voucherDetail')->get();

    // $data = [];
    // SubControlAccount::where('acc_code', 'Like', '10%')->with('subHeadAccount.accountCode.chartOfAccount.voucherDetail:acc_code,id,dr')->get();
    // foreach($subcontrol_accounts as $index => $subcontrol_account){
    //     $data['subcontrol_account'][] = $subcontrol_account;
    //     $SubHeadAccount = SubHeadAccount::where('acc_code', 'Like', $subcontrol_account->acc_code.'%')->get();
    //     $data['subcontrol_account'][$index]['subhead_account'] = [];
    //     foreach($SubHeadAccount as $sub_account_index => $subhead_account){
    //         $temp = $data['subcontrol_account'][$index]['subhead_account'];
    //         $temp[] = $subhead_account;
    //         $data['subcontrol_account'][$index]['subhead_account'] = $temp;

    //         $AccountCode = AccountCode::where('acc_code', 'Like', $subhead_account->acc_code.'%')->get();

    //         $data['subcontrol_account'][$index]['subhead_account'][$sub_account_index]['account_code'] = [];

    //         foreach($AccountCode as $accode_code_index => $account_code){
    //             $temp = $data['subcontrol_account'][$index]['subhead_account'][$sub_account_index]['account_code'];
    //             $temp[] = $account_code;
    //             $data['subcontrol_account'][$index]['subhead_account'][$sub_account_index]['account_code'] = $temp;

    //             $ChartOfAccount = ChartOfAccount::where('acc_code', 'LIKE', $account_code->acc_code.'%')->get();
    //             $data['subhead_accounts']['subhead_account']['chatof_account'][] = $ChartOfAccount;

    //             $data['subcontrol_account'][$index]['subhead_account'][$sub_account_index]['account_code'][$accode_code_index]['chartof_account'] = [];
    //             foreach($ChartOfAccount as $chatof_account){

    //                 $temp = $data['subcontrol_account'][$index]['subhead_account'][$sub_account_index]['account_code'][$accode_code_index]['chartof_account'];
    //                 $temp['cr'] = VoucherDetail::where('acc_code', $chatof_account->id)->sum('cr');
    //                 $temp['dr'] = VoucherDetail::where('acc_code', $chatof_account->id)->sum('dr');
    //                 $data['subcontrol_account'][$index]['subhead_account'][$sub_account_index]['account_code'][$accode_code_index]['chartof_account'] = $temp;
    //             }
    //         }
    //     }
    // }
    // return $data;

    // $reports =  ControlAccount::whereIn('acc_code',['10','11']);
    // $reports = $reports->whereHas('subControlAccount',  function ($q) use ($from_date, $to_date) {
    //     $q->whereHas('subHeadAccount',  function ($q1) use ($from_date, $to_date) {
    //         $q1->whereHas('accountCode',  function ($q2) use ($from_date, $to_date) {
    //             $q2->whereHas('chartOfAccount',  function ($q4) use ($from_date, $to_date) {
    //                 $q4->whereHas('voucherDetail',  function ($q5) use ($from_date, $to_date) {
    //                     $q5->whereHas('voucher',  function ($q6) use ($from_date, $to_date) {
    //                         $q6->whereBetween('voucher_date', [$from_date, $to_date]);
    //                     });
    //                 });
    //             });
    //         });
    //     });
    // });
    // $reports = $reports->with(['subControlAccount.subHeadAccount.accountCode.chartOfAccount'])->get();
    // foreach($reports as $ControlAccount){
    //     foreach($ControlAccount->subControlAccount as $subControlAccount){
    //         foreach($subControlAccount->subHeadAccount as $subHeadAccount){
    //             foreach($subHeadAccount->accountCode as $accountCode){
    //                 foreach($accountCode->chartOfAccount as $chartOfAccount){
    //                     $chartOfAccount->cr = VoucherDetail::where('acc_code',$chartOfAccount->id)->sum('cr');
    //                     $chartOfAccount->dr = VoucherDetail::where('acc_code',$chartOfAccount->id)->sum('dr');
    //                 }
    //             }
    //         }
    //     }
    // }

    public function balanceSheet()
    {
        // $currentYear = date('Y');
        // $currentMonth = date('m');

        // if ($currentMonth < 7) {
        //     $from_date_default = date('Y-m-d', strtotime(($currentYear - 1) . '-07-01'));
        //     $to_date_default = date('Y-m-d', strtotime($currentYear . '-06-30'));
        // } else {
        //     $from_date_default = date('Y-m-d', strtotime($currentYear . '-07-01'));
        //     $to_date_default = date('Y-m-d', strtotime(($currentYear + 1) . '-06-30'));
        // }

        $from_date = request()->input('from_date');
        $to_date = request()->input('to_date');

        if ($from_date && $to_date) {
            $from_date_sql = \Carbon\Carbon::createFromFormat('d-m-Y', $from_date)->format('Y-m-d');
            $to_date_sql = \Carbon\Carbon::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
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
            WHERE (coa.acc_code LIKE '10%' OR coa.acc_code LIKE '11%')
            GROUP BY coa.acc_code, coa.acc_desc, sca.acc_code, sca.acc_desc, sha.acc_code, sha.acc_desc, ac.acc_code, ac.acc_desc, ca.acc_code, ca.acc_desc, ca.opening
            ORDER BY acc_desc_level1, acc_desc_level2, acc_desc_level3, acc_desc_level4, acc_desc_level5", [
                $from_date_sql,
                $from_date_sql, // Opening balances
                $from_date_sql,
                $to_date_sql,   // Current period
                $from_date_sql,
                $to_date_sql,   // Current period
                $from_date_sql,             // Closing debit/credit
                $from_date_sql,
                $to_date_sql,   // Closing debit/credit
                $from_date_sql,             // Closing debit/credit
                $from_date_sql,
                $to_date_sql    // Closing debit/credit
            ]);
        } else {
            $reports = [];
        }

        if (!$from_date && !$to_date) {
            $reports = [];
        }

        if (isset($_GET['selected_accounts']) && $_GET['selected_accounts'] != '') {
            $reports = collect($reports)->where('acc_code_level4', $_GET['selected_accounts'])->values();
        }

        // return $reports;

        return Inertia::render('Accounts/Reports/BalanceSheet', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'accounts' => AccountCode::select('id', 'acc_code', 'acc_desc')->get(),
            'selected_accounts' => $_GET['selected_accounts'] ?? null
        ]);
    }

    public function balanceSheetNotes()
    {
        // $currentYear = date('Y');
        // $currentMonth = date('m');

        // if ($currentMonth < 7) {
        //     $from_date_default = date('Y-m-d', strtotime(($currentYear - 1) . '-07-01'));
        //     $to_date_default = date('Y-m-d', strtotime($currentYear . '-06-30'));
        // } else {
        //     $from_date_default = date('Y-m-d', strtotime($currentYear . '-07-01'));
        //     $to_date_default = date('Y-m-d', strtotime(($currentYear + 1) . '-06-30'));
        // }

        $from_date = request()->input('from_date');
        $to_date = request()->input('to_date');

        if ($from_date && $to_date) {
            $from_date_sql = \Carbon\Carbon::createFromFormat('d-m-Y', $from_date)->format('Y-m-d');
            // dd( $from_date_sql);
            $to_date_sql = \Carbon\Carbon::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
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
            Where (coa.acc_code LIKE '10%' OR coa.acc_code LIKE '11%')
            GROUP BY coa.acc_code, coa.acc_desc, sca.acc_code, sca.acc_desc, sha.acc_code, sha.acc_desc, ac.acc_code, ac.acc_desc, ca.acc_code, ca.acc_desc, ca.opening
            ORDER BY acc_desc_level1, acc_desc_level2, acc_desc_level3, acc_desc_level4, acc_desc_level5", [
                $from_date_sql,
                $from_date_sql, // Opening balances
                $from_date_sql,
                $to_date_sql,   // Current period
                $from_date_sql,
                $to_date_sql,   // Current period
                $from_date_sql,             // Closing debit/credit
                $from_date_sql,
                $to_date_sql,   // Closing debit/credit
                $from_date_sql,             // Closing debit/credit
                $from_date_sql,
                $to_date_sql    // Closing debit/credit
            ]);
        } else {
            $reports = [];
        }

        if (!$from_date && !$to_date) {
            $reports = [];
        }

        if (isset($_GET['selected_accounts']) && $_GET['selected_accounts'] != '') {
            $reports = collect($reports)->where('acc_code_level4', $_GET['selected_accounts'])->values();
        }

        return Inertia::render('Accounts/Reports/BalanceSheetNotes', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'accounts' => AccountCode::select('id', 'acc_code', 'acc_desc')->get(),
            'selected_accounts' => $_GET['selected_accounts'] ?? null
        ]);
    }


    public function incomeStatement()
    {
        $from_date = request()->input('from_date');
        $to_date = request()->input('to_date');

        if ($from_date && $to_date) {
            $from_date_sql = \Carbon\Carbon::createFromFormat('d-m-Y', $from_date)->format('Y-m-d');
            $to_date_sql = \Carbon\Carbon::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');

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
            WHERE (coa.acc_code LIKE '12%' OR coa.acc_code LIKE '13%' OR coa.acc_code LIKE '14%')
            GROUP BY coa.acc_code, coa.acc_desc, sca.acc_code, sca.acc_desc, sha.acc_code, sha.acc_desc, ac.acc_code, ac.acc_desc, ca.acc_code, ca.acc_desc, ca.opening
            ORDER BY acc_desc_level1, acc_desc_level2, acc_desc_level3, acc_desc_level4, acc_desc_level5", [
                $from_date_sql,
                $from_date_sql, // Opening balances
                $from_date_sql,
                $to_date_sql,   // Current period
                $from_date_sql,
                $to_date_sql,   // Current period
                $from_date_sql,             // Closing debit/credit
                $from_date_sql,
                $to_date_sql,   // Closing debit/credit
                $from_date_sql,             // Closing debit/credit
                $from_date_sql,
                $to_date_sql    // Closing debit/credit
            ]);
        } else {
            $reports = [];
        }

        if (!$from_date && !$to_date) {
            $reports = [];
        }

        if (isset($_GET['selected_accounts']) && $_GET['selected_accounts'] != '') {
            $reports = collect($reports)->where('acc_code_level4', $_GET['selected_accounts'])->values();
        }

        return Inertia::render('Accounts/Reports/IncomeStatement', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }

    public function incomeStatementNotes()
    {
        // $currentYear = date('Y');
        // $currentMonth = date('m');

        // if ($currentMonth < 7) {
        //     $from_date_default = date('Y-m-d', strtotime(($currentYear - 1) . '-07-01'));
        //     $to_date_default = date('Y-m-d', strtotime($currentYear . '-06-30'));
        // } else {
        //     $from_date_default = date('Y-m-d', strtotime($currentYear . '-07-01'));
        //     $to_date_default = date('Y-m-d', strtotime(($currentYear + 1) . '-06-30'));
        // }

        $from_date = request()->input('from_date');
        $to_date = request()->input('to_date');

        if ($from_date && $to_date) {
            $from_date_sql = \Carbon\Carbon::createFromFormat('d-m-Y', $from_date)->format('Y-m-d');
            $to_date_sql = \Carbon\Carbon::createFromFormat('d-m-Y', $to_date)->format('Y-m-d');
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
            WHERE (coa.acc_code LIKE '12%' OR coa.acc_code LIKE '13%' OR coa.acc_code LIKE '14%')
            GROUP BY coa.acc_code, coa.acc_desc, sca.acc_code, sca.acc_desc, sha.acc_code, sha.acc_desc, ac.acc_code, ac.acc_desc, ca.acc_code, ca.acc_desc, ca.opening
            ORDER BY acc_desc_level1, acc_desc_level2, acc_desc_level3, acc_desc_level4, acc_desc_level5", [
                $from_date_sql,
                $from_date_sql, // Opening balances
                $from_date_sql,
                $to_date_sql,   // Current period
                $from_date_sql,
                $to_date_sql,   // Current period
                $from_date_sql,             // Closing debit/credit
                $from_date_sql,
                $to_date_sql,   // Closing debit/credit
                $from_date_sql,             // Closing debit/credit
                $from_date_sql,
                $to_date_sql    // Closing debit/credit
            ]);
        } else {
            $reports = [];
        }

        if (!$from_date && !$to_date) {
            $reports = [];
        }
        return Inertia::render('Accounts/Reports/IncomeStatementNotes', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }

    public function getAccounts()
    {
        $accounts = \DB::table('chart_of_accounts')->select('id', 'acc_code', 'acc_desc')->get();
        return response()->json($accounts);
    }
    public function partyLedger(Request $request)
    {
        $from_date_check = request()->input('from_date');
        $to_date_check = request()->input('to_date');

        // $currentYear = date('Y');
        // $currentMonth = date('m');

        // if(!$from_date){
        //     if ($currentMonth < 7) {
        //         $from_date = date('Y-m-d', strtotime(($currentYear - 1) . '-07-01'));
        //         $to_date = date('Y-m-d', strtotime($currentYear . '-06-30'));
        //     } else {
        //         $from_date = date('Y-m-d', strtotime($currentYear . '-07-01'));
        //         $to_date = date('Y-m-d', strtotime(($currentYear + 1) . '-06-30'));
        //     }
        // }

        $account_id = $request->input('account_id');

        $from_date = date('Y-m-d', strtotime($from_date_check));
        $to_date = date('Y-m-d', strtotime($to_date_check));
        //  return $to_date;

        // $opening_balance = DB::table('voucher_details')
        // ->join('vouchers', 'voucher_details.voucher_id', '=', 'vouchers.id')
        // ->where('voucher_details.acc_code', $account_id)
        // ->where('vouchers.voucher_date', '<', $from_date)
        // ->select(DB::raw('SUM(voucher_details.dr) - SUM(voucher_details.cr) as opening_balance'))
        // ->value('opening_balance');

        $opening_balance = DB::table('voucher_details')
            ->join('vouchers', 'voucher_details.voucher_id', '=', 'vouchers.id')
            ->where('voucher_details.acc_code', $account_id)
            ->where('vouchers.voucher_date', '<', $from_date)
            ->whereNull('vouchers.deleted_at')
            ->where('vouchers.is_posted', '1')
            ->whereNull('voucher_details.deleted_at')
            ->sum(DB::raw('voucher_details.dr - voucher_details.cr'));

        $opening_balance_coa = ChartOfAccount::whereId($account_id)->value('opening');
        $opening_balance = +$opening_balance + +$opening_balance_coa;

        // $reports = DB::table('voucher_details')
        // ->join('vouchers', 'voucher_details.voucher_id', '=', 'vouchers.id')
        // ->where('voucher_details.acc_code', $account_id)
        // ->where('vouchers.is_posted','1')
        // ->whereNull('vouchers.deleted_at')
        // ->whereNull('voucher_details.deleted_at')
        // ->whereBetween('vouchers.voucher_date', [$from_date, $to_date])
        // ->select('vouchers.voucher_date as date', 'vouchers.voucher_no','voucher_details.transaction_no','voucher_details.transaction_type', 'voucher_details.remarks as description', 'vouchers.narration as narration', 'voucher_details.dr as debit', 'voucher_details.cr as credit','vouchers.id as id')
        // ->orderBy('vouchers.voucher_date')
        // ->get();
        $reports = DB::table('voucher_details')
            ->join('vouchers', 'voucher_details.voucher_id', '=', 'vouchers.id')
            ->join('voucher_types', 'vouchers.voucher_type_id', '=', 'voucher_types.id')
            ->where('voucher_details.acc_code', $account_id)
            ->where('vouchers.is_posted', '1')
            ->whereNull('vouchers.deleted_at')
            ->whereNull('voucher_details.deleted_at')
            ->whereBetween('vouchers.voucher_date', [$from_date, $to_date])
            ->select(
                'vouchers.voucher_date as date',
                'vouchers.voucher_no',
                'voucher_details.transaction_no',
                'voucher_details.transaction_type',
                'voucher_details.remarks as description',
                'vouchers.narration as narration',
                'voucher_details.dr as debit',
                'voucher_details.cr as credit',
                'vouchers.id as id',
                'voucher_types.voucher_abrv as voucher_type'
            )
            ->orderBy('vouchers.voucher_date')
            ->get();

        // return $reports;

        if (!$from_date && !$to_date) {
            $reports = [];
        }
        if (!$from_date_check) {
            $from_date = null;
            $to_date = null;
        }

        if ($account_id == 59 && env('VITE_PROJECT_TYPE') == 'hms' || $account_id == 18 && env('VITE_PROJECT_TYPE') == 'jinnah') {
            $merge = 0;
            $parms = [
                "from_date" => null,
                "to_date" => null,
                "from_voucher_no" => null,
                "to_voucher_no" => null,
                "debit" => 0,
                "credit" => 0
            ];

            // Calculate $parms based on the date range
            foreach ($reports as $value) {
                if (date('Y-m-d', strtotime($value->date)) > date('Y-m-d', strtotime('2024-10-02')) && date('Y-m-d', strtotime($value->date)) < date('Y-m-d', strtotime('2024-12-02'))) {
                    $merge = 1;
                    $parms['from_date'] = $parms['from_date'] == null ? $value->date : $parms['from_date'];
                    $parms['to_date'] = $value->date;
                    $parms['from_voucher_no'] = $parms['from_voucher_no'] == null ? $value->voucher_no : $parms['from_voucher_no'];
                    $parms['to_voucher_no'] = $value->voucher_no;
                    $parms['debit'] += $value->debit;
                    $parms['credit'] += $value->credit;
                }
            }

            // Finalize the $parms object
            if ($merge == 1) {
                $parms = [
                    "date" => date('d-m-Y', strtotime($parms['from_date'])) . ' to ' . date('d-m-Y', strtotime($parms['to_date'])),
                    "voucher_no" => $parms['from_voucher_no'] . ' to ' . $parms['to_voucher_no'],
                    "transaction_no" => null,
                    "transaction_type" => null,
                    "description" => date('d-m-Y', strtotime($parms['from_date'])) . ' to ' . date('d-m-Y', strtotime($parms['to_date'])),
                    "narration" => null,
                    "debit" => $parms['debit'],
                    "credit" => $parms['credit'],
                    'id' => 00,
                ];

                // Filter out data not between the specified dates
                $filteredData = $reports->filter(function ($item) {
                    return date('Y-m-d', strtotime($item->date)) < date('Y-m-d', strtotime('2024-10-03')) || date('Y-m-d', strtotime($item->date)) > date('Y-m-d', strtotime('2024-12-01'));
                });

                // Convert to a collection
                $collection = collect($filteredData)->values();

                // Find the position where "2024-12-01" occurs
                $insertAfterIndex = $collection->search(function ($item) {
                    return date('Y-m-d', strtotime($item->date)) == date('Y-m-d', strtotime('2024-12-02'));
                });
                // dd($insertAfterIndex);

                // Insert $parms into the correct position
                if ($insertAfterIndex != false) {
                    // Get the slices of the collection
                    $before = $collection->slice(0, $insertAfterIndex + 1); // Include the element at the index
                    $after = $collection->slice($insertAfterIndex + 1); // All elements after the index

                    // Merge the slices with the new object in between
                    $updatedCollection = $before->merge([$parms])->merge($after);
                } else {
                    // If "2024-12-01" not found, just append the new object
                    $updatedCollection = $collection->merge([$parms]);
                }

                // Convert back to an array
                $reports = $updatedCollection->values()->toArray();
            }
        }
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 7)->first();
        return Inertia::render('Accounts/Reports/PartyLedger', [
            'reports' => $reports,
            'from_date' => $from_date_check ? date('d-m-Y', strtotime($from_date)) : null,
            'to_date' => $to_date_check ? date('d-m-Y', strtotime($to_date)) : null,
            'account_id' => $account_id,
            'role' => $role,
            'opening_balance' => $opening_balance,
            'accounts' => DB::table('chart_of_accounts')->select('id', 'acc_code', 'acc_desc')->get()
        ]);
    }



    public function generalLedger(Request $request)
    {
        $from_date_check = request()->input('from_date');
        $to_date_check = request()->input('to_date');

        // $currentYear = date('Y');
        // $currentMonth = date('m');

        // if(!$from_date){
        //     if ($currentMonth < 7) {
        //         $from_date = date('Y-m-d', strtotime(($currentYear - 1) . '-07-01'));
        //         $to_date = date('Y-m-d', strtotime($currentYear . '-06-30'));
        //     } else {
        //         $from_date = date('Y-m-d', strtotime($currentYear . '-07-01'));
        //         $to_date = date('Y-m-d', strtotime(($currentYear + 1) . '-06-30'));
        //     }
        // }


        $from_date = date('Y-m-d', strtotime($from_date_check));
        $to_date = date('Y-m-d', strtotime($to_date_check));
        //  return $to_date;

        // $opening_balance = DB::table('voucher_details')
        // ->join('vouchers', 'voucher_details.voucher_id', '=', 'vouchers.id')
        // ->where('voucher_details.acc_code', $account_id)
        // ->where('vouchers.voucher_date', '<', $from_date)
        // ->select(DB::raw('SUM(voucher_details.dr) - SUM(voucher_details.cr) as opening_balance'))
        // ->value('opening_balance');

        $opening_balance = DB::table('voucher_details')
            ->join('vouchers', 'voucher_details.voucher_id', '=', 'vouchers.id')
            ->where('vouchers.voucher_date', '<', $from_date)
            ->where('vouchers.is_posted', '1')
            ->whereNull('vouchers.deleted_at')
            ->whereNull('voucher_details.deleted_at')
            ->sum(DB::raw('voucher_details.dr - voucher_details.cr'));


        $reports = DB::table('voucher_details')
            ->join('vouchers', 'voucher_details.voucher_id', '=', 'vouchers.id')
            ->whereNull('vouchers.deleted_at')
            ->where('vouchers.is_posted', '1')
            ->whereNull('voucher_details.deleted_at')
            ->whereBetween('vouchers.voucher_date', [$from_date, $to_date])
            ->select('vouchers.voucher_date as date', 'vouchers.voucher_no', 'voucher_details.remarks as description', 'vouchers.narration as narration', 'voucher_details.dr as debit', 'voucher_details.cr as credit')
            ->orderBy('vouchers.voucher_date')
            ->get();

        // return $reports;

        if (!$from_date && !$to_date) {
            $reports = [];
        }
        if (!$from_date_check) {
            $from_date = null;
            $to_date = null;
        }
        return Inertia::render('Accounts/Reports/GeneralLedger', [
            'reports' => $reports,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }
    public function getCashBooks(Request $request)
    {
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');
        if ($request->has('from_date') && $request->from_date != '') {
            $fromDate = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$fromDate) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $fromDate = $fromDate->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $toDate = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$toDate) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $toDate = $toDate->format('Y-m-d');
        }
        $searchTerm = $request->input('search');
        $cashInHandAccount = DB::table('acc_settings')
        ->where('key', 'Cash_in_hand')
        ->first();

        $cashInHandAccCode = DB::table('acc_settings')
        ->where('key', 'Cash_In_Hand')
        ->value('value');
    $cashInHandChartAccountId = null;
    if ($cashInHandAccCode) {
        $cashInHandChartAccountId = DB::table('chart_of_accounts')
            ->where('acc_code', $cashInHandAccCode)
            ->value('id');
    }
    $vouchersWithDetails = Voucher::with(['voucherDetails' => function ($query) use ($cashInHandChartAccountId) {
        if ($cashInHandChartAccountId) {
            $query->where('acc_code', '!=',$cashInHandChartAccountId);
        }
    }, 'voucherType', 'voucherDetails.chartOfAccount:id,acc_code,acc_desc'])
        ->whereHas('voucherType', function ($query) {
            $query->whereColumn('voucher_types.id', 'vouchers.voucher_type_id')
                ->whereIn('voucher_types.voucher_abrv', ['CR', 'CP']);
        })
        ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
            return $query->whereBetween('vouchers.voucher_date', [$fromDate, $toDate]);
        })
        ->when($searchTerm, function ($query) use ($searchTerm) {
            return $query->where(function ($query) use ($searchTerm) {
                $query->where('vouchers.id', 'LIKE', "%$searchTerm%")
                    ->orWhere('vouchers.voucher_no', 'LIKE', "%$searchTerm%")
                    ->orWhereHas('voucherDetails', function ($query) use ($searchTerm) {
                        $query->whereHas('chartOfAccount', function ($query) use ($searchTerm) {
                            $query->where('acc_desc', 'LIKE', "%$searchTerm%");
                        })
                        ->orWhere('transaction_no', 'LIKE', "%$searchTerm%")
                        ->orWhere('transaction_type', 'LIKE', "%$searchTerm%");
                    });
            });
        })
      ->orderBy('voucher_date')
        ->get()
        ;
        return inertia('Accounts/Reports/CashBook', [
            'reports' => $vouchersWithDetails,
            'search' => $searchTerm,
            'from_date' => $fromDate ? date('d-m-Y', strtotime($fromDate)) : null,
            'to_date' => $toDate ? date('d-m-Y', strtotime($toDate)) : null,
        ]);
    }


    public function getJournalBooks(Request $request)
    {
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');
        if ($request->has('from_date') && $request->from_date != '') {
            $fromDate = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$fromDate) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $fromDate = $fromDate->format('Y-m-d');
        }
        if ($request->has('to_date') && $request->to_date != '') {
            $toDate = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$toDate) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $toDate = $toDate->format('Y-m-d');
        }

        $searchTerm = $request->input('search');

        $jvVouchers = Voucher::with(['voucherDetails', 'voucherType', 'voucherDetails.chartOfAccount:id,acc_code,acc_desc'])
        ->whereHas('voucherType', function ($query) {
            $query->whereColumn('voucher_types.id', 'vouchers.voucher_type_id')
                ->whereIn('voucher_types.voucher_abrv', ['JR']);
        })
        ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
            return $query->whereBetween('vouchers.voucher_date', [$fromDate, $toDate]);
        })
    ->when($searchTerm, function ($query) use ($searchTerm) {
        return $query->where(function ($query) use ($searchTerm) {
            $query->where('vouchers.id', 'LIKE', "%$searchTerm%")
                ->orWhere('vouchers.voucher_no', 'LIKE', "%$searchTerm%")
                ->orWhereHas('voucherDetails', function ($query) use ($searchTerm) {
                    $query->whereHas('chartOfAccount', function ($query) use ($searchTerm) {
                        $query->where('acc_desc', 'LIKE', "%$searchTerm%");
                    })
                    ->orWhere('transaction_no', 'LIKE', "%$searchTerm%")
                    ->orWhere('transaction_type', 'LIKE', "%$searchTerm%");
                });
        });
    })->orderBy('voucher_date')
    ->get();

        return inertia('Accounts/Reports/JournalBook', [
            'reports' => $jvVouchers,
            'search' => $searchTerm,
            'from_date' => $fromDate ? date('d-m-Y', strtotime($fromDate)) : null,
            'to_date' => $toDate ? date('d-m-Y', strtotime($toDate)) : null,
        ]);
    }
}
