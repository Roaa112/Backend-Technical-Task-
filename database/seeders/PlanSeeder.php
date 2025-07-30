<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
      $plans = [
        ['name' => 'Basic', 'price' => 9.99, 'currency' => 'USD', 'interval' => 'monthly'],
        ['name' => 'Pro', 'price' => 19.99, 'currency' => 'USD', 'interval' => 'monthly'],
    ];

    foreach ($plans as $plan) {
        Plan::create($plan);
    }
    }
}
