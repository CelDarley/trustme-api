<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\SealController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/plans', [PlanController::class, 'index']);
Route::get('/plans/{plan}', [PlanController::class, 'show']);
Route::get('/testimonials', [TestimonialController::class, 'index']);
Route::get('/testimonials/{testimonial}', [TestimonialController::class, 'show']);
Route::get('/faqs', [FaqController::class, 'index']);
Route::get('/faqs/{faq}', [FaqController::class, 'show']);
Route::post('/contact', [ContactController::class, 'store']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Subscription routes
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show']);
    Route::patch('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel']);
    
    // Contract routes
    Route::apiResource('contracts', ContractController::class);
    
    // Seal routes
    Route::apiResource('seals', SealController::class)->except(['update']);
    
    // Payment routes
    Route::post('/payment/preference', [PaymentController::class, 'createPreference']);
    Route::post('/payment/process', [PaymentController::class, 'processPayment']);
});
