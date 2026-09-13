<?php

use Illuminate\Support\Facades\Route;


Route::prefix('dts')->group(function () {
    // Core Dashboard Layout
    Route::view('/', 'app.dts.dashboard.index')->name('dts.dashboard');

    // Document Routing Dropdown Layer Sub-menus
    // Route::view('/documents', 'app.dts.documents')->name('documents.index');
    Route::view('/documents/received', 'app.dts.documents.received')->name('documents.received');
    Route::view('/documents/released', 'app.dts.documents.released')->name('documents.released');
    Route::view('/documents/deferred', 'app.dts.documents.deferred')->name('documents.deferred');

    // Core Sidebar Main Targets
    Route::view('/documents', 'app.dts.documents.index')->name('dts.documents.index');
    Route::view('/offices', 'app.dts.offices.index')->name('dts.offices.index');
    Route::view('/users', 'app.dts.users.index')->name('dts.users.index');
    Route::view('/logs', 'app.dts.logs.index')->name('dts.logs.index');
});
