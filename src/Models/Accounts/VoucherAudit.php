<?php

namespace XelentAbrar\HospitalAccounts\Models\Accounts;

use App\Models\IPD\Admission;
use App\Models\User;
use App\Models\OPD\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherAudit extends Model
{
    use HasFactory;


    protected $fillable = [
        'voucher_type_id',
        'voucher_date',
        'narration',
        'type',
        'parent_id',
        'is_admin_posted',
        'is_posted',
        'created_by',
        'updated_by',
        'created_at',
    ];

    protected $casts = [
        'voucher_date' => 'date',
    ];
    function voucherType(): BelongsTo
    {
        return $this->belongsTo(VoucherType::class);
    }

    function voucherAuditDetails(): HasMany
    {
        return $this->hasMany(VoucherAuditDetail::class);
    }

    function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }
}
