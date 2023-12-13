<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    /**
     * Get the number of achievements based on lessons watched and comments written.
     */
    public function getAchievementsAttribute()
    {
        $global_lesson_watched_achievement_list = lesson_watched_achievement_list();
        $global_comment_written_achievement_list = comment_written_achievement_list();
        $my_lesson_watched_achievements = [];
        $my_comment_written_achievements = [];
        $lessonsWatchedCount = $this->watched()->count();
        $commentsWrittenCount = $this->comments()->count();
        $totalLessonWatchedAchievement = 0;
        $totalCommentWrittenAchievement = 0;

        // Number of achievement getting from Lesson watched
        if ($lessonsWatchedCount >= 1) {
            array_push($my_lesson_watched_achievements, $global_lesson_watched_achievement_list[0]);
            $totalLessonWatchedAchievement++;
        }
        if ($lessonsWatchedCount >= 5) {
            array_push($my_lesson_watched_achievements, $global_lesson_watched_achievement_list[1]);
            $totalLessonWatchedAchievement++;
        }
        if ($lessonsWatchedCount >= 10) {
            array_push($my_lesson_watched_achievements, $global_lesson_watched_achievement_list[2]);
            $totalLessonWatchedAchievement++;
        }
        if ($lessonsWatchedCount >= 25) {
            array_push($my_lesson_watched_achievements, $global_lesson_watched_achievement_list[3]);
            $totalLessonWatchedAchievement++;
        }
        if ($lessonsWatchedCount >= 50) {
            array_push($my_lesson_watched_achievements, $global_lesson_watched_achievement_list[4]);
            $totalLessonWatchedAchievement++;
        }

        // Number of achievement getting from Comment written
        if ($commentsWrittenCount >= 1) {
            array_push($my_comment_written_achievements, $global_comment_written_achievement_list[0]);
            $totalCommentWrittenAchievement++;
        }
        if($commentsWrittenCount >= 3) {
            array_push($my_comment_written_achievements, $global_comment_written_achievement_list[1]);
            $totalCommentWrittenAchievement++;
        }
        if ($commentsWrittenCount >= 5) {
            array_push($my_comment_written_achievements, $global_comment_written_achievement_list[2]);
            $totalCommentWrittenAchievement++;
        }
        if ($commentsWrittenCount >= 10) {
            array_push($my_comment_written_achievements, $global_comment_written_achievement_list[3]);
            $totalCommentWrittenAchievement++;
        }
        if ($commentsWrittenCount >= 20) {
            array_push($my_comment_written_achievements, $global_comment_written_achievement_list[4]);
            $totalCommentWrittenAchievement++;
        }
        $totalAchievements = $totalLessonWatchedAchievement + $totalCommentWrittenAchievement;


        return [
            'total_achievement' => $totalAchievements,
            'lesson_watched_achievement_list' => $my_lesson_watched_achievements,
            'comment_written_achievement_list' => $my_comment_written_achievements,
            'total_lesson_watched_achievement' => $totalLessonWatchedAchievement,
            'total_comment_written_achievement' => $totalCommentWrittenAchievement
        ];
    }
}

