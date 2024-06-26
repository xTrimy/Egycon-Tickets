<?php

namespace App\Http\Livewire\Cards;

use App\Models\Event;
use Livewire\Component;

class AcceptedRequests extends Component
{
    public $title = 'Accepted Requests';
    public $subtitle = '';
    public $icon = 'las la-check';
    public $event_id;
    public function mount()
    {
        /** @var Event $event */
        $event = app(Event::class);
        $posts = $event->posts()->where('status', 1)->count();
        $this->subtitle = $posts;
    }

    public function render()
    {
        return view('livewire.cards.accepted-requests');
    }
}
