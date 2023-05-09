<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public static function firstOrCreateMany($tags)
    {
        return collect($tags)->map(function ($tag) {
            return Tag::firstOrCreate(['name' => $tag])->id;
        });
    }
}
