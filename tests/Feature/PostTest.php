<?php

use function Pest\Laravel\{actingAs, get, json};

use App\Models\Post;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    User::factory()->create();
});


it('user not logged in cant create a post', function () {
    json('post', '/api/posts', [])
        ->assertJson(['message' => 'Unauthenticated.']);
});


it('user logged in can create a post', function () {
    actingAs(User::first())
        ->json('post', '/api/posts', [
            'title' => 'Post Title',
            'content' => 'Post content',
        ])
        ->assertJson(function (AssertableJson $json) {
            $json
                ->has('data')
                ->whereType('data', 'array')
                ->has('data.title')
                ->has('data.content');
        });
});


it('validates title and content on post create', function () {
    actingAs(User::first())
        ->json('post', '/api/posts', [
        ])
        ->assertJson([
            'errors' => [
                'title' => [
                    'The title field is required.'
                ],'content' => [
                    'The content field is required.'
                ]
            ],
        ]);
});

it('user not logged in cant update a post', function () {
    json('put', '/api/posts/1', [])
        ->assertJson(['message' => 'Unauthenticated.']);
});


it('user logged in can update a post', function () {
    Post::factory()->create();

    actingAs(User::first())
        ->json('put', '/api/posts/1', [
            'title' => 'Post Title',
            'content' => 'Post content',
        ])
        ->assertJson(function (AssertableJson $json) {
            $json
                ->has('data')
                ->whereType('data', 'array')
                ->has('data.title')
                ->has('data.content');
        });
});

it('validate cant update inexistent post', function () {
    actingAs(User::first())
        ->json('put', '/api/posts/1', [
            'title' => 'Post Title',
            'content' => 'Post content',
        ])
        ->assertJson(['error' => 'Post not found'])
        ->assertStatus(404);
});
