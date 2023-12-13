<?php

use App\Http\Controllers\AchievementsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/{user_id}/lesson_watch/{lesson_id}', [AchievementsController::class, 'lesson_watched_event']);

//Excluding CSRF middleware for the simplicity
Route::post('user/{user_id}/comment_written',[AchievementsController::class, 'comment_written_event'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);
