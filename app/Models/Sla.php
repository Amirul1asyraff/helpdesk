<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sla extends Model
{
    protected $table = "slas";
    protected $fillable = [
        'status',
        'response_time',
        'resolution_time',
        'penalty'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
