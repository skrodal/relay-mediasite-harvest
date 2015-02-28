<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

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
