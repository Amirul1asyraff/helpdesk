<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Response extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "responses";
    protected $fillable = [
        'content',
        'ticket_id',
        'response_by'  // Changed from user_id to response_by
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'response_by');  // Specify the foreign key
    }
}
