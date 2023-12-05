<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\BoxController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/ingredients', [IngredientController::class, 'create']);
Route::get('/ingredients', [IngredientController::class, 'index']);





Route::get('/recipes', [RecipeController::class, 'index']);
Route::post('/recipes', [RecipeController::class, 'create']);


Route::get('/boxes', [BoxController::class, 'index']); 
Route::post('/boxes', [BoxController::class, 'create']);



Route::get('/ingredients-order', [IngredientController::class, 'getIngredientsOrder']);
