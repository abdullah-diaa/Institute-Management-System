<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\YoutubeVideoController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\HomeController;

/*
|---------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------
*/













 //[Route for home] ------------------------------------------------------------------------------------------------------------

 Route::prefix('v1/home')->middleware('throttle:60,1')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('api.home.index');
});


//------------------------------------------------------------------------------------------------------------



 //[All routes for youtube-videos] ------------------------------------------------------------------------------------------------------------

Route::prefix('v1/youtube-videos')->middleware('throttle:60,1')->group(function () {
   
    Route::get('/', [YoutubeVideoController::class, 'index']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/', [YoutubeVideoController::class, 'store']);
        Route::put('{id}', [YoutubeVideoController::class, 'update']);
        Route::delete('{id}', [YoutubeVideoController::class, 'destroy']);
    });
});

 //------------------------------------------------------------------------------------------------------------




 //[All routes for playlists] ------------------------------------------------------------------------------------------------------------

 Route::prefix('v1/playlists')->middleware('throttle:60,1')->group(function () {
        Route::get('/', [PlaylistController::class, 'index'])->name('api.playlists.index'); 
        Route::get('/{id}', [PlaylistController::class, 'show'])->name('api.playlists.show');
  
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/', [PlaylistController::class, 'store'])->name('api.playlists.store'); 
        Route::put('/{playlist}', [PlaylistController::class, 'update'])->name('api.playlists.update'); 
        Route::delete('/{playlist}', [PlaylistController::class, 'destroy'])->name('api.playlists.destroy'); 
    });
});

 //---------------------------------------------------------------------------------------------------------------------------------------------




 //[All routes for posts] ------------------------------------------------------------------------------------------------------------

 Route::prefix('v1/posts')->middleware('throttle:60,1')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('api.posts.index'); 
        Route::get('/{id}', [PostController::class, 'show'])->name('api.posts.show'); 

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/', [PostController::class, 'store'])->name('api.posts.store'); 
        Route::put('/{id}', [PostController::class, 'update'])->name('api.posts.update');
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('api.posts.destroy');
    });
});
//---------------------------------------------------------------------------------------------------------------------------------------------




 //[All routes for assignments] ------------------------------------------------------------------------------------------------------------

Route::prefix('v1/assignments')->middleware('throttle:60,1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('api.assignments.index'); // List assignments
        Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('api.assignments.show'); 
    });

    Route::middleware(['auth:sanctum', 'can:manage-assignments'])->group(function () {
        Route::post('/', [AssignmentController::class, 'store'])->name('api.assignments.store'); 
        Route::put('/{assignment}', [AssignmentController::class, 'update'])->name('api.assignments.update'); 
        Route::delete('/{assignment}', [AssignmentController::class, 'destroy'])->name('api.assignments.destroy'); 
    });
});
//---------------------------------------------------------------------------------------------------------------------------------------------




 //[All routes for subscriptions] ------------------------------------------------------------------------------------------------------------

Route::prefix('v1/subscriptions')->middleware('throttle:60,1')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('api.subscriptions.index'); 
        Route::post('/{course}', [SubscriptionController::class, 'store'])->name('api.subscriptions.store'); 
        Route::put('/{id}', [SubscriptionController::class, 'update'])->name('api.subscriptions.update'); 
        Route::delete('/{id}', [SubscriptionController::class, 'destroy'])->name('api.subscriptions.destroy'); 
    });

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/successful', [SubscriptionController::class, 'successfulSubscriptions'])->name('api.subscriptions.successfulSubscriptions'); // List of successful subscriptions
        Route::get('/failed', [SubscriptionController::class, 'failedSubscriptions'])->name('api.subscriptions.failedSubscriptions'); // List of failed subscriptions
        Route::get('/{id}', [SubscriptionController::class, 'show'])->name('api.subscriptions.show');
    });
});

//---------------------------------------------------------------------------------------------------------------------------------------------




 //[All routes for answers] ------------------------------------------------------------------------------------------------------------

Route::prefix('v1/answers')->middleware('throttle:60,1')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('assignment/{assignmentId}', [AnswerController::class, 'index'])->name('api.answers.index'); // List answers for a specific assignment
        Route::get('{id}', [AnswerController::class, 'show'])->name('api.answers.show'); // View a specific answer
    });

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/', [AnswerController::class, 'store'])->name('api.answers.store'); 
        Route::delete('assignment/{assignmentId}/{answerId}', [AnswerController::class, 'destroy'])->name('api.answers.destroy'); // Delete an answer for a specific assignment
    });
});
//---------------------------------------------------------------------------------------------------------------------------------------------




 //[All routes for courses] ------------------------------------------------------------------------------------------------------------

Route::prefix('v1/courses')->middleware('throttle:60,1')->group(function () {

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('api.courses.index');
        Route::get('{course}', [CourseController::class, 'show'])->name('api.courses.show');
    });

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/', [CourseController::class, 'store'])->name('api.courses.store');
        Route::put('{course}', [CourseController::class, 'update'])->name('api.courses.update');
        Route::delete('{course}', [CourseController::class, 'destroy'])->name('api.courses.destroy');
    });
});
//---------------------------------------------------------------------------------------------------------------------------------------------




 //[All routes for Profiles] ------------------------------------------------------------------------------------------------------------

Route::prefix('v1/profiles')->middleware('throttle:60,1')->group(function () {

    Route::middleware(['admin'])->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('api.profiles.index'); 
        Route::delete('/{profile}', [ProfileController::class, 'destroy'])->name('api.profiles.destroy');
    });

    Route::get('/{profile}', [ProfileController::class, 'show'])->name('api.profiles.show'); 

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/', [ProfileController::class, 'store'])->name('api.profiles.store');
    });
});

//---------------------------------------------------------------------------------------------------------------------------------------------




 //[All routes for users] ------------------------------------------------------------------------------------------------------------

Route::prefix('v1/users')->middleware(['auth:sanctum', 'admin', 'throttle:60,1'])->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('api.users.index');      
    Route::get('/{id}', 'show')->name('api.users.show');    
    Route::put('/{id}', 'update')->name('api.users.update');  
    Route::delete('/{id}', 'destroy')->name('api.users.destroy'); 
});
//---------------------------------------------------------------------------------------------------------------------------------------------




 //[All routes for enrollments] ------------------------------------------------------------------------------------------------------------

Route::prefix('v1/enrollments')->middleware(['auth:sanctum', 'admin', 'throttle:60,1'])->controller(EnrollmentController::class)->group(function () {
    Route::post('/{user}', 'store')->name('api.enrollments.store');      
    Route::get('/{user}', 'edit')->name('api.enrollments.edit');       
    Route::put('/{user}', 'update')->name('api.enrollments.update');     
});
//---------------------------------------------------------------------------------------------------------------------------------------------




 //[All routes for settings] ------------------------------------------------------------------------------------------------------------

 Route::prefix('v1/settings')->middleware(['auth:sanctum', 'throttle:60,1'])->controller(SettingsController::class)->group(function () {
    Route::get('/', 'index')->name('api.settings.index'); // Get user's settings
    Route::put('/name', 'updateName')->name('api.settings.updateName'); // Update name if the last update was more than one month
    Route::put('/phone', 'updatePhoneNumber')->name('api.settings.updatePhoneNumber'); // Update phone number if the last update was more than one month
});
//---------------------------------------------------------------------------------------------------------------------------------------------




 //[All Authentication routes] ------------------------------------------------------------------------------------------------------------

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//---------------------------------------------------------------------------------------------------------------------------------------------


