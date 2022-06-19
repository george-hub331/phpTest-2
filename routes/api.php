<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\articleController;

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


Route::get('/articles', [articleController::class, 'index'])->name('list.articles');

Route::get('/articles/{id}', [articleController::class, 'viewArticle'])->name('articles.view');

Route::post('/articles/{id}/comment', [articleController::class, 'comment'])->name('articles.comment');

Route::get('/articles/{id}/view', [articleController::class, 'views'])->name('articles.views');

Route::get('/articles/{id}/like', [articleController::class, 'views'])->name('articles.views');





