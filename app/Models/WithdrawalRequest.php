<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'provider_id',
        'cashier_id',
        'amount',
        'commission',
        'final_amount',
        'shamcash_account',
        'status',
        'note',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'amount' => 'float',
        'commission' => 'float',
        'final_amount' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

   

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
    public function cashier()
{
    return $this->belongsTo(
        Cashier::class,
        'cashier_id'
    );
}
}