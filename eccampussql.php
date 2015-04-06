<?php
Dotenv::load(__DIR__);

return array(
	"paths" => array(
		"migrations" => "database/migrations/ecampussql"
	),
	"environments" => array(
		"default_migration_table" => "phinxlog",
		"default_database" => "development",
		"development" => array(
			"adapter" => "sqlsrv",
			"host" => getenv('ESQL_HOST'),
			"name" => getenv('ESQL_DATABASE'),
			"user" => getenv('ESQL_USERNAME'),
			"pass" => getenv('ESQL_PASSWORD'),
		/*	'port' => getenv('ESQL_PORT'),*/
	)
));

?>