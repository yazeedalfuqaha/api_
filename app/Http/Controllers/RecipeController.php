<?php



namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;


class RecipeController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'ingredients' => 'required|array',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.amount' => 'required|numeric|min:0',
        ]);

        $recipe = Recipe::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        $recipe->ingredients()->attach($request->input('ingredients'));

        return response()->json(['recipe' => $recipe], 201);
    }

    
    


    public function show($id)
    {
        $recipe = Recipe::with('ingredients')->find($id);

        return view('recipes.show', compact('recipe'));
    }

    

    public function index(Request $request)
    {
        $perPage = $request->has('per_page') ? (int)$request->per_page : 10; // Number of items per page
        $recipes = Recipe::paginate($perPage);

        return response()->json($recipes, 200);
    }


}
