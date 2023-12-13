<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Events\UnlockedEvent;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LessonWatchListener
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
    public function handle(LessonWatched $event)
    {
        // Attach the watched lesson to the user and set the 'watched' pivot column to true
        $event->user->lessons()->attach($event->lesson, ['watched' => true]);
        // Check and unlock lessons watched achievements
        $this->checkAndUnlockLessonsWatchedAchievements($event->user);
    }
    private function checkAndUnlockLessonsWatchedAchievements(User $user)
    {
        $global_achievement_list = lesson_watched_achievement_list();
        $lessonsWatchedCount = $user->watched()->count();

        // Define achievement unlocking conditions
        if ($lessonsWatchedCount == 1) {
            $this->unlockAchievement($user, $global_achievement_list[0]);
        } elseif ($lessonsWatchedCount == 5) {
            $this->unlockAchievement($user, $global_achievement_list[1]);
        } elseif ($lessonsWatchedCount == 10) {
            $this->unlockAchievement($user, $global_achievement_list[2]);
        } elseif ($lessonsWatchedCount == 25) {
            $this->unlockAchievement($user, $global_achievement_list[3]);
        } elseif ($lessonsWatchedCount == 50) {
            $this->unlockAchievement($user, $global_achievement_list[4]);
        }
    }

    private function unlockAchievement(User $user, string $achievementName)
    {
        // Implement logic for unlocking achievements
        event(new UnlockedEvent($achievementName, $user));
    }
}
