# README

This is documentation for ecampus statistics v2

#TODO
* Show a typical import, both all and daily
* Update installed mongo packages (if new version works. yum list installed | grep mongo)



#Prerequisities

##FreeTDS and unixODBC
FreeTDS + unixODBC must be installed and configured. Next comes examples of my files on baham.

/etc/odbc.ini

```
[ECAMPUSSQL]
Driver = FreeTDS
Address = ecampussql.bibsys.no
Port = 1433
TDS_Version = 8.0
Database = Relay441

```


/etc/odbcinst.ini 

```
[PostgreSQL]
Description		= ODBC for PostgreSQL
Driver		= /usr/lib/psqlodbc.so
Setup		= /usr/lib/libodbcpsqlS.so
Driver64		= /usr/lib64/psqlodbc.so
Setup64		= /usr/lib64/libodbcpsqlS.so
FileUsage		= 1

[MySQL]
Description		= ODBC for MySQL
Driver		= /usr/lib/libmyodbc5.so
Setup		= /usr/lib/libodbcmyS.so
Driver64		= /usr/lib64/libmyodbc5.so
Setup64		= /usr/lib64/libodbcmyS.so
FileUsage		= 1

[FreeTDS]
UsageCount		= 1
Driver = 	/usr/lib64/libtdsodbc.so.0

```

/etc/freetds.conf 


```
[kim@baham ~]$ cat /etc/freetds.conf 
[global]
	text size = 64512
	client charset = UTF-8
	tds version = 8.0
# A typical Sybase server
[egServer50]
	host = symachine.domain.com
	port = 5000
	tds version = 5.0

# A typical Microsoft server
[egServer70]
	host = ntmachine.domain.com
	port = 1433
	tds version = 7.0
 [PERSEUS]
     	host = perseus.bibsys.no
     	port = 1433
	tds version = 8.0
	client charset = UTF-8
[ECAMPUSSQL]
	host = ecampussql.bibsys.no
	port = 1433
	tds version = 8.0
 [PICTOR]
        host = pictor.bibsys.no
        port = 1433
        tds version = 8.0
        client charset = UTF-8
```
###Testing
If configured right, you should try and see

```
(screen) [kim@baham ~]$ isql -v -s ECAMPUSSQL username password
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
(screen) [kim@baham ~]$ tsql -S ECAMPUSSQL -U username  -P password
locale is "en_US.UTF-8"
locale charset is "UTF-8"
using default charset "UTF-8"
1> 
```

##Packages
Installed packages are:

###PHP
```
[kim@baham ~]$ yum list installed | grep php
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

###Mongo

```
[kim@baham ~]$ yum list installed | grep mongo
libmongodb.x86_64                     2.4.12-2.el6                     @epel    
mongodb-org.x86_64                    2.6.6-1                          @10gen   
mongodb-org-mongos.x86_64             2.6.6-1                          @10gen   
mongodb-org-server.x86_64             2.6.6-1                          @10gen   
mongodb-org-shell.x86_64              2.6.6-1                          @10gen   
mongodb-org-tools.x86_64              2.6.6-1                          @10gen   
```


# Installation

##Mongodb
This is written of what I remember, something here may be wrong.

To upgrade

```
sudo yum install mongodb-org

```

EXAMPLE: How to create admin user https://gist.github.com/tamoyal/10441108

Make sure that auth=true is commented out in /etc/mongod.conf, then:

```
sudo service mongod restart
```

Enter mongo shell

```
mongo
```

Create users

```
db.createUser({user:"admin",pwd:"PASSWORD", roles:[{role:"root",db:"admin"}]})
db.createUser({user:"apiUser",pwd:"PASSWORD", roles:[{role:"read",db:"ecampus"}]})
db.createUser({user:"ecampususer",pwd:"PASSWORD", roles:[{role:"readWrite",db:"ecampus"}]})
```
	


In /etc/mongd.conf, make sure that: auth=true. After that 

```
sudo service mongod restart
```


##The project

First, clone the repository

```
git clone https://kimatuninett@bitbucket.org/kimatuninett/ecampus-new.git
```

Install dependencies (make sure you are in project root)
```
composer install
```

Copy and rename .env.example to .env and fill out all fields.

* MONGO refers to the local mongo database
* ESQL  is where users are found. organisations are also derived from them.
* PICTOR is where statistics for requestPerHour, dailyUserAgents and dailyUniqueTraffic are collected

```
MONGO_HOST=
MONGO_USERNAME=
MONGO_PASSWORD=
MONGO_DATABASE=

ESQL_HOST=ECAMPUSSQL
ESQL_USERNAME=
ESQL_PASSWORD=
ESQL_DATABASE=

PICTOR_HOST=
PICTOR_USERNAME=
PICTOR_PASSWORD=
PICTOR_DATABASE=
```

Update crontab to run jobs. If PHP is configured to have a standard timezone of UTC, make sure this job runs after 01 and with some margin (I used to run it 02:00). If not, some imports (requestsPerHour, daily*) will import the wrong date.

```
# Example of job definition:
# .---------------- minute (0 - 59)
# |  .------------- hour (0 - 23)
# |  |  .---------- day of month (1 - 31)
# |  |  |  .------- month (1 - 12) OR jan,feb,mar,apr ...
# |  |  |  |  .---- day of week (0 - 6) (Sunday=0 or 7) OR sun,mon,tue,wed,thu,fri,sat
# |  |  |  |  |
# *  *  *  *  * user-name command to be executed
  0  2  *  *  * kim php /home/kim/ecampus/EcampusStatistics/index.php daily
  0  2  *  *  * kim php /home/kim/ecampus/EcampusStatistics/index.php mediasite
```
##Configuring the project config file
Make sure all directories are correct. Example of my file comes next.

```
/**
 * About the fields:
 * debug : enable this and nothing will be inserted to mongo, even if it will look like it happens. Used in testing.
 * startDateToImportIISLogs : This was the date when it seemed that the system was used by other real people (and not just testing)
 * lastupdates_doc_key : used as an identified in the lastupdates collection to use one document containing last user id imported etc.
 * relaymedia: Root path to relaymedia
 * root : Root path to project
 * folders_to_scan_for_files : folders to scan for presentations
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
```

##Configuring /etc/php.ini
I have added this in the top of the configuration file. Make sure the log file exists.

```
[PHP]
date.timezone = "UTC"

error_log = /var/log/php-errors.log
display_errors = On
display_startup_errors = On
error_reporting = -1
log_errors = On


```


#About
##Organisation

```
ecampus-new/
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
* Run contains wrapper classes for daily, full import, and mediasite
* Since mongodb has no "schema", the name of the fields are defined where. One schema mostly relates to one collection. They are used through the code, so it is possible to change the name of one attribute one place, and it will be reflected through the code
* Tests contains files with tests (if any).


#Maintenance

##If a new version of the relay database is released

Update Database in /etc/odbc.ini 

```
[ECAMPUSSQL]
Driver = FreeTDS
Address = ecampussql.bibsys.no
Port = 1433
TDS_Version = 8.0
Database = Relay441

```

Update .env file in project root

#FAQ
Something here?


#My own notes etc
See Aggregering.txt in the project folder for examples of doing various tasks in mongodb


