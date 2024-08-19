<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;

class PassportClientSeeder extends Seeder
{
    public function __construct(protected ClientRepository $clientRepository) {}

    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Retrieve predefined client ID and secret from config
        $clientId = config('passport-clients.client_id');
        $clientSecret = config('passport-clients.client_secret');
        $provider = config('passport-clients.provider');
        $clientName = config('app.name').' Password Grant Client';

        // Check if the client already exists
        $client = $this->clientRepository->findActive($clientId);
        if ($client) {
            $this->command->info('Password grant client with predefined UUID already exists.');

            return;
        }

        $client = $this->clientRepository->createPasswordGrantClient(null, $clientName, config('app.url'), $provider);

        // Update the client secret to the predefined secret
        $client->update(['secret' => $clientSecret, 'id' => $clientId]);

        $this->command->info('Password grant client with predefined UUID has been inserted.');
    }
}
