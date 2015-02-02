<?php

/**
 * This class uses the PDO library to access and perform queries in a MySQL server
 * It's safe, fast and all changes are logged into a file for future audit.
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

use SRC\Manager\DBManager;

/**
 * Set the default locale
 */
setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");

/**
 * Set the default timezone
 */
date_default_timezone_set('America/Sao_Paulo');

/**
 * The host of the mysql server
 */
define('DB_HOST', 'localhost');
/**
 * The port of the mysql server
 */
define('DB_PORT', 3306);
/**
 * The user of the mysql server
 */
define('DB_USER', 'root');
/**
 * The password of the mysql server
 */
define('DB_PASS', 'admin');
/**
 * The database to connect
 */
define('DB_NAME', 'test');
/**
 * The database encoding (used to insert and update statements)
 */
define('DB_ENCODING', 'utf8');
/**
 * Debug sql on errors
 */
define('SQL_DEBUG', false);

require_once(__DIR__ . '/vendor/autoload.php');

// Set the config file
$config = __DIR__ . '/config.xml';

$pdo = DBManager::newInstance($config);

################### INSERT STATEMENT ###################
$paramIns = [
    'login' => 'adm2',
    'password' => '0DPiKuNIrrVmD8IUCuw1hQxNqZc=',
    'access_level' => 100,
    'vip_level' => 4,
    'email' => 'root@vmendonca.com.br'
];
$insert = $pdo->createInsert('accounts', $paramIns);
$resultInsert = $pdo->query($insert);

################### UPDATE STATEMENT ###################
$paramUpd = [
    'access_level' => -400,
    'vip_level' => 5,
];
$paramCond = [
    'login' => [DBManager::COL_EQUAL => ['adm2' => 'and']],
    'email' => [DBManager::COL_EQUAL => ['root@vmendonca.com.br' => null]]
];
$update = $pdo->createUpdate('accounts', $paramUpd, $paramCond);
$resultUpdate = $pdo->query($update);

################### DELETE STATEMENT ###################
$paramDel = ['login' => [DBManager::COL_EQUAL => ['adm2' => null]]];
$delete = $pdo->createDelete('accounts', $paramDel);
$resultDelete = $pdo->query($delete);

################### SELECT STATEMENT ###################
$paramSelect = ['login', 'email', 'access_level', 'vip_level'];
$paramWhere = [
    'login' => [DBManager::COL_LIKE => ['adm' => 'and']],
    'email' => [DBManager::COL_LIKE => ['mend' => null]]
];
$paramOrder = [
    'fields' => ['access_level', 'vip_level'],
    'order' => 'ASC'
];
$select = $pdo->createSelect('accounts', $paramSelect, $paramWhere, $paramOrder);
$resultSelect = $pdo->select($select);

// unset the object, close connection and clear the logger
unset($pdo);
