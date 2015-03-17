<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);



//http://stackoverflow.com/questions/6511913/php-sql-error-message-changed-database-context
/*sqlsrv_configure ( "WarningsReturnAsErrors" , 0 ); //OFF
sqlsrv_configure ( "LogSeverity" , 1 ); //SQLSRV_LOG_SEVERITY_ERROR*/


//http://forum.symfony-project.org/viewtopic.php?t=9958&p=40272

/**
 * MSSQL-queries may sometimes fail and mssql_last_error() returns "Changed database context to Relay441"
 * It may seem like this message is misinterpeted by PHP and it ends up as an error.
 * mssql_min_error_severity should remove those messages (and possibly others?)
 *
 * Source http://forum.symfony-project.org/viewtopic.php?t=9958&p=40272
 */
mssql_min_error_severity(16);
//TODO: Experimental!
ini_set('memory_limit', '1536M');
ini_set('mssql.timeout', 60 * 10);



$projectRoot = dirname( __DIR__);


/**
 * Autoload third party modules
 */
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config.php';

/*
 * Load .env file
 */
Dotenv::load($projectRoot);
