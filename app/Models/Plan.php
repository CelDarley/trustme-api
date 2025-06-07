<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'seals_limit',
        'contracts_limit',
        'monthly_price',
        'six_months_price',
        'yearly_price',
        'is_active'
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'six_months_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function isUnlimited($feature)
    {
        return is_null($this->{$feature . '_limit'});
    }
}
