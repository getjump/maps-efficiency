<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$n = 99999;
    	$batchSize = 1000;

    	$data = [];

    	$this->command->getOutput()->progressStart($n);

    	for ($it = 0, $batch = 0; $it < $n; $it ++, $batch++) {
    		if ($batch >= $batchSize) {
    			DB::insert('INSERT INTO points (geom) VALUES ' . rtrim(str_repeat('(ST_SetSRID(ST_MakePoint(?, ?), 4326)),', $batchSize), ',') . ';', $data);

    			unset($data);
    			$data = [];

    			$batch = 0;

    			$this->command->getOutput()->progressAdvance($batchSize);
    		}

    		$scope = random_int(10, 1000);

    		$latitude = random_int(-1800, 1800) / $scope;
    		$longitude = random_int(-1800, 1800) / $scope;

    		$data[] = $longitude;
    		$data[] = $latitude;
    	}

    	$this->command->getOutput()->progressFinish();
    }
}
