<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BadgeUnlockedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlocked $event)
    {
        $badgeName = $event->badgeName;
        $user = $event->user;
        // Log the event information
        Log::info("Badge: $badgeName, User: $user->name");
    }
}
