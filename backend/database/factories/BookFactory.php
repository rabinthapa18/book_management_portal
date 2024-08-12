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
        $type = $this->faker->randomElement(['fiction', 'non-fiction']);
        $title = $this->faker->sentence(3);
        $author = $this->faker->name;
        return [
            'title' => $title,
            'author' => $author,
            'genre' => $type,
        ];
    }
}
