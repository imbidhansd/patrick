<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CompanyMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $faker = Faker::create();
        $status_arr = ['info', 'warning', 'danger', 'success'];


        $companies = \App\Models\Company::all();

        foreach ($companies as $company_item) {

            $fake_range = range(20, 30);

            foreach ($fake_range as $i) {

                \App\Models\CompanyMessage::create([
                    'company_id' => $company_item->id,
                    'message_type' => $status_arr[rand(0, 3)],
                    'title' => $faker->sentence,
                    'content' => $faker->paragraph,
                    'link' => $faker->url,
                ]);
            }
        }
    }
}
