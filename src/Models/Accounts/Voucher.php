<?php

namespace XelentAbrar\HospitalAccounts\Models\Accounts;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'voucher_type_id',
        'voucher_date',
        'voucher_no',
        'narration',
        'is_posted',
        'created_by',
        'updated_by',
        'created_at',
        'deleted_at',
        'is_printed',
        'voucher_posted_date',
    ];

    protected $casts = [
        'voucher_date' => 'date',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($voucher) {
    //         $currentYear = date('Y');

    //         $lastVoucher = Voucher::whereYear('created_at', $currentYear)->orderBy('id', 'desc')->first();

    //         $voucher->voucher_no = $lastVoucher ? ++$lastVoucher->voucher_no : $currentYear.''. 1;
    //         // $currentYear . substr($voucherNumber, 4, '0', STR_PAD_LEFT);
    //     });
    // }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($voucher) {
            $currentYear = date('Y');

            $lastVoucher = Voucher::where('voucher_type_id', $voucher->voucher_type_id)
                ->whereYear('created_at', $currentYear)
                ->orderByDesc('voucher_no')
                ->first();

            if ($lastVoucher) {
                $lastNumber = (int) substr($lastVoucher->voucher_no, 4);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $voucher->voucher_no = $lastVoucher ? ++$lastVoucher->voucher_no : $currentYear.''. 1;
        });
    }

    function voucherType(): BelongsTo
    {
        return $this->belongsTo(VoucherType::class);
    }

    function voucherDetails(): HasMany
    {
        return $this->hasMany(VoucherDetail::class);
    }

    function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
