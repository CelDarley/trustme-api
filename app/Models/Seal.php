<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'verification_status',
        'verification_notes',
        'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }
}
