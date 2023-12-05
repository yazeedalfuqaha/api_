<?php

namespace App\Models;
use App\Models\Recipe;
use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('amount');
    }
}
