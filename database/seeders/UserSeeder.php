<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'alvin@gmail.com'],
            [
                'name' => 'Alvin',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123')
            ]
        );

        // Assign existing data to Alvin
        \App\Models\Category::whereNull('user_id')->update(['user_id' => $user->id]);
        \App\Models\Transaction::withTrashed()->whereNull('user_id')->update(['user_id' => $user->id]);
        \App\Models\DreamPlanning::whereNull('user_id')->update(['user_id' => $user->id]);
    }
}
