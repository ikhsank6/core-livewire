<?php

use App\Livewire\Settings\SystemSettingIndex;
use Illuminate\Support\Facades\Route;

Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/system', SystemSettingIndex::class)->name('system.index');
});
