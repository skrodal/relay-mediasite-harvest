<?php

	Dotenv::load(__DIR__);

	return array(
		"paths"        => array(
			"migrations" => "migrations"
		),
		"environments" => array(
			"default_migration_table" => "phinxlog",
			"default_database"        => "development",
			"development"             => array(
				"adapter" => "mysql",
				"host"    => getenv('SCREENCAST_SQL_HOST'),
				"name"    => getenv('SCREENCAST_SQL_DATABASE'),
				"user"    => getenv('SCREENCAST_SQL_USERNAME'),
				"pass"    => getenv('SCREENCAST_SQL_PASSWORD'),
				"port"    => 3306,
			)
		)
	);
?>