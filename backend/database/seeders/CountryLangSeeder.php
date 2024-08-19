<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\CountryLang;
use Illuminate\Database\Seeder;

class CountryLangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::query()->chunk(20, function ($chunk) {
            $chunk->each(function ($item) {
                CountryLang::query()->create(
                    [
                        'lang_code' => 'en',
                        'name' => $item->name,
                        'country_id' => $item->id,
                    ]
                );
            });
        });
    }
}
