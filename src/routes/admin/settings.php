<?php

use XelentAbrar\HospitalAccounts\Http\Controllers\AccountSettingController;
use Illuminate\Support\Facades\Route;


Route::resource('acc_settings', AccountSettingController::class)->names([
    'index'   => 'acc_settings.index',
    'create'  => 'acc_settings.create',
    'store'   => 'acc_settings.store',
    'show'    => 'acc_settings.show',
    'edit'    => 'acc_settings.edit',
    'update'  => 'acc_settings.update',
    'destroy' => 'acc_settings.destroy',
]);
Route::get('/account/settings', [AccountSettingController::class, 'index'])->name('account.settings');

Route::post('/account/settings', [AccountSettingController::class, 'store'])->name('account.settings.store');
