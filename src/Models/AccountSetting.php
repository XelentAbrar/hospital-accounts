<?php

namespace XelentAbrar\HospitalAccounts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountSetting extends Model
{
    use HasFactory;
    protected $table='acc_settings';

    protected $fillable = ['key', 'value'];

}
