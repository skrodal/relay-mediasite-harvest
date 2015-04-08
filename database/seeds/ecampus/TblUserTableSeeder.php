<?php
use Uninett\Core\Seeders\Seeder;
use Uninett\Database\DatabaseConnection;

/**
 * Class TblUserTableSeeder
 */
class TblUserTableSeeder implements Seeder{

	/**
	 * @var DatabaseConnection
	 */
	private $db;

	/**
	 * @param $db
	 */
	function __construct(DatabaseConnection $db)
	{
		$this->db = $db;
	}


	/**
	 * Run the seed
	 */
	public function run()
	{
		$faker = Faker\Factory::create();

		foreach (range(1, 5) as $index)
		{
			$email =  $faker->safeEmail;

			$emailArray = explode('@', $email);

			$user = (new \Uninett\Models\Ecampussql\TblUser())->withAttributes([
				'userId'          => $index,
				'userName'        => $email,
				'userEmail'       => $email,
				'userDisplayName' => $emailArray[0],
				'createdOn'       => \Carbon\Carbon::now(),
			]);

			$this->db->insert($user);
		}
	}
}