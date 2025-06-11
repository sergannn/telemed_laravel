<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

use App\Http\Controllers\OnlineController;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/update-value', [OnlineController::class, 'updateValue']);
// Route::get('/get-value', [OnlineController::class, 'getValue']);

// use App\Http\Controllers\UserAuthController;
// use App\Http\Controllers\UserCatsController;
// use App\Http\Controllers\MarkerController;
// use App\Http\Controllers\BookController;
// Route::get('/cats', [UserCatsController::class, 'showCats']);
// Route::get('/users', [UserAuthController::class, 'showUsers']);
// Route::get('/markers', [MarkerController::class, 'show']);
// Route::get('/books', [BookController::class, 'show']);
// Route::get('/books/{book}', [BookController::class, 'show_book']);
//Route::post('/markers', 'MarkerController@store');
//Route::post('/markers', [MarkerController::class, 'storeApi']);


// Route::post('/register', 'AuthController@register');
// Route::post('/login', 'AuthenticatedSessionController@store');
//  Route::get('/login', [AuthenticatedSessionController::class,'store']);


// Route::get('login', [UserAuthController::class, 'login']);
// Route::get('logout', [UserAuthController::class, 'logout']);


// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('/markers/add', [MarkerController::class, 'storeMarker']);
// });



// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::delete('/markers/{markerId}', [MarkerController::class, 'deleteMarker']);
// });

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/user-markers', [MarkerController::class, 'showMarkersForUser']);
// });
//require __DIR__.'/auth.php';

/*===========================
=           specialization---controllers           =
=============================*/


/*=====  End of specialization---controllers   ======*/

/*===========================
=           specializations           =
=============================*/

Route::apiResource('/specializations', \App\Http\Controllers\API\SpecializationController::class);

Route::get("activespecializations",[\App\Http\Controllers\API\SpecializationController::class,'showActive']);
/*=====  End of specializations   ======*/


use App\Http\Controllers\Auth\VerifyEmailControllerSerg;

Route::get('/verify-email', [VerifyEmailControllerSerg::class, '__invoke']);

/*===========================
=           faqs           =
=============================*/

Route::apiResource('/faqs', \App\Http\Controllers\API\FaqController::class);

/*=====  End of faqs   ======*/
