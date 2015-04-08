<?php
use Uninett\Core\Seeders\Seeder;
use Uninett\Core\XmlPresentation;
use Uninett\Database\DatabaseConnection;
use Uninett\Models\Ecampussql\TblFile;

/**
 * Class TblFileTableSeeder
 */
class TblFileTableSeeder implements Seeder{

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
		foreach (range(1, 2) as $filePresentation_presId) {
			$files = [];

			$presentationName = substr(md5(rand()), 0, 7);

			$someMonth= \Carbon\Carbon::now()->format('m');

			$someDay  = \Carbon\Carbon::now()->format('d');

			$date =  $someMonth . "." . $someDay;

			$dir = substr(md5(rand()), 0, 7);

			foreach (range(1, 4) as $fileID) {
				$file = [
					'filePresentation_presId'  => $filePresentation_presId,
					'fileId'    => $fileID,
					'filePath' => getenv('APP_PATH') . '/storage/xml/' . "relaymedia/ansatt/{$date}/" . $dir . '/' . $presentationName . '_' . $fileID .'.xml',
					'createdOn' => \Carbon\Carbon::now(),
				];

				$files[] = $file;

				$this->db->insert((new TblFile)->withAttributes($file));

			}
			(new XmlPresentation)->create($files);
		}
	}
}