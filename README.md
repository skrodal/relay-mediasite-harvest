# README

This is documentation for UNINETT eCampus Relay/Mediasite content/data harvester v2.

**NOTE** The Mediasite features of this service is no longer used, nor needed. Ignore any references to Mediasite.

The system collects and consolidates data from the Relay DB (users, presentations), Screencast DB (IIS logs) and
XML metadata-files pertaining to each and every presentation.

Consolidated information is stored in MongoDB collections for easy retrieval by an API (different service). Significant data includes:
 
 - User 
 	- username, name, email, affiliation (employee/student), status (has_content, is_deleted,...)
 	- presentation
 - Presentation
 	- descriptive metadata
 	- technical metadata
 	- is_deleted
 	- paths and URLs to encoded files
  
 
The system is tailor-made for the specific TechSmith Relay installation used at UNINETT (profiles, publication paths/URLS, metadata, etc), 
but parts of it may nonetheless be useful, with some effort, for anyone wanting to do something similar.

The service was originally created by Kim Syversen, UNINETT. Updated and maintained by Simon Skrødal, UNINETT.

# TODO

* When ScreencastSQL Connection is in place, enable `RunRelay*`- pertaining to Screencast data    
* Show a typical import, both all and daily
* <strike>When (end-user)service for presentation deletion is in place, re-enable the PresentationCheckForDeleted routine</strike>
	
	No - We now have a separate service for tracking movable/moved/deleted/restored content, which is also implemented in the Relay API. 
	The RelayAdmin client currently fetches all content from the harvester, as well as content from the delete-service. It then cross-checks 
	the two to determine what/how of the former content to present.

# Prerequisities

## FreeTDS <strike>and unixODBC</strike>
FreeTDS <strike>+ unixODBC </strike>must be installed and configured, e.g.:
<strike>
/etc/odbc.ini

```
[UNINETTSQL]
host = ...
port = 1433
tds version = 8.0

```


/etc/odbcinst.ini 

```

[FreeTDS]
UsageCount	= 1
Driver 		= 	/usr/lib64/libtdsodbc.so.0

```
</strike>
/etc/freetds.conf 

```

[UNINETTSQL]
	host = ...
	port = 1433
	tds version = 8.0
 [SCREENCASTSQL]
        host = ...
        port = 1433
        tds version = 8.0
        client charset = UTF-8
```
### Testing
If configured right, you should try and see

```
$ isql -v -s UNINETTSQL username password
+---------------------------------------+
| Connected!                            |
|                                       |
| sql-statement                         |
| help [tablename]                      |
| quit                                  |
|                                       |
+---------------------------------------+
SQL> 

```


```
$ tsql -S UNINETTSQL -U username  -P password
locale is "en_US.UTF-8"
locale charset is "UTF-8"
using default charset "UTF-8"
1> 

```

## Packages
Installed packages:

### PHP
```
$ yum list installed | grep php

php55w.x86_64                         5.5.21-1.w6                      @webtatic
php55w-cli.x86_64                     5.5.21-1.w6                      @webtatic
php55w-common.x86_64                  5.5.21-1.w6                      @webtatic
php55w-devel.x86_64                   5.5.21-1.w6                      @webtatic
php55w-mcrypt.x86_64                  5.5.21-1.w6                      @webtatic
php55w-mssql.x86_64                   5.5.21-1.w6                      @webtatic
php55w-odbc.x86_64                    5.5.21-1.w6                      @webtatic
php55w-pdo.x86_64                     5.5.21-1.w6                      @webtatic
php55w-pear.noarch                    1:1.9.4-7.w6                     @webtatic
php55w-pecl-geoip.x86_64              1.0.8-1.w6                       @webtatic

```

### Mongo

```
$ yum list installed | grep mongo

libmongodb.x86_64                     2.4.12-2.el6                     @epel    
mongodb-org.x86_64                    2.6.6-1                          @10gen   
mongodb-org-mongos.x86_64             2.6.6-1                          @10gen   
mongodb-org-server.x86_64             2.6.6-1                          @10gen   
mongodb-org-shell.x86_64              2.6.6-1                          @10gen   
mongodb-org-tools.x86_64              2.6.6-1                          @10gen   

```


# Installation

## Mongodb

Install and create users as follows:

1. Enter mongo shell

```
mongo
```

2. Create users

```
db.createUser({user:"admin",pwd:"PASSWORD", roles:[{role:"root",db:"admin"}]})
db.createUser({user:"apiUser",pwd:"PASSWORD", roles:[{role:"read",db:"RelayMediasiteHarvestDB"}]})
db.createUser({user:"systemUser",pwd:"PASSWORD", roles:[{role:"readWrite",db:"RelayMediasiteHarvestDB"}]})
```

Enter created user with role `readWrite` ((e.g. `systemUser`)) to `MONGO`-section of `.env`-file.
  
User with role `read` (e.g. `apiUser`) is suitable for use by API responsible for extracting harvested data. 

##The project

First, clone the repository

Install dependencies (make sure you are in project root)

```
composer install
```

Copy and rename .env.example to .env and fill out all fields.

* MONGO refers to the local mongo database
* RELAY_SQL  is where users are found. organisations are also derived from them.
* SCREENCAST_SQ is where statistics for requestPerHour, dailyUserAgents and dailyUniqueTraffic are collected

```
MONGO_HOST=
MONGO_USERNAME=
MONGO_PASSWORD=
MONGO_DATABASE=

RELAY_SQL_HOST=
RELAY_SQL_USERNAME=
RELAY_SQL_PASSWORD=
RELAY_SQL_DATABASE=

SCREENCAST_SQL_HOST=
SCREENCAST_SQL_USERNAME=
SCREENCAST_SQL_PASSWORD=
SCREENCAST_SQL_DATABASE=
```

Update crontab to run jobs. If PHP is configured to have a standard timezone of UTC, make sure this job runs after 01 and with some margin (I used to run it 02:00). If not, some imports (requestsPerHour, daily*) will import the wrong date.


```
# Example of Relay Harvest job definition:

MAILTO=whoever_wants_nightly_reports@uninett.no

#
#     .---------------- minute (0 - 59)
#     |      .------------- hour (0 - 23)
#     |      |              .---------- day of month (1 - 31)
#     |      |              |      .------- month (1 - 12) OR jan,feb,mar,apr ...
#     |      |              |      |      .---- day of week (0 - 6) (Sunday=0 or 7) OR sun,mon,tue,wed,thu,fri,sat
#     |      |              |      |      |
# 	  *  	 *  			*  	   *      * 	 user-name command to be executed
      0      0-1,5-23       *      *      *      simon php /path/to/relay-mediasite-harvest/index.php relayHourly
      20     1              *      *      *      simon php /path/to/relay-mediasite-harvest/index.php relayNightly
```



## Configuring the project config file
Make sure all directories are correct. Example of config file comes next.

```
/**
 * About the fields:
 * debug : enable this and nothing will be inserted to mongo, even if it will look like it happens. Used in testing.
 * startDateToImportIISLogs : This was the date when it seemed that the system was used by other real people (and not just testing)
 * lastupdates_doc_key : used as an identified in the lastupdates collection to use one document containing last user id imported etc.
 * relaymedia: Root path to relaymedia
 * root : Root path to project
 * folders_to_scan_for_files : folders to scan for presentations
 *		depth: no. of folders down to (and including) ansatt|student path 
 * userStatus : What the different statuses mean
 * numberOfDecimals : Used to control how many decimalas that shall be used in calculations and stored in mongdb
 * mediasite : Which directories that contains mediasite data
 */
<?php
Uninett\Config::add(
[
	'settings' => [
		'debug' => false,
		'startDateToImportIISLogs' =>  '2013-05-22',
		'lastupdates_doc_key' => '1',
		'relaymedia' => '/path/to/relaymedia',
		'root' => '/path/to/relay-mediasite-harvest/'
	],
	'folders_to_scan_for_files' => [
		'ansatt' => '/path/to/relaymedia/ansatt',
		'student' => '/path/to/relaymedia/student',
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
			"/path/to/mediasite/"
		]
	]
]);
```

# About

## Organisation

```
/path/to/relay-mediasite-harvest/
├── App
│   ├── Collections
│   │   ├── DailyUniqueTraffic
│   │   ├── DailyUserAgents
│   │   ├── DailyVideos
│   │   ├── LastUpdates
│   │   ├── Mediasite
│   │   ├── Org
│   │   ├── Presentations
│   │   ├── RequestPerHour
│   │   ├── UserDiskusage
│   │   └── Users
│   ├── Database
│   ├── Helpers
│   ├── Models
│   ├── Run
│   ├── Schemas
│   └── Tests
├── start
└── vendor

```

* vendor contains third party applications. Usually its no need to enter this folder
* start/bootstrap autoloads some files and load .env file
* App/Collections contains one folder per collection
* App/Database contains database files
* Helpers contains various helper classes
* Models contains model files related to the collections. 
* Run contains wrapper classes for daily, full import, and Mediasite
* Since mongodb has no "schema", the name of the fields are defined where. One schema mostly relates to one collection. They are used through the code, so it is possible to change the name of one attribute one place, and it will be reflected through the code
* Tests contains files with tests (if any).


# Maintenance

## If a new version of the relay database is released

- Update .env file in project root to point to the new DB (e.g. `Relay507`)

#FAQ

TODO


# Other

See Aggregering.txt in the project folder for examples of doing various tasks in mongodb


