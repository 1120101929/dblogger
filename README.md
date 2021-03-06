Database Manager
================

<p>This class provides a safe connection with database (yeah, it does use transaction) and log all actions performed daily into a single file/day.</p>
<p>You can audit all queries was performed into database, with values, datetime and totally debugged by viewing the files created into ./log/ folder.</p>

---
[![Total Downloads](https://poser.pugx.org/connection/dbmanager/downloads.svg)](https://packagist.org/packages/connection/dbmanager)
[![Latest Unstable Version](https://poser.pugx.org/connection/dbmanager/v/unstable.svg)](https://packagist.org/packages/connection/dbmanager)
[![Latest Stable Version](https://poser.pugx.org/connection/dbmanager/v/stable.svg)](https://packagist.org/packages/connection/dbmanager)
[![License](https://poser.pugx.org/connection/dbmanager/license.svg)](https://packagist.org/packages/connection/dbmanager)

---
## License

<p>This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.</p>

<p>This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.</p>

<p>You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.</p>

---

## Installation

Execute the [sql file](src/accounts.sql "Example test table") located in ```./src/```

Install [apache/log4php] using [composer].

To install, add ```apache/log4php``` to your ```composer.json``` file.

```
{
   "require" : {
    "apache/log4php": "2.3.0"
  },
}
```

From there, use the ```composer install``` or ```composer update``` commands to install.


---

## Basic Usage

```php
<?php
define('DB_HOST', 'my-host'); // define the host of mysql server
define('DB_PORT', 3306); // define the port of mysql server
define('DB_USER', 'my-user'); // define the user of mysql server
define('DB_PASS', 'my-pass'); // define the password of mysql server
define('DB_NAME', 'my-db'); // define the database name
define('DB_ENCODING', 'utf8'); // define the encoding of statements

// Assuming you installed from Composer:
require "vendor/autoload.php";

// Get the instance of DBManager:
$pdo = DBManager::getInstance();

###### INSERT STATEMENT ######
// Array with key->val pairs (columns that will be created)
$paramIns = array(
    'cn1' => 'cv1',
    'cn2' => 'cv2',
    'cnN' => 'cvN',
);
// Creates the sql
$insert = $pdo->createInsert('tableName', $paramIns);
/*
Output: INSERT INTO tableName(cn1,cn2,cnN) VALUES (:cn1,:cn2,:cnN);
*/
$resultInsert = $pdo->query($insert);

###### UPDATE STATEMENT ######
// Array with key->val pairs (columns that will be updated)
$paramUpd = array(
    'cn1' => 'cv1',
    'cn2' => 'cv2'
);
// Array with columnKey => val (columns that will be used on WHERE clause)
// If null or empty, the result sql will be "UPDATE tableName SET col1 = :val1, col2 = :va2, ... colN = :valN ;"
$paramCond = array(
	// The first parameter of array data is the where clause (like, equal, less, etc... [see the main class constants]])
	// The second parameter of second array is the operand type (and|or) to concat with next column. Use null if the last param
    'ck1' => array(DBManager::COL_EQUAL => array('cv1' => 'and')),
    'ck2' => array(DBManager::COL_EQUAL => array('cv2' => null))
);
// Creates the sql
$update = $pdo->createUpdate('tableName', $paramUpd, $paramCond);
/*
Output: UPDATE tableName SET `cn1` = :cn1, `cn2` = :cn2 WHERE `ck1` = :ck1 and `ck2` =  :ck2 ;
*/
$resultUpdate = $pdo->query($update);

###### DELETE STATEMENT ######
// Array with key->val pairs to where clause to delete
// If null or empty, the result sql will be "DELETE FROM tableName;"
$paramDel = array(
	// The first parameter of array data is the where clause (like, equal, less, etc... [see the main class constants]])
	// The second parameter of second array is the operand type (and|or) to concat with next column. Use null if the last param
	'ck1' => array(DBManager::COL_EQUAL => array('cv1' => null))
);
$delete = $pdo->createDelete('tableName', $paramDel);
/*
Output: DELETE FROM table WHERE  `key1` =  :key1;
*/
$resultDelete = $pdo->query($delete);

###### SELECT STATEMENT ######
// Array with columns to retrieve
$paramSelect = array('cn1', 'cnN');
// Array with columnKey => value to where clause to select
// If null or empty, the result sql will be "SELECT col1, col2,... colN FROM tableName ORDER BY col1,col2,...colN;"
$paramWhere = array(
	// The first parameter of array data is the where clause (like, equal, less, etc... [see the main class constants]])
	// The second parameter of second array is the operand type (and|or) to concat with next column. Use null if the last param
    'cn1' => array(DBManager::COL_EQUAL => array('cv1' => 'and')),
    'cn2' => array(DBManager::COL_LIKE => array('cv2' => null))
);
// Array with columnKey -> value to order clause to select
// If null or empty, the result sql will be "SELECT col1, col2,... colN FROM tableName;"
$paramOrder = array(
	// Fields used in order
    'fields' => array('cn1', 'cn2'),
	// Order type (asc|desc)
    'order' => 'ASC'
);
// Creates the sql
$select = $pdo->createSelect('tableName', $paramSelect, $paramWhere, $paramOrder);
/*
Output: SELECT `cn1`, `cnN` FROM table WHERE `cn1` = :cn1 and `cn2` LIKE :cn2 ORDER BY cn1,cn2 ASC;
*/  
$resultSelect = $pdo->select($select);  
  
unset($pdo);  
?>  
```  

## Screenshots  

![Logger HTML view](views/screenshot.png "The script result on the web")  
![Logger FILE view](views/screenshot2.png "The script result on the file")

[apache/log4php]:http://logging.apache.org/log4php/download.html
[composer]:http://getcomposer.org/