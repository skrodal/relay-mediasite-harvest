<?php

use Phinx\Migration\AbstractMigration;

class CreateTblUsers extends AbstractMigration
{

	private $tableName = "tblUser";
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
	    $table = $this->table($this->tableName);
	    $table->addColumn('userId', 'integer')
		    ->addColumn('userName', 'string')
		    ->addColumn('userEmail', 'string')
		    ->addColumn('userDisplayName', 'string')
		    ->addColumn('createdOn', 'datetime')

		    ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
	    $this->table($this->tableName)->drop();
    }
}