<?php

namespace App\Http\Livewire\Cards;

use App\Models\Event;
use App\Models\PostTicket;
use Livewire\Component;

class SuccessfulScans extends Component
{
    public $title = 'Successful Scans';
    public $subtitle = '';
    public $icon = 'las la-barcode';
    public $event_id;
    public function mount()
    {
        $event = Event::find($this->event_id);
        $ticket_types = $event->ticket_types;
        // get sum of post tickets scans
        $tickets_count = PostTicket::whereIn('ticket_type_id', $ticket_types->pluck('id'))->sum('scans');
        $this->subtitle = $tickets_count;
    }

    public function render()
    {
        return view('livewire.cards.successful-scans');
    }
}
