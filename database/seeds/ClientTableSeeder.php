<?php

use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = ['Ukraine', 'Russia', 'Poland'];
        $country_codes = ['UA', 'RU', 'PL'];
        $cities = [['Kiev', 'Dnepr', 'Odessa'], ['Moskow', 'Penza'], ['Warsaw']];
        $city_center_coordinates = [[[50.49, 30.50], [48.48, 35.03], [46.67, 30.79]], [[55.83, 37.63], [53.24, 45.00]], [[52.25, 21.02]]];

        foreach($countries as $index=>$country){
            $Country = new App\Country();
            $Country->name = $country;
            $Country->code = $country_codes[$index];
            $Country->save();

            foreach($cities[$index] as $city_index=>$city){
                $City = new App\City();
                $City->name = $city;
                $City->country_id = $Country->id;
                $City->latitude = $city_center_coordinates[$index][$city_index][0];
                $City->longitude = $city_center_coordinates[$index][$city_index][1];
                $City->save();
            }
        }

        factory(App\Client::class, 20)->create()->each(function ($client){
            $clientCity = App\City::find($client->city_id);

            $count_coordinates = rand(6, 10);
            for($i=1; $i<=$count_coordinates; $i++) {
                $client->coordinates()->save(factory(App\ClientCoordinate::class)->create([
                    'latitude' => round($clientCity->latitude + (rand(50, 150) / 1000), 4),
                    'longitude' => round($clientCity->longitude + (rand(50, 150) / 1000), 4),
                    'client_id' => $client->id
                ]));
            }
        });
    }
}
