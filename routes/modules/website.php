<?php

use App\Actions\Website\ShowAboutPage;
use App\Actions\Website\ShowHomePage;
use App\Actions\Website\ShowNewsDetail;
use App\Actions\Website\ShowNewsList;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', ShowHomePage::class)->name('landing');
Route::get('/news', ShowNewsList::class)->name('news.index');
Route::get('/news/{slug}', ShowNewsDetail::class)->name('news.show');
Route::get('/about', ShowAboutPage::class)->name('about');
