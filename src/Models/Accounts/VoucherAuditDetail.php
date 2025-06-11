<?php

namespace XelentAbrar\HospitalAccounts\Models\Accounts;

use App\Models\IPD\Admission;
use XelentAbrar\HospitalLab\Models\Lab\PatientTest;
use App\Models\OPD\Appointment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherAuditDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_audit_id',
        'acc_code',
        'dr',
        'cr',
        'transaction_type',
        'transaction_no',
        'remarks',
        'system_type',
    ];

    function voucherAudit(): BelongsTo
    {
        return $this->belongsTo(VoucherAudit::class);
    }

    function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'acc_code');
    }
    function opd(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'transaction_no', 'id')
                    ->whereHas('voucherAuditDetails', function($query) {
                        $query->where('transaction_type', 'opd');
                    });
    }
    function lab(): BelongsTo
    {
        if(file_exists(base_path('config/lab.php'))) {
            return $this->belongsTo(PatientTest::class, 'transaction_no', 'id')
                    ->whereHas('voucherAuditDetails', function($query) {
                        $query->where('transaction_type', 'lab');
                    });
                }
    }
    function ipd(): BelongsTo
    {
        return $this->belongsTo(Admission::class, 'transaction_no', 'id')
                    ->whereHas('voucherAuditDetails', function($query) {
                        $query->where('transaction_type', 'ipd');
                    });
    }



}
