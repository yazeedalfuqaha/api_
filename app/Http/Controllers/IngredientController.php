<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Order;

use Illuminate\Support\Carbon;

class IngredientController extends BaseController
{
    public function index()
    {
        $ingredients = Ingredient::all();
        return response()->json(['ingredients' => $ingredients], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'measure' => 'required|string',
            'supplier' => 'required|string',
        ]);

        $ingredient = Ingredient::create([
            'name' => $request->input('name'),
            'measure' => $request->input('measure'),
            'supplier' => $request->input('supplier'),
        ]);

        return response()->json(['ingredient' => $ingredient], 201);
    }


    public function getIngredientsOrder(Request $request)
    {
        $validatedData = $request->validate([
            'order_date' => 'required|date', // Validate order_date input
        ]);

        $orderDate = Carbon::parse($validatedData['order_date']);
        $endDate = $orderDate->copy()->addDays(7);

        // Retrieve orders within the specified date range
        $orders = Order::whereBetween('order_date', [$orderDate, $endDate])->get();

        // Extract and aggregate ingredients and their quantities from the orders
        $ingredients = [];
        foreach ($orders as $order) {
            foreach ($order->recipes as $recipe) {
                foreach ($recipe->ingredients as $ingredient) {
                    $ingredientId = $ingredient->id;
                    $ingredientName = $ingredient->name;
                    $ingredientQuantity = $ingredient->pivot->quantity;

                    // Aggregate ingredient quantities for ordering
                    if (!isset($ingredients[$ingredientId])) {
                        $ingredients[$ingredientId] = [
                            'name' => $ingredientName,
                            'total_quantity' => $ingredientQuantity,
                        ];
                    } else {
                        $ingredients[$ingredientId]['total_quantity'] += $ingredientQuantity;
                    }
                }
            }
        }

        return response()->json(['ingredients' => array_values($ingredients)], 200);
    }
}

