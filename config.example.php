<?php
/**
 * About the fields:
 * debug : enable this and nothing will be inserted to mongo, even if it will look like it happens. Used in testing.
 * startDateToImportIISLogs : This was the date when it seemed that the system was used by other real people (and not just testing)
 * lastupdates_doc_key : used as an identified in the lastupdates collection to use one document containing last user id imported etc.
 * relaymedia: Root path to relaymedia
 * root : Root path to project
 * folders_to_scan_for_files : folders to scan for presentations
 *  depth: count depth up to and including ansatt|student folder (used by ConvertHelper and PresentationCreate->destUrlToRootPath
 * userStatus : What the different statuses mean
 * numberOfDecimals : Used to control how many decimalas that shall be used in calculations and stored in mongdb
 * mediasite : Which directories that contains mediasite data
 */
Uninett\Config::add(
[
	'settings' => [
		'debug' => false,
		'startDateToImportIISLogs' =>  '2013-05-22',
		'lastupdates_doc_key' => '1',
		'relaymedia' => '/home/uninett/relaymedia',
		'root' => '/home/kim/ecampus-new'
	],
	'folders_to_scan_for_files' => [
		'ansatt' => '/home/uninett/relaymedia/ansatt',
		'student' => '/home/uninett/relaymedia/student',
	    'depth' => 4
	],
	'userStatus' => [
		-1  => 'not set',
		1 => 'account on relay and no userfolder',
		2 => 'account on relay and have userfolder',
		3 => 'deleted account on relay, has content on disk',
		4 => 'deleted account on relay, has no content on disk'
	],
	'arithmetic' => [
		'numberOfDecimals' => 2
	],
	'mediasite' => [
		'directories' => [
			"/home/uninett/mediasite/"
		]
	]
]);