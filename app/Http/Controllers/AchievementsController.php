<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\Routing\Loader\Configurator\collection;

class AchievementsController extends Controller
{
    public function index(User $user)
    {

        $user_achievements = $user->achievements;
        $unlockedAchievements = array_merge($user_achievements['lesson_watched_achievement_list'], $user_achievements['comment_written_achievement_list']);
        $remaining_lesson_watched_achievement_arr =  array_diff(lesson_watched_achievement_list(),$user_achievements['lesson_watched_achievement_list']);
        $remaining_comment_written_achievement_arr =  array_diff(comment_written_achievement_list(),$user_achievements['comment_written_achievement_list']);

        $nextAvailableAchievements = array_filter([
            collect($remaining_lesson_watched_achievement_arr)->first(),
            collect($remaining_comment_written_achievement_arr)->first(),
        ]);
        $badge_details = $this->get_badge_details($user);
        return response()->json([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $badge_details['current_badge'],
            'next_badge' => $badge_details['next_available_badge'],
            'remaining_to_unlock_next_badge' => $badge_details['remaining_to_unlock_next_badge']
        ]);
    }

    public function get_badge_details($user){
        $global_badge_name_list = badge_name_list();
        $achievement = $user->achievements['total_achievement'];
        $remaining_to_unlock_next_badge = 1;
        //return $achievement;
        $my_badges = [];
        if ($achievement >= 1 ) {
            array_push($my_badges, $global_badge_name_list[0]);
            $remaining_to_unlock_next_badge = 4 - $achievement;
        }
        if ($achievement >= 4) {
            array_push($my_badges, $global_badge_name_list[1]);
            $remaining_to_unlock_next_badge = 8 - $achievement;
        }
        if($achievement >= 8) {
            array_push($my_badges, $global_badge_name_list[2]);
            $remaining_to_unlock_next_badge = 10 - $achievement;
        }
        if($achievement >= 10) {
            array_push($my_badges, $global_badge_name_list[3]);
            $remaining_to_unlock_next_badge =0;
        }
        return [
            'current_badge' => collect($my_badges)->last(),
            'next_available_badge' => collect(array_diff($global_badge_name_list,$my_badges))->first(),
            'remaining_to_unlock_next_badge' => $remaining_to_unlock_next_badge,
        ];

    }


    public function lesson_watched_event($user_id, $lesson_id){
        $lesson = Lesson::find($lesson_id);
        $user = User::find($user_id);
        event(new \App\Events\LessonWatched($lesson, $user));
    }
    public function comment_written_event(Request $request, $user_id){
        $user = User::find($user_id);
        $comment = $user->comments()->save(new Comment($request->only('body')));
        event(new \App\Events\CommentWritten($comment));
    }
}
