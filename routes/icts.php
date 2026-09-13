<?php

use Illuminate\Support\Facades\Route;

Route::prefix('icts')->middleware('auth')->group(function () {
    Route::view('/', 'app.icts.index')->name('icts.index');
    Route::view('/myprofile', 'app.icts.profile.index')->name('icts.profile.index');
});
