<?php

use Phinx\Migration\AbstractMigration;

class CreateTableTblFile extends AbstractMigration
{
	private $tableName = "tblFile";
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

		$table
			->addColumn('filePresentation_presId', 'integer')
			->addColumn('fileId', 'integer')
			->addColumn('filePath', 'string')
			->addColumn('createdOn', 'datetime')
			->create();
	}

	/**
	 * Migrate Down.
	 */
	public function down()
	{
		$this->dropTable($this->tableName);
	}
}