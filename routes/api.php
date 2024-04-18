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
// form login email , password
Route::post('login', [LoginController::class, 'login']);

// api register
// {
//   "name": "Jhon Doe",
//   "email": "jhon@example.com",
//   "password": "12345678",
//   "password_confirmation": "12345678"
// }
Route::post('register', [RegisterController::class, 'register']);
Route::get('/get-all/questions', [QuestionController::class, 'getAllQuestion']);

// api/choose-question/id
// body : {"idUser": 456,
//     "user_answer": "your_answer_here"
// }
//

Route::post('/choose-question/{id}', [QuestionController::class, 'checkUserChoose']);

// idUser
Route::get('/get-all-question-used-user/{id}', [QuestionController::class, 'getAlldetailsUserChoose']);

// id từ bảng Details_User_Choose
Route::get('/get-id-question-used-user/{id}', [QuestionController::class, 'getIdDetailsUserChoose']);

//  'name' ,
            // 'email' ,
            // 'new_password'',
Route::post('update-password', [UserController::class, 'resetPassword']);

//id question
Route::get('/questions/{id}', [QuestionController::class, 'getIdQuestion']);
Route::middleware('auth:api')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('logout', [ProfileController::class, 'logout']);

    // name ,email
    Route::put('profile/update/{id}', [UserController::class, 'updateProfile']);

    //id user ,  role
    Route::put('/users/{id}/role', [UserController::class, 'updateRole'])->middleware(VerifyTokenMiddleware::class);

    //  'question' => 'required|string',
    //         'image' => 'nullable|string',
    //         'options' => 'required|string',
    //         'choose' => 'required|array',
    //         'answer' => 'required|string',
    //         'point' => 'required|integer',
    // body của choose [{"q":"22"},{"q":"22"},{"q":"22"},{"q":"22"}]

    Route::post('/create-question', [QuestionController::class, 'createQuestion'])->middleware(VerifyTokenMiddleware::class);

    // id của question
    Route::delete('/delete-questions/{id}', [QuestionController::class, 'deleteQuestion'])->middleware(VerifyTokenMiddleware::class);

    //id
    // //  'question' => 'required|string',
    //         'image' => 'nullable|string',
    //         'options' => 'required|string',
    //         'choose' => 'required|array',
    //         'answer' => 'required|string',
    //         'point' => 'required|integer',
    // body của choose [{"q":"22"},{"q":"22"},{"q":"22"},{"q":"22"}]
    Route::put('/edit-questions/{id}', [QuestionController::class, 'updateQuestion'])->middleware(VerifyTokenMiddleware::class);
});
