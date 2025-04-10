<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\CheckIsLogged;
use App\Http\Middleware\CheckIsNotLogged;
use Illuminate\Support\Facades\Route;



// Auth Routes - User not Logged
Route::middleware([CheckIsNotLogged::class])->group(function(){
    Route::get('/login',[AuthController::class,'login']);
    Route::post('/loginSubmit', [AuthController::class,'loginSubmit']);
});

//App rotes - User Logged
Route::middleware([CheckIsLogged::class])->group(function(){
    Route::get('/',[MainController::class,'index'])->name('home');
    Route::get('/newNote',[MainController::class,'newNote'])->name('new');
    Route::post('/newNoteSubmit',[MainController::class,'newNoteSubmit'])->name('newNoteSubmit');

    //Edit note
    Route::get('/editNote/{id}',[MainController::class,'editNote'])->name('edit');
    Route::post('/editNoteSubmit',[MainController::class,'editNoteSubmit'])->name('editNoteSubmit');

    //Deleted note
    Route::get('/deletedNote/{id}',[MainController::class,'deletedNote'])->name('delete');
    Route::get('/deletedNoteConfirm/{id}',[MainController::class,'deletedNoteConfirm'])->name('deleteConfirm');


    Route::get('/logout',[AuthController::class,'logout'])->name('logout');

});

