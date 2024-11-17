<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Lecture;
use App\Models\Subject;

class LectureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lecture::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'link_video' => $this->faker->word(),
            'link_pdf' => $this->faker->word(),
            'pdf_password' => $this->faker->word(),
            'is_unlocked' => $this->faker->boolean(),
            'status' => $this->faker->word(),
            'subject_id' => Subject::factory(),
        ];
    }
}
