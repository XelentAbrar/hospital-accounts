<?php

namespace XelentAbrar\HospitalAccounts\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use XelentAbrar\HospitalAccounts\Http\Requests\Accounts\VoucherAuditRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherType;
use XelentAbrar\HospitalAccounts\Models\Accounts\Voucher;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAudit;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAuditDetail;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherDetail;
use App\Models\IPD\Admission;
use App\Models\IPD\AdmissionDetail;
use App\Models\OPD\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use stdClass;

class VoucherAuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:vouchers-audit.index')->only('index');
        $this->middleware('checkPermission:vouchers-audit.create')->only('create', 'store');
        $this->middleware('checkPermission:vouchers-audit.show')->only('show');
        $this->middleware('checkPermission:vouchers-audit.edit')->only('edit', 'update');
        $this->middleware('checkPermission:vouchers-audit.destroy')->only('destroy');
    }

    public function index()
{
    $search = request('search', '');

    $vouchers = VoucherAudit::with(['voucherAuditDetails.chartOfAccount:id,acc_desc,acc_code','voucherAuditDetails.ipd:id,careoff_id' ,'voucherAuditDetails.opd:id,careoff_id' ,'voucherAuditDetails.lab:id,careoff_id' ,'voucherType:id,voucher_name'])
        ->when(isset(Auth::user()->userRole[0]->role_id) && Auth::user()->userRole[0]->role_id == '1', function ($query) {
            $query->where('is_posted', '1');
        }, function ($query) {
            $query->where('is_posted', '0');
        })
        ->when($search, function ($query, $search) {
            $query->whereHas('voucherAuditDetails.chartOfAccount', function ($q) use ($search) {
                $q->where('acc_desc', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('voucherType', function ($q) use ($search) {
                $q->where('voucher_name', 'LIKE', "%{$search}%");
            })
            ->orWhere('id', '=', $search)
            ->orWhere('narration', 'LIKE', "%{$search}%")
            ->orWhere('voucher_date', 'LIKE', "%{$search}%");
        })
        ->orderBy('is_posted', 'asc')
        ->get();

    $account_vouchers = [];
    // if (isset(Auth::user()->userRole[0]->role_id) && Auth::user()->userRole[0]->role_id == '1') {
        $account_vouchers = Voucher::with(['voucherDetails.chartOfAccount:id,acc_desc,acc_code', 'voucherType:id,voucher_name'])
            ->where('is_posted', '0')
            ->orderBy('is_posted', 'asc')
            ->get();
    // }
    $date_vouchers = [];
    $current_date_vouchers = [];
    $ipd_users = 0;
    $date_vouchers = VoucherAudit::where('is_posted', '0')
        ->selectRaw('DATE(voucher_date) as voucher_date')
        ->groupBy(DB::raw('DATE(voucher_date)')) // Group by the date
        ->orderBy('voucher_date', 'asc') // Sort results
        ->get();
        foreach ($date_vouchers as $key => $data) {
            $ids = VoucherAudit::whereRaw("DATE(voucher_date) = ?", [$data->voucher_date])->where('is_posted', '0')->pluck('id');
            $dr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->sum('dr');
            $cr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->sum('cr');
            if(env('VITE_PROJECT_TYPE') == 'hms')
            {
                $ch_cr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->where('acc_code','59')->sum('cr');
                $ch_dr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->where('acc_code','59')->sum('dr');
            }
            else{
                $ch_cr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->where('acc_code','189')->sum('cr');
                $ch_dr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->where('acc_code','189')->sum('dr');
            }
            $data['cr'] = $cr;
            $data['dr'] = $dr;
            $data['cash_in_hand'] = $ch_dr - $ch_cr;
            $data['date'] = date('Y-m-d', strtotime($data->voucher_date));
        }
        $ipd_users = Admission::where('status', 'Pending')->where('cancel','0')->count();

        $current_date_vouchers = VoucherAudit::where('is_posted', '1')
        ->selectRaw('DATE(voucher_date) as voucher_date')
        ->groupBy(DB::raw('DATE(voucher_date)')) // Group by the date
        ->orderBy('voucher_date', 'asc') // Sort results
        ->get();
        foreach ($current_date_vouchers as $key => $data) {
            $ids = VoucherAudit::whereRaw("DATE(voucher_date) = ?", [$data->voucher_date])->where('is_posted', '1')->pluck('id');
            $dr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->sum('dr');
            $cr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->sum('cr');
            if(env('VITE_PROJECT_TYPE') == 'hms')
            {
            $ch_cr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->where('acc_code','59')->sum('cr');
            $ch_dr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->where('acc_code','59')->sum('dr');
            }
            else
            {
                $ch_cr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->where('acc_code','189')->sum('cr');
                $ch_dr = VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->where('acc_code','189')->sum('dr');
            }
            $data['cr'] = $cr;
            $data['dr'] = $dr;
            $data['cash_in_hand'] = $ch_dr - $ch_cr;
            $data['date'] = date('Y-m-d', strtotime($data->voucher_date));
        }
        $current_date_vouchers;
        $is_posted_voucher = VoucherAudit::with('voucherType','voucherAuditDetails.chartOfAccount')->where('is_posted','1')->where('is_admin_posted','0')->get();
         return Inertia::render('Accounts/VoucherAudits/Index', [
        'vouchers' => $vouchers,
        'account_vouchers' => $account_vouchers,
        'current_date_vouchers' => $current_date_vouchers,
        'date_vouchers' => $date_vouchers,
        'is_posted_voucher' => $is_posted_voucher,
        'ipd_users' => $ipd_users,
        'filters' => ['search' => $search],
    ]);
}


    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        $jsonString = $request->getContent();

        $result = array_values(json_decode($jsonString, true));
        foreach($result as $data){
            $sql = VoucherAudit::whereId($data['id'])->first();
            if($sql->voucherAuditDetails->sum('dr')  == $sql->voucherAuditDetails->sum('cr')){
                $ids = $sql->voucherAuditDetails->whereNull('acc_code')->first();
                if(!$ids){
                    $sql->update(['is_posted'=> $data['is_posted']]);
                }
            }
        }
        DB::commit();
        return redirect()->route('vouchers-audit.index');
    }


    // public function edit(VoucherAudit $vouchers_audit)
    // {
    //     // $dateString = $voucher_audit->created_at;
    //     // $givenDate = new DateTime($dateString);
    //     // $currentDate = new DateTime();
    //     // $interval = $givenDate->diff($currentDate);
    //     // $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

    //     // if((isset(Auth::user()->userRole[0]->role_id) && Auth::user()->userRole[0]->role_id != '1') &&$totalMinutes > 10){
    //     //     return redirect('/vouchers');
    //     // }
    //     $services = Service::select('coa_id', 'name')->get();
    //     $employees = Employee::select('coa_id', 'name')->get();
    //     $vouchers_audit = $vouchers_audit->loadMissing(['voucherAuditDetails', 'voucherAuditDetails.chartOfAccount']);
    //     foreach ($vouchers_audit->voucherAuditDetails as $voucherAuditDetails) {
    //         $obj = new stdClass();
    //         $service = $services->firstWhere('coa_id', $voucherAuditDetails->acc_code);
    //         if ($service) {
    //             $obj->name = $service->name;
    //         } else {
    //             $employee = $employees->firstWhere('coa_id', $voucherAuditDetails->acc_code);


    //             if ($employee) {
    //                 $obj->name = $employee->name;
    //             } else {
    //                 $obj->name = null;
    //             }
    //         }
    //         $obj->label = $voucherAuditDetails->transaction_type;
    //         $voucherAuditDetails->transaction_type = $obj;
    //     }
    //     $voucher_types = VoucherType::select('id', 'voucher_name')->get();
    //     $chart_of_accounts = ChartOfAccount::select('id', 'acc_desc')->get();
    //     return Inertia::render('Accounts/VoucherAudits/Create', [
    //         'voucher' => $vouchers_audit,
    //         'voucher_types' => $voucher_types,
    //         'chart_of_accounts' => $chart_of_accounts,
    //     ]);
    // }

    // public function edit(VoucherAudit $vouchers_audit)
    // {
    //     // $services = Service::select('id', 'coa_id', 'name')->get();
    //     // $employees = Employee::select('id', 'coa_id', 'name')->get();
    //     // $appointments = Appointment::select('id', 'consulting_doctor_id')->get();
    //     // $admissions = AdmissionDetail::select('admission_id', 'service_id', 'doctor_id')->get();

    //     $vouchers_audit = $vouchers_audit->loadMissing(['voucherAuditDetails', 'voucherAuditDetails.chartOfAccount']);

    //     foreach ($vouchers_audit->voucherAuditDetails as $voucherAuditDetails) {
    //         $obj = new stdClass();


    //         if ($voucherAuditDetails->transaction_type == 'opd') {
    //             $test = Appointment::with('doctor')->whereId($voucherAuditDetails->transaction_no)->first();
    //             if ($test && $test->doctor && $test->doctor->coa_id === $voucherAuditDetails->acc_code) {
    //                 $obj->name = $test->doctor->name;
    //             } else {
    //                 $obj->name = null;
    //             }
    //         }
    //         else {
    //             $admissionDetails = AdmissionDetail::with(['doctor', 'service'])
    //                 ->where('admission_id', $voucherAuditDetails->transaction_no)
    //                 ->get();

    //             if ($admissionDetails->isNotEmpty()) {
    //                 $foundMatch = false;
    //                 $names = [];

    //                 foreach ($admissionDetails as $detail) {
    //                     if ($detail->doctor && $detail->doctor->coa_id === $voucherAuditDetails->acc_code) {
    //                         $names[] = $detail->doctor->name;
    //                         $foundMatch = true;
    //                     }
    //                     if ($detail->service && $detail->service->coa_id === $voucherAuditDetails->acc_code) {
    //                         $names[] = $detail->service->name;
    //                         $foundMatch = true;
    //                     }
    //                 }

    //                 $obj->name = $foundMatch ? implode(', ', $names) : null;
    //             } else {
    //                 $obj->name = null;
    //             }
    //         }

    //         if (is_null($obj->name)) {
    //             $obj->name = '';
    //         }
    //         $obj->label = $voucherAuditDetails->transaction_type;
    //         $voucherAuditDetails->transaction_type = $obj;
    //     }


    //     $voucher_types = VoucherType::select('id', 'voucher_name')->get();
    //     $chart_of_accounts = ChartOfAccount::select('id', 'acc_desc')->get();

    //     return Inertia::render('Accounts/VoucherAudits/Create', [
    //         'voucher' => $vouchers_audit,
    //         'voucher_types' => $voucher_types,
    //         'chart_of_accounts' => $chart_of_accounts,
    //     ]);
    // }
    public function edit(VoucherAudit $vouchers_audit)
    {
        $vouchers_audit = $vouchers_audit->loadMissing(['voucherAuditDetails', 'voucherAuditDetails.chartOfAccount']);

        foreach ($vouchers_audit->voucherAuditDetails as $voucherAuditDetails) {
            $obj = new stdClass();
            $accCode = $voucherAuditDetails->acc_code;

            // Default name
            $obj->name = '';
            $type = $voucherAuditDetails->transaction_type;

            // Handle OPD
            if ($type === 'opd') {
                $appointment = Appointment::with('doctor')->find($voucherAuditDetails->transaction_no);
                if ($appointment && $appointment->doctor && $appointment->doctor->coa_id === $accCode) {
                    $obj->name = $appointment->doctor->name;
                }
            }
            // Handle IPD or LAB
            elseif (in_array($type, ['ipd', 'lab'])) {
                $admissionDetails = AdmissionDetail::with(['doctor', 'service'])
                    ->where('admission_id', $voucherAuditDetails->transaction_no)
                    ->get();

                if ($admissionDetails->isNotEmpty()) {
                    $foundMatch = false;
                    $names = [];

                    foreach ($admissionDetails as $detail) {
                        // For lab: only include if acc_code is not 189 or 96
                        if ($type === 'lab' && in_array($accCode, [189, 96])) {
                            continue;
                        }

                        if ($detail->doctor && $detail->doctor->coa_id === $accCode) {
                            $names[] = $detail->doctor->name;
                            $foundMatch = true;
                        }
                        if ($detail->service && $detail->service->coa_id === $accCode) {
                            $names[] = $detail->service->name;
                            $foundMatch = true;
                        }
                    }

                    $obj->name = $foundMatch ? implode(', ', $names) : '';
                }
            }

            if (env('VITE_PROJECT_TYPE') == 'jinnah' && $type === 'lab' && !in_array($accCode, [189, 96])) {
                $obj->name = $vouchers_audit->narration ?? '';
            }
            if (env('VITE_PROJECT_TYPE') == 'hms' && $type === 'lab' && !in_array($accCode, [59, 64])) {
                $obj->name = $vouchers_audit->narration ?? '';
            }

            $obj->label = $type;
            $voucherAuditDetails->transaction_type = $obj;
        }

        $voucher_types = VoucherType::select('id', 'voucher_name')->get();
        $chart_of_accounts = ChartOfAccount::select('id', 'acc_desc')->get();

        return Inertia::render('Accounts/VoucherAudits/Create', [
            'voucher' => $vouchers_audit,
            'voucher_types' => $voucher_types,
            'chart_of_accounts' => $chart_of_accounts,
        ]);
    }



    public function update(VoucherAuditRequest $request, VoucherAudit $vouchers_audit)
    {
        DB::beginTransaction();

        $formData = $request->only($vouchers_audit->getFillable());
        $formData['updated_by'] = Auth::user()->id;
        $vouchers_audit->update($formData);

        if (
            !empty($request->voucher_audit_details) &&
            is_array($request->voucher_audit_details)
        ) {
            // $voucher->voucherDetails()->delete();
            $formData = [];
            $currentVoucherIds = [];
            foreach ($request->voucher_audit_details as $voucher_audit_details) {
                if($voucher_audit_details['id']){
                    $currentVoucherIds[] = $voucher_audit_details['id'];
                    $formData['voucher_audit_id'] = $vouchers_audit->id;
                    $formData['acc_code'] = $voucher_audit_details['chart_of_account']['id'] ?? null;
                    $formData['dr'] = $voucher_audit_details['dr'] ?? 0;
                    $formData['cr'] = $voucher_audit_details['cr'] ?? 0;
                    $formData['transaction_type'] = $voucher_audit_details['transaction_type']['label'] ?? null;
                    $formData['transaction_no'] = $voucher_audit_details['transaction_no'] ?? null;
                    $formData['remarks'] = $voucher_audit_details['remarks'] ?? null;
                    VoucherAuditDetail::whereId($voucher_audit_details['id'])->update($formData);
                }
                else{
                    $formData['voucher_audit_id'] = $vouchers_audit->id;
                    $formData['acc_code'] = $voucher_audit_details['chart_of_account']['id'] ?? null;
                    $formData['dr'] = $voucher_audit_details['dr'] ?? 0;
                    $formData['cr'] = $voucher_audit_details['cr'] ?? 0;
                    $formData['transaction_type'] = $voucher_audit_details['transaction_type']['label'] ?? null;
                    $formData['transaction_no'] = $voucher_audit_details['transaction_no'] ?? null;
                    $formData['remarks'] = $voucher_audit_details['remarks'] ?? null;
                    $vd = VoucherAuditDetail::create($formData);
                    $currentVoucherIds[] = $vd->id;
                }
            }
            VoucherAuditDetail::whereNotIn('id', $currentVoucherIds)->where('voucher_audit_id', $vouchers_audit->id)->delete();

        }

        DB::commit();

        return redirect()->route('vouchers-audit.index')->with('message', 'Voucher Audit updated successfully.');
    }



    public function approved(Request $request)
    {
        $jsonString = $request->getContent();
        $result = array_values(json_decode($jsonString, true));

        DB::beginTransaction();

        foreach($result as $data){
            Voucher::whereId($data['id'])->update([
                'is_posted' => $data['is_posted'],
            ]);
        }

        // $vouchers = VoucherAudit::where('is_admin_posted','1')->get();

        $date_vouchers = VoucherAudit::where('is_admin_posted', '1')
        ->selectRaw('DATE(voucher_date) as voucher_date')
        ->groupBy(DB::raw('DATE(voucher_date)')) // Group by the date
        ->orderBy('voucher_date', 'asc') // Sort results
        ->get();

        foreach ($date_vouchers as $key => $data) {
            $ids = $this->queryApproved($data, 'opd');
            VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->delete();
            VoucherAudit::whereIn('id', $ids)->where('is_admin_posted','1')->delete();
            $ids = $this->queryApproved($data, 'ipd');
            VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->delete();
            VoucherAudit::whereIn('id', $ids)->where('is_admin_posted','1')->delete();
            $ids = $this->queryApproved($data, 'lab');
            VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->delete();
            VoucherAudit::whereIn('id', $ids)->where('is_admin_posted','1')->delete();
            if(file_exists(base_path('config/donation.php'))) {
                $ids = $this->queryApproved($data, 'donor_fund');
                VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->delete();
                VoucherAudit::whereIn('id', $ids)->where('is_admin_posted','1')->delete();
            }
            if(file_exists(base_path('config/expense.php'))) {
                $ids = $this->queryApproved($data, 'expense');
                VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->delete();
                VoucherAudit::whereIn('id', $ids)->where('is_admin_posted','1')->delete();
            }
            $ids = $this->queryApproved($data, 'donation');
            VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->delete();
            VoucherAudit::whereIn('id', $ids)->where('is_admin_posted','1')->delete();
        }

        $vouchers = VoucherAudit::where('is_admin_posted', '1')->get();
        foreach($vouchers as $voucher){
            $store_voucher = Voucher::create([
                'voucher_type_id' => 1,
                'voucher_date' => $voucher->voucher_date,
                'voucher_posted_date' => date('Y-m-d'),
                'narration' => null,
                'is_posted' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ]);
            foreach($voucher->voucherAuditDetails as $voucher_details){
                $formData['voucher_id'] = $store_voucher->id;
                $formData['acc_code'] = $voucher_details->acc_code;
                $formData['dr'] = $voucher_details->dr ?? 0;
                $formData['cr'] = $voucher_details->cr ?? 0;
                $formData['transaction_type'] = $voucher_details->transaction_type ?? null;
                $formData['transaction_no'] = $voucher_details->transaction_no ?? null;
                $formData['system_type'] = $voucher_details->system_type ?? null;
                $formData['remarks'] = $voucher_details->remarks ?? null;
                VoucherDetail::create($formData);
            }
        }
        $ids = $vouchers->pluck('id');
        VoucherAuditDetail::whereIn('voucher_audit_id', $ids)->delete();
        VoucherAudit::where('is_admin_posted','1')->delete();

        DB::commit();

        return redirect()->route('vouchers-audit.index');
    }

    public function queryApproved($data, $type)
    {
        $vouchers = VoucherAudit::whereRaw("DATE(voucher_date) = ?", [$data->voucher_date])->where('is_admin_posted', '1')->where('type', $type)->get();

        $store_voucher = Voucher::create([
            'voucher_type_id' => 1,
            'voucher_date' => $data->voucher_date,
            'voucher_posted_date' => date('Y-m-d'),
            'narration' => $type,
            'is_posted' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        $transaction_no = $remarks = $system_type = '';
        $dr = $cr = 0;
        $voucher_array = [];
        foreach($vouchers as $voucher){
            foreach ($voucher->voucherAuditDetails as $voucher_details) {
                $parms = [];
                $parms['transaction_type'] = $voucher_details['transaction_type'];
                $parms['transaction_no'] = $voucher_details['transaction_no'].',';
                $parms['remarks'] = $voucher['narration'].',';
                $parms['system_type'] = $voucher_details['system_type'].',';
                $parms['dr'] = $voucher_details['dr'];
                $parms['cr'] = $voucher_details['cr'];
                $voucher_array[$voucher_details['acc_code']][] = $parms;
            }
        }

        foreach($voucher_array as $index => $voucher_split){
            $transaction_no = $transaction_type = $system_type = $remarks = '';
            $dr = $cr = 0;
            foreach($voucher_split as $key => $voucher_dtl){
                $transaction_type = $voucher_dtl['transaction_type'];
                $transaction_no = $transaction_no.' '.$voucher_dtl['transaction_no'];
                $system_type = $system_type .' '. $voucher_dtl['system_type'];
                $remarks = $remarks .' '. $voucher_dtl['remarks'];
                $dr += $voucher_dtl['dr'];
                $cr += $voucher_dtl['cr'];
            }
            $formData['voucher_id'] = $store_voucher->id;
            $formData['acc_code'] = $index;
            $formData['transaction_type'] = $transaction_type ?? null;
            $formData['transaction_no'] = $transaction_no ?? null;
            $formData['system_type'] = $system_type ?? null;
            $formData['remarks'] = $remarks ?? null;

            if($dr > 0){
                $formData['dr'] = $dr ?? 0;
                $formData['cr'] =  0;
                VoucherDetail::create($formData);
            }

            if($cr > 0){
                $formData['dr'] = 0;
                $formData['cr'] = $cr ?? 0;
                VoucherDetail::create($formData);
            }
        }
        return $vouchers->pluck('id');
    }
    public function postChecked(Request $request)
    {
        DB::beginTransaction();
        $jsonString = $request->getContent();
        $result = array_values(json_decode($jsonString, true));

        foreach($result as $data){
            VoucherAudit::whereId($data['id'])->update([
                'is_admin_posted' => $data['is_admin_posted'],
            ]);
        }
        DB::commit();

        return 1;
    }

    public function destroy($id)
    {
        VoucherAuditDetail::where('voucher_audit_id', $id)->delete();
        VoucherAudit::where('id', $id)->delete();

        return redirect()->route('vouchers-audit.index')->with('success', 'Vouchers Audit deleted successfully.');
    }


}
