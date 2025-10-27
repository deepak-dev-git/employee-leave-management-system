<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'request_reason',
        'status',
        'admin_remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
