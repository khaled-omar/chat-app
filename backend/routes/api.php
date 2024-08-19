<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\BootController;
use App\Http\Controllers\Api\V1\ContactRequestController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\OTPController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {

    // Authentication routes start here
    Route::name('auth.')->prefix('auth')->group(function () {
        Route::post('register', [RegistrationController::class, 'register'])->name('user.register');
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('refresh_token');
        // Forget and reset password routes.
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkForgotPassword'])->name('forgot-password');
        Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password');

        // Protected authentication routes start here
        Route::middleware('auth:api')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('me', [AuthController::class, 'me'])->name('me');
            Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        });
    });

    // protected routes group start here
    Route::middleware('auth:api')->group(function () {
        // Notifications endpoints.
        Route::patch('notifications/{notification}/mark-as-unread', [NotificationController::class, 'markAsUnRead'])->name('notifications.mark-as-unread')->middleware('authorize:notification');
        Route::patch('notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read')->middleware('authorize:notification');
        Route::apiResource('notifications', NotificationController::class)->only(['index', 'destroy']);
        // Logged-in user can send contact request to other companies.
        Route::post('users/{user}/contact-requests', [ContactRequestController::class, 'store'])->name('contact-requests.store');
        // users routes group start here
        Route::middleware('authorize:user')->group(function () {
            Route::apiResource('users/{user}/contact-requests', ContactRequestController::class)->except(['store'])->middleware('authorize:contact-request');
            Route::patch('users/{user}/contact-requests/{contact_request}/mark-as-read', [ContactRequestController::class, 'markAsRead'])->name('contact-requests.mark-as-read')->middleware('authorize:contact-request');
            Route::apiResource('users/{user}/contact-requests/{contact_request}/messages', MessageController::class)->only(['index', 'store'])->middleware('authorize:contact-request');
        });

    });

    // Public routes start here
    Route::post('otp', [OTPController::class, 'send']);
    Route::get('boot', [BootController::class, 'index'])->name('boot');
    Route::apiResource('countries', CountryController::class)->only(['index', 'show']);
});
