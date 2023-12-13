<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Events\UnlockedEvent;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentWrittenListener
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
    public function handle(CommentWritten $event)
    {
        // Check and unlock comments written achievements
        $this->checkAndUnlockCommentsWrittenAchievements($event->comment->user);

    }
    private function checkAndUnlockCommentsWrittenAchievements(User $user)
    {
        $global_achievement_list = comment_written_achievement_list();
        $commentsWrittenCount = $user->comments()->count();

        // Define achievement unlocking conditions
        if ($commentsWrittenCount == 1) {
            $this->unlockAchievement($user, $global_achievement_list[0]);
        } elseif ($commentsWrittenCount == 3) {
            $this->unlockAchievement($user, $global_achievement_list[1]);
        } elseif ($commentsWrittenCount == 5) {
            $this->unlockAchievement($user, $global_achievement_list[2]);
        } elseif ($commentsWrittenCount == 10) {
            $this->unlockAchievement($user, $global_achievement_list[3]);
        } elseif ($commentsWrittenCount == 20) {
            $this->unlockAchievement($user, $global_achievement_list[4]);
        }
    }

    private function unlockAchievement(User $user, string $achievementName)
    {
        // Implement logic for unlocking achievements
        event(new UnlockedEvent($achievementName, $user));
    }
}
