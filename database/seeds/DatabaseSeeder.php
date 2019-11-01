<?php

declare(strict_types=1);

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Seeder;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function run()
    {
        if (Client::query()->doesntExist()) {
            /** @var ClientRepository $clientRepository */
            $clientRepository = $this->container->make(ClientRepository::class);

            /** @var \Illuminate\Contracts\Config\Repository $config */
            $config = $this->container->make(Repository::class);

            $client = $clientRepository->createPasswordGrantClient(
                null, $config->get('app.name') . ' Password Grant Client', 'http://localhost'
            );

            // We'll update the client to always have the same secret.
            $client->update(['secret' => '384WFKbLe729R2fw7ZRvK1DuGVwpAhct774DYkTI']);
        }

        $this->call(UsersTableSeeder::class);
    }
}
