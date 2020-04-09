<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;

final class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        if (User::query()->where('email', 'info@kingscode.nl')->doesntExist()) {
            factory(User::class)->create([
                'email' => 'info@kingscode.nl',
            ]);
        }
    }
}
