<?php

namespace XelentAbrar\HospitalAccounts\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use XelentAbrar\HospitalAccounts\Http\Requests\Accounts\VoucherRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherType;
use XelentAbrar\HospitalAccounts\Models\Accounts\Voucher;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherDetail;
use App\Models\HRMS\Employee;
use App\Models\OPD\Service;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use stdClass;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:vouchers.index')->only('index');
        $this->middleware('checkPermission:vouchers.create')->only('create', 'store');
        $this->middleware('checkPermission:vouchers.show')->only('show');
        $this->middleware('checkPermission:vouchers.edit')->only('edit', 'update');
        $this->middleware('checkPermission:vouchers.destroy')->only('destroy');
    }
    public function index()
    {
        // $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-d');
        // $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');
        if (isset($_GET['from_date'])) {
            try {
                $from_date = \Carbon\Carbon::createFromFormat('d-m-Y', $_GET['from_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['from_date' => 'Invalid from_date format. Expected format: dd-mm-yyyy']);
            }
        } else {
            $from_date = date('Y-m-d');
        }

        if (isset($_GET['to_date'])) {
            try {
                $to_date = \Carbon\Carbon::createFromFormat('d-m-Y', $_GET['to_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['to_date' => 'Invalid to_date format. Expected format: dd-mm-yyyy']);
            }
        } else {
            $to_date = date('Y-m-d');
        }


        $vouchers = new Voucher;
        if(isset($_GET['voucher_no']) && $_GET['voucher_no'] != ''){
            $vouchers = $vouchers->where('voucher_no', $_GET['voucher_no']);
        }

        $vouchers = $vouchers->whereBetween('voucher_date', [$from_date, $to_date])->with(['voucherType:id,voucher_name', 'voucherDetails:voucher_id,cr,dr'])->limit(100)->orderByDesc('voucher_date')->paginate(100);

        foreach ($vouchers as $voucher) {
            $totalCr = 0;
            $totalDr = 0;

            foreach ($voucher->voucherDetails as $detail) {
                $totalCr += (float) $detail->cr;
                $totalDr += (float) $detail->dr;
            }

            $voucher->totalCr = $totalCr;
            $voucher->totalDr = $totalDr;
        }
        return Inertia::render('Accounts/Vouchers/Index', [
            'vouchers' => $vouchers,
          'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
            'voucher_no' => isset($_GET['voucher_no']) ? $_GET['voucher_no'] : null,
        ]);
    }

    public function create()
    {
        $voucher_types = VoucherType::select('id', 'voucher_name')->get();
        $chart_of_accounts = ChartOfAccount::select('id', 'acc_desc','acc_code')->get();
        // $voucher_number = strval($this->generateVoucherNumber());
        $voucher_number = [];
        foreach ($voucher_types as $type) {
            $voucher_number[$type->id] = strval($this->generateVoucherNumber($type->id));
        }
    // dd($voucher_number);
        return Inertia::render(
            'Accounts/Vouchers/Create',
            [
                'voucher_types' => $voucher_types,
                'chart_of_accounts' => $chart_of_accounts,
                'voucher_number' => $voucher_number
            ]
        );
    }

    public function store(VoucherRequest $request)
    {
        DB::beginTransaction();

        $voucher = new Voucher();
        $formData = $request->only($voucher->getFillable());
        $formData['created_by'] = Auth::user()->id;
        $formData['voucher_posted_date'] = date('Y-m-d');
        $formData['voucher_date'] = date('Y-m-d',strtotime($formData['voucher_date']));

        $voucher = Voucher::create($formData);

        if (
            !empty($request->voucher_details) &&
            is_array($request->voucher_details)
        ) {
            $formData = [];
            foreach ($request->voucher_details as $voucher_details) {
                $formData['voucher_id'] = $voucher->id;
                $formData['acc_code'] = $voucher_details['chart_of_account']['id'] ?? null;
                $formData['dr'] = $voucher_details['dr'] ?? 0;
                $formData['cr'] = $voucher_details['cr'] ?? 0;
                $formData['transaction_type'] = $voucher_details['transaction_type']['label'] ?? null;
                $formData['transaction_no'] = $voucher_details['transaction_no'] ?? null;
                $formData['remarks'] = $voucher_details['remarks'] ?? null;
                VoucherDetail::create($formData);
            }
        }

        DB::commit();

        return redirect()->route('vouchers.create', ['vt' => $request->voucher_type_id,  'vId'=>$voucher->id]);
    }


    public function edit(Voucher $voucher)
    {
        $voucher = $voucher->loadMissing(['voucherDetails', 'voucherDetails.chartOfAccount']);
        $services = Service::select('coa_id', 'name')->get();
        $employees = Employee::select('coa_id', 'name')->get();
        foreach ($voucher->voucherDetails as $voucherDetails) {
            $obj = new stdClass();
            $service = $services->firstWhere('coa_id', $voucherDetails->acc_code);

            if ($service) {
                $obj->name = $service->name;
            } else {
                $employee = $employees->firstWhere('coa_id', $voucherDetails->acc_code);

                if ($employee) {
                    $obj->name = $employee->name;
                } else {
                    $obj->name = null;
                }
            }

            $obj->label = $voucherDetails->transaction_type;
            $voucherDetails->transaction_type = $obj;
        }
        $voucher_types = VoucherType::select('id', 'voucher_name')->get();
         $voucher_number = [];
        foreach ($voucher_types as $type) {
            $voucher_number[$type->id] = strval($this->generateVoucherNumber($type->id));
        }
        $chart_of_accounts = ChartOfAccount::select('id', 'acc_desc','acc_code')->get();
        return Inertia::render('Accounts/Vouchers/Create', [
            'voucher' => $voucher,
            'voucher_types' => $voucher_types,
            'chart_of_accounts' => $chart_of_accounts,
             'voucher_number' => $voucher_number
        ]);
    }
    public function editFile($voucher_no)
    {
        $voucher = Voucher::where('voucher_no', $voucher_no)->first();
        if (!$voucher) {
            return redirect()->route('vouchers.index');
        }
       $dateString = $voucher->created_at;
        $givenDate = new DateTime($dateString);
        $currentDate = new DateTime();
        $interval = $givenDate->diff($currentDate);
        $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

        if (isset(Auth::user()->userRole[0]->role_id) && Auth::user()->userRole[0]->role_id != '1' && $totalMinutes > 10) {
            return redirect('/vouchers');
        }

        return redirect('/vouchers/'.$voucher->id.'/edit');
    }


    public function update(VoucherRequest $request, Voucher $voucher)
    {

        $dateString = $voucher->created_at;
        $givenDate = new DateTime($dateString);
        $currentDate = new DateTime();
        $interval = $givenDate->diff($currentDate);
        $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

        if((isset(Auth::user()->userRole[0]->role_id) && Auth::user()->userRole[0]->role_id != '1') &&$totalMinutes > 10){
            return redirect('/vouchers');
        }

        DB::beginTransaction();

        $formData = $request->only($voucher->getFillable());
        $formData['updated_by'] = Auth::user()->id;
        $formData['voucher_date'] = date('Y-m-d',strtotime($formData['voucher_date']));
        $voucher->update($formData);

        if (
            !empty($request->voucher_details) &&
            is_array($request->voucher_details)
        ) {
            // $voucher->voucherDetails()->delete();
            $formData = [];
            $currentVoucherIds = [];
            // dd($request->voucher_details);
            foreach ($request->voucher_details as $voucher_details) {
                if($voucher_details['id']){
                    $currentVoucherIds[] = $voucher_details['id'];
                    $formData['voucher_id'] = $voucher->id;
                    $formData['acc_code'] = $voucher_details['chart_of_account']['id'] ?? null;
                    $formData['dr'] = $voucher_details['dr'] ?? 0;
                    $formData['cr'] = $voucher_details['cr'] ?? 0;
                    $formData['transaction_type'] = $voucher_details['transaction_type']['label'] ?? null;
                    $formData['transaction_no'] = $voucher_details['transaction_no'] ?? null;
                    $formData['remarks'] = $voucher_details['remarks'] ?? null;
                    VoucherDetail::whereId($voucher_details['id'])->update($formData);
                }
                else{
                    $formData['voucher_id'] = $voucher->id;
                    $formData['acc_code'] = $voucher_details['chart_of_account']['id'] ?? null;
                    $formData['dr'] = $voucher_details['dr'] ?? 0;
                    $formData['cr'] = $voucher_details['cr'] ?? 0;
                    $formData['transaction_type'] = $voucher_details['transaction_type']['label'] ?? null;
                    $formData['transaction_no'] = $voucher_details['transaction_no'] ?? null;
                    $formData['remarks'] = $voucher_details['remarks'] ?? null;
                    $vd = VoucherDetail::create($formData);
                    $currentVoucherIds[] = $vd->id;
                }
            }
            VoucherDetail::whereNotIn('id', $currentVoucherIds)->where('voucher_id', $voucher->id)->delete();

        }

        DB::commit();

        return redirect()->route('vouchers.index')->with('message', 'Voucher updated successfully.');
    }


    public function destroy($id)
    {
        $voucher = VoucherDetail::where('voucher_id', $id)->delete();
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher deleted successfully.');
    }
    public function voucherPrint(Voucher $vouchers){

        $vouchers->load('voucherDetails','voucherType','voucherDetails.chartOfAccount', 'createdBy:id,name');

        $newPrintedStatus = $vouchers->is_printed == 0 ? 1 : 2;
        $vouchers->update(['is_printed' => $newPrintedStatus]);

        return Inertia::render('Accounts/VoucherAudits/VoucherPrint', [
            'voucher' => $vouchers,

        ]);
    }
    // protected function generateVoucherNumber()
    // {
    //     $currentYear = date('Y');

    //     $lastVoucher = Voucher::whereYear('created_at', $currentYear)->latest()->first();

    //     return $lastVoucher ? ++$lastVoucher->voucher_no : $currentYear.''. 1;
    // }
    protected function generateVoucherNumber($voucher_type_id)
    {
        // dd($voucher_type_id);
        $currentYear = date('Y');

        if ($voucher_type_id == 5) {
            $lastVoucher = Voucher::where('voucher_type_id', 5)
                ->whereYear('created_at', $currentYear)
                ->orderByDesc('voucher_no')
                ->first();
        }
        else if ($voucher_type_id == 4) {
            $lastVoucher = Voucher::where('voucher_type_id', 4)
            ->whereYear('created_at', $currentYear)
            ->orderByDesc('voucher_no')
            ->first();
        }
        else if ($voucher_type_id == 3){
            $lastVoucher = Voucher::where('voucher_type_id', 3)
            ->whereYear('created_at', $currentYear)
            ->orderByDesc('voucher_no')
            ->first();
        }
        else if($voucher_type_id == 2) {
            $lastVoucher = Voucher::where('voucher_type_id', 2)
            ->whereYear('created_at', $currentYear)
            ->orderByDesc('voucher_no')
            ->first();
        }
        else if ($voucher_type_id == 1) {
            $lastVoucher = Voucher::where('voucher_type_id', 1)
            ->whereYear('created_at', $currentYear)
            ->orderByDesc('voucher_no')
            ->first();
        }

        return $lastVoucher ? ++$lastVoucher->voucher_no : $currentYear.''. 1;

        // return $currentYear . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }


}
