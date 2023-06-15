<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends BaseController
{
    /**
     * List all posts
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::orderByDesc('updated_at')
            ->with('tags')
            ->paginate(15);

        return $this->successResponseWithResourceCollection(
            'Posts retrieved correctly',
            new PostCollection($posts)
        );
    }

    /**
     * Show a post by slug
     *
     * @return JsonResponse
     */
    public function show($slug): JsonResponse
    {
        try {
            $post = Post::where('slug', $slug)->firstOrFail();

            return $this->successResponse('Post retrieved correctly', new PostResource($post));
        } catch (\Throwable $e) {
            return $this->errorResponse('Post not found', 404);
        }
    }
}
