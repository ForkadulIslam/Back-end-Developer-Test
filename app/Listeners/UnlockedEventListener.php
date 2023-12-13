<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Events\UnlockedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UnlockedEventListener
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
    public function handle(UnlockedEvent $event)
    {
        $eventName = $event->eventName;
        $user = $event->user;

        Log::info("Achievement: $eventName, User: $user->name");
        // Additional logic for handling badge unlocking
        $this->checkAndUnlockBadge($user);
    }
    private function checkAndUnlockBadge($user)
    {
        $global_badge_name_list = badge_name_list();
        $totalAchievements = $user->achievements['total_achievement'];

        // Define badge unlocking conditions
        if ($totalAchievements == 0 || $totalAchievements == 1 ) {
            $this->unlockBadge($user, $global_badge_name_list[0]);
        } elseif ($totalAchievements == 4) {
            $this->unlockBadge($user, $global_badge_name_list[1]);
        } elseif ($totalAchievements == 8) {
            $this->unlockBadge($user, $global_badge_name_list[2]);
        } elseif ($totalAchievements == 10) {
            $this->unlockBadge($user, $global_badge_name_list[3]);
        }
    }

    private function unlockBadge($user, $badgeName)
    {
        // Implement logic for unlocking badges
        event(new BadgeUnlocked($badgeName, $user));
    }
}
