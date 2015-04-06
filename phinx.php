<?php

Dotenv::load(__DIR__);

return array(
	"paths" => array(
		"migrations" => "database/migrations"
	),
	"environments" => array(
		"default_migration_table" => "phinxlog",
		"default_database" => "development",
		"development" => array(
			"adapter" => "mysql",
			"host" => getenv('PICTOR_HOST'),
			"name" => getenv('PICTOR_DATABASE'),
			"user" => getenv('PICTOR_USERNAME'),
			"pass" => getenv('PICTOR_PASSWORD'),
			"port" => 3306,
		)
	)
);
?>