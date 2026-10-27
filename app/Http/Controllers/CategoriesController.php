<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'message' => 'categories',
            'results' => $categories
        ], 200);
    }
}
