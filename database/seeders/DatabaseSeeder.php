<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Transaction;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
        ]);

        Transaction::create([
            'trans_date' => Carbon::parse('2026-03-01'),
            'desc' => 'Gaji Bulanan',
            'amount' => 500000,
            'category_id' => 1
        ]);
        Transaction::create([
            'trans_date' => Carbon::parse('2026-03-02'),
            'desc' => 'Donasi',
            'amount' => 11000,
            'category_id' => 2
        ]);
        Transaction::create([
            'trans_date' => Carbon::parse('2026-03-03'),
            'desc' => 'Streaming',
            'amount' => 550000,
            'category_id' => 3
        ]);
    }
}
