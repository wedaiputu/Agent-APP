<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'password',
        'status',
        'limit_uptime',
        'price',
        'profile',
        'comment',
        'agent_id',
    ];

    /**
     * Get the agent that owns the voucher.
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
