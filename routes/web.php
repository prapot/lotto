<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\ProfileController;
use App\Http\Controllers\backend\UploadImageController;
use App\Http\Controllers\backend\HostController;
use App\Http\Controllers\backend\AgentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    abort('404');
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/register', function () {
    abort('404');
});

Route::prefix('backends')->name('backends.')->middleware(['auth','admin'])->group(function () {
    Route::get('/',function(){
      return view('backends.dashboard');
    });

    Route::post('/summernote/upload/file',[UploadImageController::class, 'upload']);
    Route::post('/temp/upload/file',[UploadImageController::class, 'uploadTemp']);
    Route::post('/temp/remove/file',[UploadImageController::class, 'removeTemp']);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'update'])->name('update');
        Route::post('/destroy', [AdminController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('agent')->name('agent.')->group(function () {
        Route::get('/create', [AgentController::class, 'create'])->name('create');
        Route::get('/', [AgentController::class, 'index'])->name('index');
        Route::post('/', [AgentController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AgentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AgentController::class, 'update'])->name('update');
        Route::post('/destroy', [AgentController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('host')->name('host.')->group(function () {
        Route::get('/', [HostController::class, 'host'])->name('host');
        Route::post('/', [HostController::class, 'store'])->name('store');
        Route::put('/update/status', [HostController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/destroy', [HostController::class, 'destroy'])->name('destroy');
        Route::post('/formula', [HostController::class, 'formula'])->name('formula');
        Route::post('/formula/destroy', [HostController::class, 'formulaDestroy'])->name('formula.destroy');
    });

    
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/{id}/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProfileController::class, 'update'])->name('update');
    });
});