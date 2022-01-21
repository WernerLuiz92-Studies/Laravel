<?php

use App\Jobs\MakeOrder;
use App\Jobs\RunPayment;
use App\Jobs\ValidateCard;
use App\Jobs\SendNotificationsJob;
use Illuminate\Bus\BatchRepository;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function (BatchRepository $batchRepository) {
    return view('welcome', [
        'batches' => $batchRepository->get(),
    ]);
})->name('home');

Route::get('/notify-all-users', function () {
    SendNotificationsJob::dispatch();

    return redirect()->route('home');
})->name('notify-all-users');

Route::get('/make-payment', function () {
    Bus::batch([
        new MakeOrder(),
        new ValidateCard(),
        new RunPayment(),
    ])
    ->name('make-payment'. rand(1, 10))
    ->dispatch();

    return redirect()->route('home');
})->name('run-payment-batch');