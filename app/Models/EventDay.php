<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EventDay extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $fillable = [
        'event_id',
        'date',
        'start_time',
        'end_time'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticket_types()
    {
        return $this->belongsToMany(TicketType::class, 'ticket_type_event_day', 'event_day_id', 'ticket_type_id');
    }
}
