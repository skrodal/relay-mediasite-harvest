<?php

use Phinx\Migration\AbstractMigration;

class CreateRequestsPerHourTable extends AbstractMigration
{



	/**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
	    $tableName = \Uninett\Schemas\RequestsPerHourSchema::PICTOR_TABLE_NAME;

	    if(!$this->table($tableName)->exists())
			$this->table($tableName)
				->addColumn('Dates', 'string')
				->addColumn('BytesSent', 'integer')
				->addColumn('Requests', 'integer')
				->save();


	    $startDate = new DateTime('2015-02-01');
	    $endDate = new DateTime('today');

	    $dateInterval = DateInterval::createFromDateString('1 hour');

	    $datePeriod = new DatePeriod($startDate, $dateInterval, $endDate);

	    $faker = Faker\Factory::create();

	    foreach ($datePeriod as $dt)
	    {
		    $request = $faker->numberBetween(1,100);

		    $bytesSent = $faker->numberBetween(1,10000);

		    $date =  $dt->format('Y-m-d H:i:s');

		    $this->execute("INSERT INTO {$tableName} (Dates, BytesSent, Requests) VALUES ('$date', $request, $bytesSent)");
	    }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
	    $tableName = \Uninett\Schemas\RequestsPerHourSchema::PICTOR_TABLE_NAME;

	    $this->table($tableName)->drop();

    }
}