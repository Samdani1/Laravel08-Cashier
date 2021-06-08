<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::get('/', function (Request $request) {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/subscribe', function(){
    return view('subscribe', [
        'intent'=> auth()->user()->createSetupIntent(),
    ]);
})->name('subscribe')->middleware('auth');

Route::post('/subscribepost', function(Request $request){
    auth()->user()->newSubscription('cashier', $request->plan)->create($request->paymentMethod);
    return redirect('home');
})->name('subscribe')->middleware('auth');
