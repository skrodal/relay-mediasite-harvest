<?php

use Phinx\Migration\AbstractMigration;

class CreateUniqueTrafficTable extends AbstractMigration
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
	    $tableName = \Uninett\Schemas\DailyUniqueTrafficSchema::PICTOR_TABLE_NAME;
	    if(!$this->table($tableName)->exists())
	    $this->table($tableName)
		    ->addColumn('date', 'datetime')
		    ->addColumn('ip', 'string')
		    ->addColumn('uri', 'string')
		    ->addColumn('referer', 'string')
		    ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
	    $tableName = \Uninett\Schemas\DailyUniqueTrafficSchema::PICTOR_TABLE_NAME;

	    $this->table($tableName)->drop();
    }
}