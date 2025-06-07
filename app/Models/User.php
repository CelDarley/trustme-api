<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birth_date',
        'gender'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function seals()
    {
        return $this->hasMany(Seal::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();
    }

    public function hasActivePlan()
    {
        return $this->activeSubscription() !== null;
    }

    public function canCreateContracts()
    {
        $subscription = $this->activeSubscription();
        if (!$subscription) return false;

        $plan = $subscription->plan;
        if ($plan->isUnlimited('contracts')) return true;

        $contractsCount = $this->contracts()->count();
        return $contractsCount < $plan->contracts_limit;
    }

    public function canCreateSeals()
    {
        $subscription = $this->activeSubscription();
        if (!$subscription) return false;

        $plan = $subscription->plan;
        if ($plan->isUnlimited('seals')) return true;

        $sealsCount = $this->seals()->count();
        return $sealsCount < $plan->seals_limit;
    }
}
