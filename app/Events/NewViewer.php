<?php

namespace App\Events;

use App\Modules\Docs\Models\Doc;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewViewer implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $doc;
    public $viewer;

    /**
     * Create a new event instance.
     *
     * @param Doc $doc
     * @param     $user
     */
    public function __construct(Doc $doc, $user)
    {
        $this->doc = $doc;
        $this->viewer = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel($this->doc->channel_name);
    }
}
