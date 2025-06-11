<?php

use Inertia\Inertia;
use XelentAbrar\HospitalAccounts\Models\Accounts\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use XelentAbrar\HospitalAccounts\Http\Controllers\Accounts\VoucherController;
use XelentAbrar\HospitalAccounts\Http\Controllers\Accounts\AccountCodeController;
use XelentAbrar\HospitalAccounts\Http\Controllers\Accounts\VoucherTypeController;
use XelentAbrar\HospitalAccounts\Http\Controllers\Accounts\VoucherAuditController;
use XelentAbrar\HospitalAccounts\Http\Controllers\Accounts\AccountReportController;
use XelentAbrar\HospitalAccounts\Http\Controllers\Accounts\ChartOfAccountController;
use XelentAbrar\HospitalAccounts\Http\Controllers\Accounts\ControlAccountController;
use XelentAbrar\HospitalAccounts\Http\Controllers\Accounts\SubHeadAccountController;
use XelentAbrar\HospitalAccounts\Http\Controllers\Accounts\SubControlAccountController;

Route::resource('control-accounts', ControlAccountController::class)->names('control-accounts');
Route::resource('sub-control-accounts', SubControlAccountController::class)->names('sub-control-accounts');
Route::resource('sub-head-accounts', SubHeadAccountController::class)->names('sub-head-accounts');
Route::resource('account-codes', AccountCodeController::class)->names('account-codes');
Route::resource('chart-of-accounts', ChartOfAccountController::class)->names('chart-of-accounts');
Route::post('chart-of-accounts-delete/{id}/{type}/{coa_id}', [ChartOfAccountController::class, 'destroy'])->name('chart-of-accounts-delete');
Route::resource('voucher-types', VoucherTypeController::class)->names('voucher-types');
Route::resource('vouchers', VoucherController::class)->names('vouchers');
Route::resource('vouchers-audit', VoucherAuditController::class)->names('vouchers-audit');
Route::get('vouchers/print/{vouchers}', [VoucherController::class, 'voucherPrint'])->name('vouchers.print');
Route::post('vouchers-audit/approved', [VoucherAuditController::class, 'approved'])->name('vouchers-audit.approved');
Route::post('vouchers-audit/post-checked', [VoucherAuditController::class, 'postChecked'])->name('vouchers-audit.admin-post-checked');
Route::get('/voucher-edit/{voucher_no}/edit', [VoucherController::class, 'editFile'])->name('voucher-edit');

Route::get('trial-balance', [AccountReportController::class,'trialBalance'])->name('trial-balance');
Route::get('trial-balance-sheet', [AccountReportController::class,'trialBalanceDC'])->name('trial-balance-sheet');
Route::get('balance-sheet', [AccountReportController::class,'balanceSheet'])->name('balance-sheet');
Route::get('balance-sheet-notes', [AccountReportController::class,'balanceSheetNotes'])->name('balance-sheet-notes');
Route::get('income-statement', [AccountReportController::class,'incomeStatement'])->name('income-statement');
Route::get('income-statement-notes', [AccountReportController::class,'incomeStatementNotes'])->name('income-statement-notes');
Route::get('party-ledger', [AccountReportController::class,'partyLedger'])->name('party-ledger');
Route::get('general-ledger', [AccountReportController::class,'generalLedger'])->name('general-ledger');
Route::get('get-accounts', [AccountReportController::class, 'getAccounts'])->name('get-accounts');
Route::get('cash-books', [AccountReportController::class, 'getCashBooks'])->name('cash-books');
Route::get('journal-books', [AccountReportController::class, 'getJournalBooks'])->name('journal-books');
Route::post('party-ledger', [AccountReportController::class, 'partyLedger'])->name('party-ledger');
Route::get('/voucher-activity-log', function () {
    $user = Auth::user();
    $vouchers = Voucher::with([
        'createdBy:id,name',
        'voucherDetails.chartOfAccount:id,acc_desc',
        'voucherType:id,voucher_name'
    ]);

    if ($user) {
        $vouchers = $vouchers->orderBy('voucher_date', 'desc');
    }

    if (isset($_GET['search']) && $_GET['search'] !== '') {
        $searchTerm = $_GET['search'];
        $vouchers = $vouchers->whereHas('createdBy', function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', '%' . $searchTerm . '%')->orWhere('id',$searchTerm)
                ->orWhere('voucher_date', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('narration', 'LIKE', '%' . $searchTerm . '%');
        });
    }

    if (isset($_GET['from_date']) && $_GET['from_date'] !== '') {
        $fromDate = $_GET['from_date'];
        $vouchers = $vouchers->whereDate('voucher_date', '>=', $fromDate);
    }

    if (isset($_GET['to_date']) && $_GET['to_date'] !== '') {
        $toDate = $_GET['to_date'];
        $vouchers = $vouchers->whereDate('voucher_date', '<=', $toDate);
    }

    $vouchers = $vouchers->paginate(200);
    $vouchers = $vouchers->appends([
        'search' => request('search'),
        'from_date' => request('from_date'),
        'to_date' => request('to_date')
    ]);

    $vouchers->getCollection()->each(function ($voucher) {
        $voucher->account_descriptions = $voucher->voucherDetails->map(function ($detail) {
            $description = $detail?->chartOfAccount?->acc_desc;

            $account_description = [];
            if ($detail->cr > 0) {
                $account_description[] = $description . " (CR: " . $detail->cr . ")";
            }
            if ($detail->dr > 0) {
                $account_description[] = $description . " (DR: " . $detail->dr . ")";
            }
            return implode(', ', $account_description);
        })->implode(', ');
    });

    return Inertia::render('HRMS/Logs/VoucherActivityLog', [
        'voucher_log' => $vouchers,
        'filters' => request()->only(['search', 'from_date', 'to_date']),
    ]);
})->name('voucher-activity-log');



