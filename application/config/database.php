<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'store_mid';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$db['terminal']['hostname'] = 'localhost';
$db['terminal']['username'] = 'root';
$db['terminal']['password'] = '';
$db['terminal']['database'] = 'terminal';
$db['terminal']['dbdriver'] = 'mysql';
$db['terminal']['dbprefix'] = '';
$db['terminal']['pconnect'] = TRUE;
$db['terminal']['db_debug'] = TRUE;
$db['terminal']['cache_on'] = FALSE;
$db['terminal']['cachedir'] = '';
$db['terminal']['char_set'] = 'utf8';
$db['terminal']['dbcollat'] = 'utf8_general_ci';
$db['terminal']['swap_pre'] = '';
$db['terminal']['autoinit'] = TRUE;
$db['terminal']['stricton'] = FALSE;

$db['anta2']['hostname'] = 'localhost';
$db['anta2']['username'] = 'root';
$db['anta2']['password'] = '';
$db['anta2']['database'] = 'antaold';
$db['anta2']['dbdriver'] = 'mysql';
$db['anta2']['dbprefix'] = '';
$db['anta2']['pconnect'] = TRUE;
$db['anta2']['db_debug'] = TRUE;
$db['anta2']['cache_on'] = FALSE;
$db['anta2']['cachedir'] = '';
$db['anta2']['char_set'] = 'utf8';
$db['anta2']['dbcollat'] = 'utf8_general_ci';
$db['anta2']['swap_pre'] = '';
$db['anta2']['autoinit'] = TRUE;
$db['anta2']['stricton'] = FALSE;



/* End of file database.php */
/* Location: ./application/config/database.php */
