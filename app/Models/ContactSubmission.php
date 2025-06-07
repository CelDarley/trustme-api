<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status'
    ];

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }
}
