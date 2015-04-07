<?php
use Uninett\Core\Seeders\Seeder;

class TblFileTableSeeder implements Seeder{
	public function run()
	{
		$faker = Faker\Factory::create();

		$db = new \Uninett\Database\EcampusSQLConnection2();

		foreach (range(1, 3) as $fileID)
		{
			$id = $fileID;
			foreach (range(1, 4) as $filePresentation_presId)
			{
				$file = (new \Uninett\Models\Ecampussql\TblFile())->withAttributes([
					'filePresentation_presId'  => $filePresentation_presId,
					'fileId'    => $id,
					'filePath' => $faker->text(10),
					'createdOn' => \Carbon\Carbon::now(),
				]);

				$db->insert($file);


				$xml = (new \Uninett\Core\XmlCreator())->createFile($file);
			}
		}
	}
}