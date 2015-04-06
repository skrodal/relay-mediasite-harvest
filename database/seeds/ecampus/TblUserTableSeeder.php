<?php
use Uninett\Core\Seeders\Seeder;

class TblUserTableSeeder implements Seeder{
	public function run()
	{
		$faker = Faker\Factory::create();

		$db = new \Uninett\Database\EcampusSQLConnection2();

		foreach (range(0, 12) as $index)
		{
			$userName = $faker->userName . $index;

			$user = (new \Uninett\Models\Ecampussql\TblUser())->withAttributes([
				'userId'          => $index,
				'userName'        => $userName,
				'userEmail'       => $userName . $faker->safeEmail(),
				'userDisplayName' => $userName,
				'createdOn'       => \Carbon\Carbon::now(),
			]);

			$db->insert($user);
		}
	}
}