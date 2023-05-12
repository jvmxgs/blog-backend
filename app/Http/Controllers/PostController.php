<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * List all posts
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::all();

        return response()->json([
            'data' => $posts,
        ]);
    }

    /**
     * Show a post by slug
     *
     * @return JsonResponse
     */
    public function show($slug): JsonResponse
    {
        $post = Post::where('slug', $slug)->first();

        return response()->json([
            'data' => $post,
        ]);
    }

    /**
     * Create a new post
     *
     * @param StorePostRequest $request
     *
     * @return JsonResponse
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = PostService::create($request->validated());

        return response()->json([
            'data' => $post,
        ], 201);
    }

    /**
     * Update a post
     *
     * @param UpdatePostRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request, $postId): JsonResponse
    {
        $post = PostService::update($postId, $request->validated());

        if (isset($post['error'])) {
            return response()->json($post, 404);
        }

        return response()->json([
            'data' => $post,
        ], 200);
    }
}
