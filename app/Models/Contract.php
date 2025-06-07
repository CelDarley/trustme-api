<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'party_a_name',
        'party_a_email',
        'party_b_name',
        'party_b_email',
        'status',
        'signed_at'
    ];

    protected $casts = [
        'signed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isSigned()
    {
        return $this->status === 'signed';
    }
}
