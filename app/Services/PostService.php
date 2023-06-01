<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PostService
{
    private static $slugRetry = 1;

    public static function create($data): Post
    {
        $data['slug'] = static::createUniqueSlug($data['title']);

        $post = Post::create($data);

        if (! empty($data['tags'])) {
            $tags = self::firstOrCreateTags($data['tags']);

            $post->tags()->attach($tags);
        }

        return $post;
    }

    public static function update($postId, $data): Post
    {
        $post = Post::find($postId);

        if (!$post) {
            throw new \Exception('Post not found');
        }

        $post->update($data);

        if (! empty($data['tags'])) {
            $tags = self::firstOrCreateTags($data['tags']);

            $post->tags()->sync($tags);
        }

        return $post;
    }

    private static function createUniqueSlug($title): string
    {
        $slug = Str::slug($title);

        if (static::$slugRetry > 1) {
            $slug = $slug . '-' . static::$slugRetry;
        }

        if (static::slugExists($slug)) {
            static::$slugRetry++;
            return static::createUniqueSlug($title);
        }

        return $slug;
    }

    private static function slugExists(string $slug)
    {
        $countPosts = Post::query()
            ->where('slug', $slug)
            ->withTrashed()
            ->count();

        return $countPosts > 0;
    }

    private static function firstOrCreateTags(string $tags)
    {
        $tags = array_map('trim', explode(',', $tags));

        return TagService::firstOrCreateMany($tags);
    }
}
