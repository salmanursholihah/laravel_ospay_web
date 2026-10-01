<?php

use App\Http\Controllers\Api\CallbackController;
use App\Http\Controllers\Api\IakCallbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CALLBACK (PUBLIK)
|--------------------------------------------------------------------------
|
| Dipanggil server eksternal (Midtrans & IAK), bukan browser, jadi:
| - tidak boleh pakai auth / session / CSRF
| - ada di api.php (grup "api" tidak memakai VerifyCsrfToken)
|
| URL yang didaftarkan di dashboard:
|   POST {APP_URL}/api/callback/midtrans
|   POST {APP_URL}/api/callback/iak
|
*/
Route::post('/callback/midtrans', [CallbackController::class, 'midtrans']);

Route::post('/callback/iak', IakCallbackController::class);
