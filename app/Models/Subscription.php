<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'billing_cycle',
        'amount',
        'status',
        'starts_at',
        'ends_at',
        'mercadopago_subscription_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->ends_at > now();
    }
}
