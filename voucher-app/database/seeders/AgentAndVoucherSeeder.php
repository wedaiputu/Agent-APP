<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;
use App\Models\Voucher;

class AgentAndVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create 5 agents
        $agents = Agent::factory()->count(5)->create();

        // Loop through each agent to create vouchers
        foreach ($agents as $agent) {
            // Create 10 vouchers for each agent
            Voucher::factory()->count(10)->create([
                'agent_id' => $agent->id,
            ]);
        }
    }
}
