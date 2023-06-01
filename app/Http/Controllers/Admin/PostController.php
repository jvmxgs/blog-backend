<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Throwable;

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
        $post = Post::where('slug', $slug)->first();

        return $this->successResponse('Post retrieved correctly', new PostResource($post));
    }

    /**
     * Show a post by slug
     *
     * @return JsonResponse
     */
    public function edit($postId): JsonResponse
    {
        $post = Post::where('id', $postId)
            ->with('tags')
            ->first();

        return $this->successResponse('Post retrieved correctly', new PostResource($post));
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

        return $this->successResponse('Post created successfully', new PostResource($post));
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
        try {
            $post = PostService::update($postId, $request->validated());

            return $this->successResponse('Post updated successfully', new PostResource($post));
        } catch (Throwable $e) {
            return $this->errorResponse('Error updating post', 404);
        }
    }

    /**
     * Delete a post
     *
     * @param int $postId
     *
     * @return JsonResponse
     */
    public function destroy($postId): JsonResponse
    {
        try {
            Post::find($postId)->delete();

            return $this->successResponse('Post deleted successfully');
        } catch (Throwable $e) {
            return $this->errorResponse('Error deleting post', 404);
        }
    }
}
