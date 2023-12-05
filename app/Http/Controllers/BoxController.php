<?php
namespace App\Http\Controllers;



use App\Models\Box;
use App\Models\Recipe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BoxController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'delivery_date' => 'required|date|after_or_equal:tomorrow', // Delivery date should be in the future
            'recipe_ids' => 'required|array|max:4', // Array of recipe IDs, maximum of 4
            'recipe_ids.*' => 'exists:recipes,id', // Ensure each recipe ID exists in the recipes table
        ]);

        // Check if delivery date is not within the next 48 hours
        $deliveryDate = Carbon::parse($validatedData['delivery_date']);
        $next48Hours = Carbon::now()->addHours(48);
        if ($deliveryDate->lessThanOrEqualTo($next48Hours)) {
            return response()->json(['error' => 'Delivery date should be at least 48 hours from now.'], 400);
        }

        // Retrieve the recipes associated with the provided recipe IDs
        $recipes = Recipe::whereIn('id', $validatedData['recipe_ids'])->get();

        // Create the box
        $box = Box::create([
            'delivery_date' => $deliveryDate,
            
        ]);

        // Attach recipes to the box
        $box->recipes()->attach($recipes);

        return response()->json(['box' => $box], 201);
    }


    public function index(Request $request)
    {
        $perPage = $request->has('per_page') ? (int)$request->per_page : 10; // Number of items per page
        $boxes = Box::paginate($perPage);

        return response()->json($boxes, 200);
    }
}
