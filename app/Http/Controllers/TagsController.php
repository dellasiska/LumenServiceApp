<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'message' => 'tags',
            'results' => $tags
        ], 200);
    }
}
