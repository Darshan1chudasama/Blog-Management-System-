<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
})->name('login');

Route::get('/addBlog', [BlogController::class, 'addBlog'])->name('addBlog');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');

Route::get('/editBlog/{id}', function ($id) {
    return view('editBlog'); // Blade file for edit blog page
});
