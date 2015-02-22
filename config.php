<?php
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
		'student' => '/home/uninett/relaymedia/student'
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