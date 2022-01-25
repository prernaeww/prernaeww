<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// Route::get('/register', [RegisteredUserController::class, 'create'])
//                 ->middleware('guest')
//                 ->name('register');

// Route::post('/register', [RegisteredUserController::class, 'store'])
//                 ->middleware('guest');

Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])
                // ->middleware('guest')
                ->name('login');

Route::post('admin/login', [AuthenticatedSessionController::class, 'store']);
                // ->middleware('guest');

Route::get('board/login', [AuthenticatedSessionController::class, 'board_create'])
                // ->middleware('guest')
                ->name('board.login');

Route::post('board/login', [AuthenticatedSessionController::class, 'board_store']);
                // ->middleware('guest');

Route::get('store/login', [AuthenticatedSessionController::class, 'store_create'])
                // ->middleware('guest')
                ->name('store.login');

Route::post('store/login', [AuthenticatedSessionController::class, 'store_store']);
                // ->middleware('guest');

Route::get('login', [AuthenticatedSessionController::class, 'customer_create'])
                // ->middleware('guest')
                ->name('customer.login');

Route::post('login', [AuthenticatedSessionController::class, 'customer_store']);
                // ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'customer'])
                ->middleware('guest')
                ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth')
                ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])
                // ->middleware(['guest', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/password/set/{id}', [VerifyEmailController::class, 'set'])
                ->name('password.set');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('auth')
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');

Route::post('/board/logout', [AuthenticatedSessionController::class, 'board_destroy'])
                ->middleware('auth')
                ->name('board.logout');

Route::post('/store/logout', [AuthenticatedSessionController::class, 'store_destroy'])
                ->middleware('auth')
                ->name('store.logout');

Route::post('/customer/logout', [AuthenticatedSessionController::class, 'customer_destroy'])
->middleware('auth')
->name('customer.logout');
