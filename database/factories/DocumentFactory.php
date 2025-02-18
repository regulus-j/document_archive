<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Document::class;

    public function definition()
    {
        return [
            'uploader' => User::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'remarks' => $this->faker->optional()->sentence,
            'content' => $this->faker->optional()->text,
            'path' => 'documents/'.$this->faker->uuid.'.pdf',
            'deleted_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
