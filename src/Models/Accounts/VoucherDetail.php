<?php

namespace XelentAbrar\HospitalAccounts\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class VoucherDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'voucher_id',
        'acc_code',
        'dr',
        'cr',
        'transaction_type',
        'transaction_no',
        'remarks',
        'system_type',
        'deleted_at',
    ];

    // Mutator for encrypting column1
    // public function setCrAttribute($value)
    // {
    //     $this->attributes['cr'] = Crypt::encryptString($value);
    // }

    // // Accessor for decrypting column1
    // public function getCrAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }

    // // Mutator for encrypting column2
    // public function setDrAttribute($value)
    // {
    //     $this->attributes['dr'] = Crypt::encryptString($value);
    // }

    // // Accessor for decrypting column2
    // public function getDrAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }

    function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'acc_code');
    }
}
