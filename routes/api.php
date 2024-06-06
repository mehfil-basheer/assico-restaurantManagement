<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TableController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/**
 * @OA\Info(
 *      title="Restaurant Management System API",
 *      version="1.0.0",
 *      description="API documentation for Restaurant Management System",
 *      @OA\Contact(
 *          email="info@example.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 */

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

/**
 * Authentication Endpoints
 */
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

/**
 * Logout Endpoint
 */
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

/**
 * Get Authenticated User Endpoint
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Routes for Admins
 */
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('tables', TableController::class);
    Route::apiResource('menus', MenuController::class);
    Route::apiResource('orders', OrderController::class);
    Route::post('changeOrderStatus', [OrderController::class, 'changeOrderStatus']);
});

/**
 * Routes for Admins and Staff
 */
Route::middleware(['auth:sanctum', 'role:admin|staff'])->group(function () {
    Route::apiResource('reservations', ReservationController::class);
    Route::get('reservations', [ReservationController::class, 'index']);
});

/**
 * Routes for Customers
 */
Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::put('reservations/{reservation}', [ReservationController::class, 'update']);
    Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy']);
    Route::get('menus', [MenuController::class, 'index']);
});


/**
 * Routes for Menu
 */
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::apiResource('menus', MenuController::class);
});

/**
 * Routes for customerand staff
 */
Route::middleware(['auth:api', 'role:customer|admin'])->group(function () {
    Route::get('menus', [MenuController::class, 'index']);
});

/**
 * Routes for admin staff
 */
Route::middleware(['auth:api', 'role:admin|staff'])->group(function () {
    Route::apiResource('orders', OrderController::class);
    Route::post('changeOrderStatus', [OrderController::class, 'changeOrderStatus']);
    Route::get('reservations', [ReservationController::class, 'index']);
});

