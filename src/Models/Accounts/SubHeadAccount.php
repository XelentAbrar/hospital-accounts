<?php

namespace XelentAbrar\HospitalAccounts\Models\Accounts;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubHeadAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'coa2_id',
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
            $subControlAccount = $model->subControlAccount;
            $maxAccCode = SubHeadAccount::where('coa2_id', $model->coa2_id)
            ->select(\DB::raw('MAX(CAST(acc_code AS UNSIGNED)) as max_acc_code'))
            ->pluck('max_acc_code')
            ->first();

            if ($maxAccCode) {
                $numericPart = intval(preg_replace('/^'.$subControlAccount->acc_code.'/', '', $maxAccCode));
                $numericPart++;
                $numericPart = $numericPart < 10 ? '0'.$numericPart : $numericPart;
                $model->acc_code = $subControlAccount->acc_code.''. $numericPart;
            } else {
                $model->acc_code = $subControlAccount->acc_code.'0'. (1);
            }
        });
    }


    function subControlAccount(): BelongsTo
    {
        return $this->belongsTo(SubControlAccount::class, 'coa2_id');
    }

    function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    function accountCode(): HasMany
    {
        return $this->hasMany(AccountCode::class, 'coa3_id');
    }
}
