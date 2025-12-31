<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MembershipLevelStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $data = [];

        foreach ([1, 2, 3, 4, 5, 6, 7] as $levelId) {
            $data[] = [
                'membership_level_id' => $levelId,
                'membership_status_id' => 18,
                'video_id' => null,
                'video_title' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('membership_level_statuses')->insert($data);
    }
}
