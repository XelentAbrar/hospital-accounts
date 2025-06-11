<?php

namespace XelentAbrar\HospitalAccounts\Models\Accounts;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ControlAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'acc_code',
        'acc_desc',
        'old_acc_code',
        'old_acc_id',
        'created_by',
        'updated_by',
        'created_at'
    ];

    protected static $controlAccountCode = 1;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $maxAccCode = static::max('acc_code');
            $model->acc_code = $maxAccCode ? +$maxAccCode + 10 : 10;
        });
    }

    function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    function subControlAccount(): HasMany
    {
        return $this->hasMany(SubControlAccount::class, 'coa1_id');
    }
}
