<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'message' => 'comments',
            'results' => $comments
        ], 200);
    }
}
