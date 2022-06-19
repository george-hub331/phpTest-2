<?php

namespace Database\Factories;

use App\Models\Articles;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\articles>
 */
class ArticlesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Articles::class;

    public function definition()
    {
        return [
            'subject' => $this->faker->name(),
            'body' => $this->faker->text(),
            'views'=> 0,
            'likes' => 0,
            'tags' => Str::random(5).','.Str::random(6).','.Str::random(5)
        ];
    }
}
