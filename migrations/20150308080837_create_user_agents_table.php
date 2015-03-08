<?php

use Phinx\Migration\AbstractMigration;

class CreateUserAgentsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
	    $tableName = \Uninett\Schemas\DailyUserAgentsSchema::PICTOR_TABLE_NAME;
		    if(!$this->table($tableName)->exists())
		    $this->table($tableName)
			    ->addColumn('Dates', 'datetime')
			    ->addColumn('hits', 'integer')
			    ->addColumn('userAgent', 'string')
			    ->save();


	    $startDate = new DateTime('2015-02-01');
	    $endDate = new DateTime('today');

	    $dateInterval = DateInterval::createFromDateString('1 day');

	    $datePeriod = new DatePeriod($startDate, $dateInterval, $endDate);

	    $faker = Faker\Factory::create();

	    foreach ($datePeriod as $dt)
	    {
		    $userAgent = $faker->userAgent;

		    $hits = $faker->numberBetween(1,10000);

		    $date =  $dt->format('Y-m-d H:i:s');

		    $this->execute("INSERT INTO {$tableName} (Dates, Hits, UserAgent) VALUES ('$date', $hits, '$userAgent')");
	    }
    }

	/**
	 * Migrate Down.
	 */
	public function down()
	{
		$tableName = \Uninett\Schemas\DailyUserAgentsSchema::PICTOR_TABLE_NAME;
		$this->table($tableName)->drop();
	}
}