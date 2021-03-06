<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTicket extends Model
{
    use HasFactory;

    public function ticket_type(){
        return $this->belongsTo(TicketType::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
