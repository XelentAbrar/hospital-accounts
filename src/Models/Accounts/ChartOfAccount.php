<?php

namespace XelentAbrar\HospitalAccounts\Models\Accounts;

use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartOfAccount extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'coa4_id',
        'acc_code',
        'acc_desc',
        'old_acc_code',
        'old_acc_id',
        'opening',
        'created_by',
        'updated_by',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $accountCode = $model->accountCode;
            $maxAccCode = ChartOfAccount::where('coa4_id', $model->coa4_id)
            ->select(\DB::raw('MAX(CAST(acc_code AS UNSIGNED)) as max_acc_code'))
            ->pluck('max_acc_code')
            ->first();

            if ($maxAccCode) {
                $numericPart = intval(preg_replace('/^'.$accountCode->acc_code.'/', '', $maxAccCode));
                $numericPart++;
                $numericPart = str_pad($numericPart, 5, "0", STR_PAD_LEFT);
                $model->acc_code = $accountCode->acc_code.''. $numericPart;
            } else {
                $model->acc_code = $accountCode->acc_code.'0000'. (1);
            }
        });
    }


    function accountCode(): BelongsTo
    {
        return $this->belongsTo(AccountCode::class, 'coa4_id');
    }

    function donor(): BelongsTo
    {
        if(file_exists(base_path('config/donation.php'))) {
            return $this->belongsTo(Donor::class, 'id', 'coa_id');
        }
    }


    function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    function voucherDetail(): HasMany
    {
        return $this->hasMany(VoucherDetail::class, 'acc_code');
    }
}
