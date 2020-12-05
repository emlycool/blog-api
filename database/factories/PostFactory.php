<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $name = 'lorem ipsum',
            'description' => 'lorem ipsum',
            'content' => 'lorem ipsum',
            'images' => [1, 3],
            'user_id' => 1,
            'slug' => Str::slug($name),
            'category_id' => 1,
        ];
    }
}
