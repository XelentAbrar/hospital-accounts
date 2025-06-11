<?php

namespace XelentAbrar\HospitalAccounts\Models\Accounts;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'coa3_id',
        'acc_code',
        'acc_desc',
        'old_acc_code',
        'old_acc_id',
        'created_by',
        'updated_by',
        'created_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $subHeadAccount = $model->subHeadAccount;
            $maxAccCode = AccountCode::where('coa3_id', $model->coa3_id)
            ->select(\DB::raw('MAX(CAST(acc_code AS UNSIGNED)) as max_acc_code'))
            ->pluck('max_acc_code')
            ->first();

            if ($maxAccCode) {
                $numericPart = intval(preg_replace('/^'.$subHeadAccount->acc_code.'/', '', $maxAccCode));
                $numericPart++;
                $numericPart = $numericPart < 10 ? '0'.$numericPart : $numericPart;
                $model->acc_code = $subHeadAccount->acc_code.''. $numericPart;
            } else {
                $model->acc_code = $subHeadAccount->acc_code.'0'. (1);
            }
        });
    }


    function subHeadAccount(): BelongsTo
    {
        return $this->belongsTo(SubHeadAccount::class, 'coa3_id');
    }

    function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    function chartOfAccount(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'coa4_id');
    }
}
