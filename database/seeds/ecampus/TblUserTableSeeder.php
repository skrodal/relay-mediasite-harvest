<?php
use Uninett\Core\Seeders\Seeder;

class TblUserTableSeeder implements Seeder{
	public function run()
	{
		$faker = Faker\Factory::create();

		$db = new \Uninett\Database\EcampusSQLConnection2();

		foreach (range(1, 5) as $index)
		{
			$userName = $faker->userName . $index;

			$email =  $faker->safeEmail;

			$emailArray = explode('@', $email);

			$user = (new \Uninett\Models\Ecampussql\TblUser())->withAttributes([
				'userId'          => $index,
				'userName'        => $email,
				'userEmail'       => $email,
				'userDisplayName' => $emailArray[0],
				'createdOn'       => \Carbon\Carbon::now(),
			]);

			$db->insert($user);
		}
	}
}