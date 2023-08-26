<?php

namespace Database\Seeders;

use App\Models\User;
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement("SET foreign_key_checks=0");
        ShortURL::truncate();
        ShortURLVisit::truncate();
        User::truncate();
        DB::statement("SET foreign_key_checks=1");

        User::factory()
            ->count(10)
            ->create();

        User::factory()->create([
            "name" => "Test User",
            "email" => "test@example.com"
        ]);

        User::all()->each(function (User $user) {
            $this->createShortURLs($user, rand(10, 20));
        });

    }

    /**
     * Create short URLs for a user
     *
     * @param User $user The user for whom to create the short URLs
     * @param int $count The number of short URLs to create
     *
     * @return void
     */
    private function createShortURLs(User $user, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            ShortURL::factory()
                ->has(ShortURLVisit::factory()->count(rand(0, 30)), "visits")
                ->create([
                    "user_id" => $user->id,
                ]);
        }
    }
}
