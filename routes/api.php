<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(CategoryProductController::class)->prefix("categories")->group(function () {
    Route::post("{categoryId}/products", "createProduct");
    Route::post("create-products-from-excel", "createProductsFromExcel");
    Route::get("empty", "getEmptyCategories");
    Route::get("less-than-hundren", "getLessThanHundredCategories");
    Route::get("more-than-hundren", "getMoreThanHundredCategories");
    Route::get("with-products", "getCategoriesWithProducts");
    Route::get("active-with-active-product  s", "getActiveCategoriesWithActiveProducts");
    Route::prefix("products")->group(function () {
        Route::post("update", "updateProduct");
        Route::get("active", "getActiveProducts");
        Route::get("inactive", "getInactiveProducts");
        Route::get("less-than-hundred", "getLessThanHundredProducts");
        Route::get("more-than-hundred", "getMoreThanHundredProducts");
        Route::get("{product}", "getProduct");
        Route::delete("{productId}", "deleteProduct");
        Route::put("{id}/activate", "activateProduct");
        Route::put("{id}/deactivate", "deactivateProduct");
    });
});

Route::controller(CategoryController::class)->prefix("categories")->group(function () {
    Route::post("", "create");
    Route::post("update", "update");
    Route::get("active", "getActiveCategories");
    Route::get("inactive", "getInactiveCategories");
    Route::get("{category}", "getCategory"); //{category} mean category id
    Route::delete("{id}", "delete");
    Route::put("{id}/activate", "activate");
    Route::put("{id}/deactivate", "deactivate");
});

Route::controller(AuthController::class)->prefix("auth")->group(function () {
    Route::post("login", "login");
    Route::post("logout", "logout");
    Route::get('forget-password/{user:email}', "forgetPassword");
    Route::put('reset-password', "resetPassword");
});

Route::controller(UserController::class)->prefix("users")->group(function () {
    Route::post("", "createUser");
    Route::post("update", "updateUser");
    Route::post("edit-profile", "editProfile");
    Route::get("{user}", "getUser");
    Route::delete("{user}", "deleteUser");
    Route::put("{id}/activate", "activateUser");
    Route::put("{id}/deactivate", "deactivateUser");
    Route::post("add-to-cart", "addToCart");
    Route::delete("{shoppingCart}/remove-from-cart", "deleteProductFromCart");
});
