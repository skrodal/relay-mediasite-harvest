<?php
use Uninett\Core\Seeders\Seeder;
use Uninett\Core\XmlPresentation;

class TblFileTableSeeder implements Seeder{
	public function run()
	{
		$faker = Faker\Factory::create();

		$db = new \Uninett\Database\EcampusSQLConnection2();

		$files = [];
		foreach (range(1, 1) as $filePresentation_presId)
		{
			$presentationName = substr(md5(rand()), 0, 7);

			foreach (range(1, 4) as $fileID)
			{
				$file = [
					'filePresentation_presId'  => $filePresentation_presId,
					'fileId'    => $fileID,
					'filePath' => getenv('APP_PATH') . '/storage/xml/' . 'ansatt/04.04/' . substr(md5(rand()), 0, 7) . '/' . $presentationName . '_' . $fileID .'.xml',
					'createdOn' => \Carbon\Carbon::now(),
				];

				$files[] = $file;
				$db->insert((new \Uninett\Models\Ecampussql\TblFile())->withAttributes($file));
			}
		}

		$xml = (new XmlPresentation())->create($files);
	}
}