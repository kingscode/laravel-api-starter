<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name'           => $this->faker->name,
            'email'          => "{$this->faker->unique()->word}+las@kingscode.nl",
            'password'       => '$2y$10$OwXT/21pAv5OSIXyG1caxexYkL0uDJQSadZ4f46Y5AjmfSJtdkphu',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model|\App\Models\User
     */
    public function createOne($attributes = [])
    {
        return parent::createOne($attributes);
    }
}
