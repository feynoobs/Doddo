<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BoardListContoller;
use App\Http\Controllers\Api\ThreadListController;
use App\Http\Controllers\Api\ResponseListController;
use App\Http\Controllers\Api\PostController;

Route::group(['as' => 'api.', 'middleware' => ['api']], function() {
    Route::post('/boards', BoardListContoller::class)->name('boards');
    Route::post('/threads', ThreadListController::class)->name('threads');
    Route::post('/responses', ResponseListController::class)->name('responses');
    Route::post('/post', PostController::class)->name('post');
});
