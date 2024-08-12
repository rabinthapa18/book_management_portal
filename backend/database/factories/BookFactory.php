<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genres = $this->faker->randomElements(['fiction', 'non-fiction', 'biography'], rand(1, 3));
        $title = $this->faker->sentence(3);
        $author = $this->faker->name;
        return [
            'title' => $title,
            'author' => $author,
            'genre' => json_encode($genres),
        ];
    }
}
