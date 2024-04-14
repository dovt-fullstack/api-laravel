<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\VerifyTokenMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);
Route::get('/get-all/questions', [QuestionController::class, 'getAllQuestion']);
Route::post('/choose-question/{id}', [QuestionController::class, 'checkUserChoose']);
Route::get('/get-all-question-used-user/{id}', [QuestionController::class, 'getAlldetailsUserChoose']);
Route::get('/get-id-question-used-user/{id}', [QuestionController::class, 'getIdDetailsUserChoose']);
Route::post('update-password', [UserController::class, 'resetPassword']);

Route::get('/questions/{id}', [QuestionController::class, 'getIdQuestion']);
Route::middleware('auth:api')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('logout', [ProfileController::class, 'logout']);
    Route::put('profile/update/{id}', [UserController::class, 'updateProfile']);

    Route::put('/users/{id}/role', [UserController::class, 'updateRole'])->middleware(VerifyTokenMiddleware::class);
    Route::post('/create-question', [QuestionController::class, 'createQuestion'])->middleware(VerifyTokenMiddleware::class);
    Route::delete('/delete-questions/{id}', [QuestionController::class, 'deleteQuestion'])->middleware(VerifyTokenMiddleware::class);
    Route::put('/edit-questions/{id}', [QuestionController::class, 'updateQuestion'])->middleware(VerifyTokenMiddleware::class);
});
