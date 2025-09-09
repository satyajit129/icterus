<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FacebookCredential;

class FacebookCredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default Facebook Credentials record if it doesn't exist
        FacebookCredential::firstOrCreate(
            ['id' => 1],
            [
                'app_id' => '',
                'app_secret' => '',
                'agency_id' => '',
                'user_access_token' => '',
                'api_url' => 'https://graph.facebook.com/v23.0/',
                'version' => 'v23.0',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
