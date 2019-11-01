<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;

final class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::query()->where('email', 'info@kingscode.nl')->doesntExist()) {
            factory(User::class)->create([
                'email'    => 'info@kingscode.nl',
                'password' => bcrypt('secret'),
            ]);
        }
    }
}
