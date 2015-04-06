<?php
use Uninett\Core\Seeders\Seeder;

class TblFileTableSeeder implements Seeder{
	public function run()
	{
		$faker = Faker\Factory::create();

		$db = new \Uninett\Database\EcampusSQLConnection2();

		foreach (range(0, 3) as $fileID)
		{
			foreach (range(0, 4) as $filePresentation_presId)
			{
				$file = (new \Uninett\Models\Ecampussql\TblFile())->withAttributes([
					'filePresentation_presId'  => $filePresentation_presId,
					'fileId'        => $fileID,
					'filePath'       => 'Jadda',
					'createdOn'       => \Carbon\Carbon::now(),
				]);

				$db->insert($file);
			}
		}

	}
}