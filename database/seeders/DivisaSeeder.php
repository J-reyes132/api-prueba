<?php

namespace Database\Seeders;

use App\Models\Divisa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Divisa::factory()->count(10)->create();
    }
}
