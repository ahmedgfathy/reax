<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityLogsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('activity_logs')->insert([
            [
                'user_id' => 1,
                'action' => 'created',
                'entity' => 'property',
                'entity_type' => 'App\Models\Property',
                'entity_id' => 1,
                'timestamp' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 2,
                'action' => 'updated',
                'entity' => 'property',
                'entity_type' => 'App\Models\Property',
                'entity_id' => 2,
                'timestamp' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
