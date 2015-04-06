<?php 
class TblUsersTableSeeder  implements Seeder{

	public function run(){

		$faker = Faker\Factory::create();

		$db = new \Uninett\Database\EcampusSQLConnection();


		foreach (range(0, 12) as $index) {

			$userName = $faker->userName;

			$user = (new \Uninett\Models\Ecampussql\TblUser())->setAttributes([
				'userName' =>  $userName,
				'userEmail' => $userName . $faker->safeEmail(),
				'userDisplayName' => $userName,
				'createdOn' => \Carbon\Carbon::now(),
			]);

			$db->create([

				'userName' =>  $userName,
				'userEmail' => $userName . $faker->safeEmail(),
				'userDisplayName' => $userName,
				'createdOn' => \Carbon\Carbon::now(),
			]);
		}
	}
}