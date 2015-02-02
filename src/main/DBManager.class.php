<?php

/**
 * Class DBManager - Connect a MySQL server using PDO and Log it!
 */

namespace SRC\Manager {

	use DateTime;
	use Exception;
	use InvalidArgumentException;
	use Logger;
	use PDO;
	use SRC\Impl\IModels;

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
	 *
	 * @package SRC
	 * @subpackage Main
	 * @author Victor Mendonca <victor.mendonca@live.com>
	 * @copyright copyright (c) copyright 2014
	 * @since   October 1, 2014 — Last update February 2, 2015
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public Licence (GPL)
	 * @version 2.1.1
	 * @link http://fb.me/vmend3
	 */
	class DBManager {

		/**
		 * Where clause LIKE
		 *
		 * @var string Format value as as (LIKE '%<b>colValue</b>%')
		 */
		const COL_LIKE = "LIKE '%%%s%%'";
		/**
		 * Where clause EQUAL (=)
		 *
		 * @var string Format value as as (= '<b>colValue</b>')
		 */
		const COL_EQUAL = "= '%s'";
		/**
		 * Where clause different (<>)
		 *
		 * @var string Format value as as (<> '<b>colValue</b>')
		 */
		const COL_NOTEQUAL = "<> '%s'";
		/**
		 * Where clause LESS (<)
		 *
		 * @var string Format value as as (< '<b>colValue</b>')
		 */
		const COL_LESS = "< '%s'";
		/**
		 * Where clause LESS OR EQUAL (<=)
		 *
		 * @var string Format value as as (<= '<b>colValue</b>')
		 */
		const COL_LESSOREQUAL = "<= '%s'";
		/**
		 * Where clause GREATER (>)
		 *
		 * @var string Format value as as (> '<b>colValue</b>')
		 */
		const COL_GREATER = "> '%s'";
		/**
		 * Where clause GREATER OR EQUAL (>=)
		 *
		 * @var string Format value as as (>= '<b>colValue</b>')
		 */
		const COL_GREATEROREQUAL = ">= '%s'";
		/**
		 * Where clause IN LIST (val1, val2,... valn)
		 *
		 * @var string Format value as as (IN ('<b>val1</b>','<b>val1</b>',...'<b>valn</b>'))
		 */
		const COL_INLIST = "IN ('%s')";
		/**
		 * Where clause NOT IN LIST (val1, val2,... valn)
		 *
		 * @var string Format value as as (NOT IN ('<b>val1</b>','<b>val1</b>',...'<b>valn</b>'))
		 */
		const COL_NOTINLIST = "NOT IN ('%s')";
		/**
		 * Where clause IS NULL
		 *
		 * @var string Format value as as (<b>colName</b> IS NULL)
		 */
		const COL_ISNULL = "IS NULL";
		/**
		 * Where clause IS NOT NULL
		 *
		 * @var string Format value as as (<b>colName</b> IS NOT NULL)
		 */
		const COL_NOTNULL = "IS NOT NULL";
		/**
		 * Holds DBManager instance
		 *
		 * @access public
		 * @var DBManager $instance Holds database instance
		 */
		public static $instance = null; //.u
		/**
		 * Holds connection opened status
		 *
		 * @access public
		 * @var bool $initialized Holds connection opened status
		 */
		public static $initialized = false;
		/**
		 * Holds the logger (instance of Logger)
		 *
		 * @var Logger $log Holds the logger
		 */
		protected static $log;
		/**
		 * Holds PDO connection
		 *
		 * @access private
		 * @var PDO $connection Holds PDO connection
		 */
		private static $connection = null;
		/**
		 * Holds statements parameters
		 *
		 * @access private
		 * @var array $params Holds statements parameters
		 */
		private static $param = [];
		/**
		 * Holds a collection from database
		 *
		 * @access private
		 * @var array $collection Holds a collection from database
		 */
		private static $collection = [];
		/**
		 * Holds statement mode (CRUD)
		 *
		 * @access private
		 * @var String $mode Holds statemend mode (CRUD)
		 */
		private static $mode = 'select';
		/**
		 * Holds the DBManager::$collection size
		 *
		 * @access private
		 * @var int $size Holds the DBManager::$collection size
		 */
		private static $size = 0;
		/**
		 * Holds the class required to create a new object (from collection)
		 *
		 * @access private
		 * @var String $callback Holds the class required do create a object (from collection)
		 */
		private static $callback = null;
		/**
		 * Holds the time when app was started
		 *
		 * @access private
		 * @var int $startTime Hlds the time when app was started
		 */
		private static $startTime = null;
		/**
		 * Holds the time when app was finished
		 *
		 * @access private
		 * @var int $endTime Hlds the time when app was finished
		 */
		private static $endTime = null;
		/**
		 * Holds the time used to run the app
		 *
		 * @access private
		 * @var int $executionTime Holds the time used to run the app
		 */
		private static $executionTime = null;
		/**
		 * Format string for log timestamps
		 *
		 * @access private
		 * @var string $dateFormat Valid PHP date() format string for log timestamps
		 */
		private static $dateFormat = 'l F\, jS \of Y h:i:s A';

		/**
		 * Private constructor in order of prevent new DBManager.
		 * Must be called only via DBManager::newInstance()
		 *
		 * @access private
		 * @see DBManager::newInstance()
		 *
		 * @param string $class The class name
		 *
		 * @throws \InvalidArgumentException
		 */
		public function __construct($class = __CLASS__) {
			self::$log = Logger::getLogger($class);
			self::$log->info("Initializing database connection.");
			$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
			if(DB_PORT != 3306) {
				$dsn = "mysql:host=" . DB_HOST . "," . DB_PORT . ";dbname=" . DB_NAME;
			}
			$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
			try {
				if(version_compare(PHP_VERSION, '5.3.6', '<') && defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
					$options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . DB_ENCODING;
				} else {
					$dsn .= ';charset=' . DB_ENCODING;
				}
				self::$connection = new PDO($dsn, DB_USER, DB_PASS, $options);
				if(version_compare(PHP_VERSION, '5.3.6', '<') && !defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
					$sql = 'SET NAMES ' . DB_ENCODING;
					self::$connection->exec($sql);
				}
				self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
				self::$initialized = true;
			} catch(Exception $e) {
				self::doException($e, $dsn);
			}

			return self::$connection;
		}

		/**
		 * Debug a excpetion found
		 *
		 * @access private
		 *
		 * @param Exception $e The exception catched
		 * @param string $statement The statement
		 *
		 * @throws InvalidArgumentException
		 * @return DBManagerException The exception error
		 */
		private static function doException(Exception $e, $statement = "") {

			if(!is_string($statement)) {
				$msg = sprintf('Statement must be string, %s given', gettype($statement));
				throw new InvalidArgumentException($msg);
			}

			$message = $e->getMessage();
			if(!is_null($statement) && !empty($statement) && (defined('SQL_DEBUG') && SQL_DEBUG === true)) {
				$message .= " in statement {$statement}";
			}
			$sqlstate = $e->getCode();

			$exception = DBManagerException::getInstance($message, 0, $e);
			$exception->setCode($sqlstate);
			$exception->setStatement($statement);

			self::$log->fatal($exception);

			return $exception;
		}

		/**
		 * Gets a unique instance of this
		 *
		 * @access public
		 * @staticvar DBManager $instance Class instance.
		 *
		 * @param boolean $l4php Log4PHP config file path
		 *
		 * @return DBManager Return this instance.
		 */
		public static function newInstance($l4php = null) {

			self::$startTime = microtime(true);
			if(!is_null($l4php)) {
				if(!file_exists($l4php) || !is_file($l4php) || !is_readable($l4php)) {
					$l4php = null;
				}
				if(!class_exists('Logger')) {
					$l4php = null;
				}
			}
			if(!is_null($l4php) && class_exists('Logger')) {
				Logger::configure($l4php);
			}
			if(null === self::$instance) {
				return self::$instance = new DBManager();
			}

			return self::$instance;
		}

		/**
		 * Get the current connection
		 *
		 * @return DBManager|\PDO
		 */
		public static function getConnection() {
			if(is_null(self::$connection)) {
				return new DBManager(__CLASS__);
			}

			return self::$connection;
		}

		/**
		 * Unset this object and clear logger
		 *
		 * @access public
		 * @return void
		 */
		public function __destruct() {
			self::$endTime = microtime(true);
			self::$executionTime = (self::$endTime - self::$startTime);
			self::$log->debug(sprintf("All queries performed in %g seconds", (float) self::$executionTime));
			self::$log->debug("Database closed");
			self::$log->debug("Changes made in: " . $this->getTimestamp());
			self::$log->clear();
		}

		/**
		 * Gets the correctly formatted Date/Time for the log entry.
		 *
		 * PHP DateTime is dump, and you have to resort to trickery to get microseconds
		 * to work correctly, so here it is.
		 *
		 * @access public
		 * @return string The formatted timestamp
		 */
		public function getTimestamp() {
			$originalTime = microtime(true);
			$micro = sprintf("%06d", ($originalTime - floor($originalTime)) * 1000000);
			$date = new DateTime(date('Y-m-d H:i:s.' . $micro, $originalTime));

			return $date->format(self::$dateFormat);
		}

		/**
		 * Used when a invalid method is called
		 *
		 * @access public
		 *
		 * @param string $method Function name
		 * @param mixed $arguments Function parameters
		 *
		 * @return void Call functions or log error
		 */
		public function __call($method, $arguments) {
			if(method_exists($this, $method)) {
				call_user_func_array([$this, $method], $arguments);
			} else {
				self::$log->warn("Called invalid method ({$method})");
			}
		}

		/**
		 * Create a instance from element self::$collection by it index ($collectionIndex)
		 *
		 * @access public
		 *
		 * @param int $collectionIndex
		 *
		 * @return Object Return a new object of type $object
		 */
		public function factoryFromCollection($collectionIndex) {
			$type = get_class(self::getCollection()[$collectionIndex]);

			/** @type \SRC\Impl\IModels $type */

			return $this->factory($type, self::getCollection()[$collectionIndex]);
		}

		/**
		 * Get the collection
		 *
		 * @access public
		 * @return array The collection from [select] statement
		 */
		public static function getCollection() {
			return self::$collection;
		}

		/**
		 * Create a new instance of $object using $params for constructor
		 *
		 * @access private
		 *
		 * @param IModels $object Name of the object will be created
		 * @param array $params Array of data for the constructor of the $object
		 *
		 * @return Object Return an object of $object type
		 */
		private function factory($object, $params = null) {
			$class = "SRC\\Models\\$object";
			/** @type IModels $class */
			$instance = $class::init($params);
			if(strtolower(self::$mode) !== 'select') {
				$logger = Logger::getLogger($object);
				$logger->info($instance);
				unset($logger);
			}

			return $instance;
		}

		/**
		 * Creates a INSERT sql
		 *
		 * @access public
		 *
		 * @param string $tabela Table name
		 * @param array $args An array of column => value
		 * @param boolean $pdo Use PDO binding system?
		 *
		 * @return string Return the created sql
		 */
		public function createInsert($tabela, $args, $pdo = true) {
			$sql = "INSERT INTO {$tabela}";
			$sql .= '(`' . implode('`,`', array_keys($args)) . '`)';
			$sql .= ' VALUES ';
			if($pdo) {
				$sql .= '(:' . implode(',:', array_keys($args)) . ');';
			} else {
				$sql .= "('" . implode("','", array_values($args)) . "');";
			}

			$this->handleCreate($tabela, ($pdo) ? $args : [], $sql);

			return $sql;
		}

		/**
		 * Just a global logger to avoid code duplicate
		 *
		 * @param string $table Table name
		 * @param array $args Array with parameters of $sql
		 * @param string $sql SQL statement
		 */
		protected function handleCreate($table, $args, $sql) {
			self::$mode = substr($sql, 0, 6);
			$this->setCallback($table);
			$this->setParam($this->sanitizeParam($args));
			self::$log->info(sprintf("Statement [%s] created", self::$mode));
			self::$log->debug($sql);
		}

		/**
		 * Set the class callback
		 *
		 * @access public
		 *
		 * @param string $callback Class name to cast
		 *
		 * @return DBManager Return this object
		 */
		public function setCallback($callback) {
			self::$callback = (!is_null($callback) ? ucwords($callback) : null);

			return $this;
		}

		/**
		 * Set DBManager::$param and log it
		 *
		 * @access private
		 *
		 * @param array|object $param
		 *
		 * @return void
		 */
		private function setParam($param) {
			self::$param = $param;
			if(!empty($param)) {
				self::$log->debug($param);
			}
		}

		/**
		 * Filter parameters of statement
		 *
		 * @access private
		 *
		 * @param string|int|array $data The parameters to filter
		 *
		 * @return array|int|string The filtered $data
		 */
		private function sanitizeParam($data) {
			if(is_array($data)) {
				foreach($data as $key => $value) {
					if(is_array($value)) {
						$data[$key] = $this->sanitizeParam($value);
					} else {
						$data[$key] = trim($value);
					}
				}
			}

			return $data;
		}

		/**
		 * Creates a UPDATE sql
		 *
		 * @access public
		 *
		 * @param string $tabela Table name
		 * @param array $updates An array of columns to be updated column => value
		 * @param array $cond Where clause column => value
		 *
		 * @return string Return the created sql
		 */
		public function createUpdate($tabela, $updates, $cond) {
			$arr = [];
			$arrc = [];
			$sql = "UPDATE {$tabela} SET";
			foreach($updates as $col => $value) {
				$arr[] = " {$col} = :{$col}";
			}
			$sql .= implode(', ', $arr) . " WHERE";
			$paramsWhere = [];
			foreach($cond as $col => $param) {
				foreach($param as $logical => $matches) {
					$w = [];
					foreach($matches as $value => $operator) {
						$w[] = " $col {$this->doSprintf($logical, $col)} {$operator}";
						$paramsWhere[$col] = $this->doSprintf($logical, $value, false);
					}
					$sql .= implode('', $w);
				}
			}
			$sql .= implode(' AND ', $arrc) . ';';
			$params = array_merge($updates, $paramsWhere);

			$this->handleCreate($tabela, $params, $sql);

			return $sql;
		}

		/**
		 * Formats a tring in order ot use in SQL statement OR params to bind the sql
		 *
		 * @param string $logical DBManager::COL_{type}
		 * @param string $col Column name
		 * @param boolean $create Will be used in SQL statement creating?
		 *
		 * @return string Return the formatted string
		 */
		protected function doSprintf($logical, $col, $create = true) {
			$fmtd = sprintf($logical, $col);
			preg_match("~'(.*?)'~", $fmtd, $display);
			$string = $display[1];
			if($create) {
				$logic = preg_replace("/\'[^)]+\'/", "", $logical);
				$string = "{$logic} :{$col}";
			}

			return $string;
		}

		/**
		 * Creates a SELECT sql
		 *
		 * @access public
		 *
		 * @param string $tabela Table name
		 * @param array $columns Columns to retrieve
		 * @param array $where Where columns params array('column_name' => array(DBManager::COL_OPERATOR =>
		 *     array('column_value' => 'and|or'))
		 * @param array $order An array containing order structure (fields => 'columns names', order => 'asc|desc')
		 *
		 * @return string Return the created sql
		 */
		public function createSelect($tabela, $columns, $where = [], $order = []) {
			$sql = "SELECT ";
			$arr = [];
			$protected = ['*', 'NOW()'];
			foreach($columns as $col) {
				if(in_array($col, $protected)) {
					$arr[] = $col;
					break;
				}
				$arr[] = $col;
			}
			if(empty($arr)) {
				$sql .= '*';
			} else {
				$sql .= implode(', ', $arr);
			}
			$sql .= " FROM {$tabela} ";
			$params = [];
			if(!empty($where)) {
				$sql .= " WHERE ";
				foreach($where as $col => $param) {
					foreach($param as $logical => $matches) {
						$w = [];
						foreach($matches as $value => $operator) {
							$w[] = " $col {$this->doSprintf($logical, $col)} {$operator}";
							$params[$col] = $this->doSprintf($logical, $value, false);
						}
						$sql .= implode('', $w);
					}
				}
			}
			if(!empty($order)) {
				$string = implode(',', $order['fields']);
				$sql .= "ORDER BY {$string} {$order['order']}";
			}
			$sql .= ';';

			$this->handleCreate($tabela, $params, $sql);

			return $sql;
		}

		/**
		 * Creates a DELETE sql
		 *
		 * @access public
		 *
		 * @param string $tabela Table name
		 * @param array $where Where clause params array('column_name' => array(DBManager::COL_OPERATOR =>
		 *     array('column_value' => 'and|or'))
		 *
		 * @return string Return the created sql
		 */
		public function createDelete($tabela, $where) {
			$sql = 'DELETE FROM ' . $tabela;

			$params = [];
			if(!empty($where)) {
				$sql .= " WHERE ";
				foreach($where as $col => $param) {
					foreach($param as $logical => $matches) {
						$w = [];
						foreach($matches as $value => $operator) {
							$w[] = " $col {$this->doSprintf($logical, $col)} {$operator}";
							$params[$col] = $this->doSprintf($logical, $value, false);
						}
						$sql .= implode('', $w);
					}
				}
			}
			$sql .= ';';

			$this->handleCreate($tabela, $params, $sql);

			return $sql;
		}

		/**
		 * Returns a collection of results selected from database
		 *
		 * @access public
		 *
		 * @param string $sql The [select] string
		 * @param boolean $unique Wants only one row?
		 * @param string $callback Override default callback
		 *
		 * @return array The collection base ond $sql
		 */
		public function select($sql, $unique = false, $callback = null) {
			$return = [];
			if(!self::isInitialized()) {
				self::doException(new Exception("Database connection not established", 0, null), $sql);
			} else {
				try {
					self::$connection->beginTransaction();
					$st = self::$connection->prepare($sql);
					(!empty(self::$param)) ? $st->execute($this->getParam()) : $st->execute();
					$result = $st->fetchAll(PDO::FETCH_OBJ);
					self::$connection->commit();

					if(!is_null($callback)) {
						$this->setCallback($callback);
					}

					if(!empty($result)) {
						foreach($result as $object) {
							if($unique) {
								$return = $this->factory($this->getCallback(), $object);
							} else {
								$return[] = $this->factory($this->getCallback(), $object);
							}
						}
					}

					return $return;
				} catch(Exception $e) {
					self::$connection->rollBack();
					self::doException($e, $sql);
				}
			}

			return $return;
		}

		/**
		 * Get the connection opened status
		 *
		 * @return bool Return if connection was established
		 */
		private static function isInitialized() {
			return self::$initialized;
		}

		/**
		 * Return the sql params
		 *
		 * @return array sql params
		 */
		public function getParam() {
			return self::$param;
		}

		/**
		 * Return the callback
		 *
		 * @access public
		 * @return IModels The class callback
		 */
		public function getCallback() {
			return self::$callback;
		}

		/**
		 * Execute a query on database
		 *
		 * @access public
		 *
		 * @param string $sql SQL to be executed
		 * @param string $col Col id to return the last insert
		 *
		 * @return boolean True if query was executed successfuly, false otherwise
		 */
		public function query($sql, $col = null) {
			$result = false;
			if(!self::isInitialized()) {
				$e = new Exception("Database connection not established", 0, null);
				self::doException($e, $sql);
			} else {
				try {
					$this->startTransaction();
					$st = self::$connection->prepare($sql);
					(!empty(self::$param)) ? $st->execute($this->getParam()) : $st->execute();
					$lastCod = null;
					if(!is_null($col)) {
						$lastCod = self::$connection->lastInsertId($col);
					}

					$commit = $this->sendCommit($sql);
					$this->finalLog($commit);
					$result = (is_null($col) ? $commit : $lastCod);
				} catch(Exception $e) {
					$this->revert($e, $sql);
				}
			}

			return $result;
		}

		/**
		 * Starts a transaction
		 *
		 * @access private
		 * @return void
		 */
		private function startTransaction() {
			$exception = null;
			if(is_null(self::$connection)) {
				$exception = new Exception("Database connection is inactive", 0, null);
				self::doException($exception);
			} else if(self::$connection->inTransaction()) {
				$exception = new Exception("There is already an active transaction", 0, null);
				self::doException($exception);
			} else {
				self::$connection->beginTransaction();
				self::$log->info("Transaction started");
			}
		}

		/**
		 * Commit a statement
		 *
		 * @access private
		 *
		 * @param string $sql The SQL
		 *
		 * @return bool Return commit result
		 */
		private function sendCommit($sql) {
			$sqltr = trim($sql);
			$this->setParam([]);
			$commit = self::$connection->commit();
			self::$log->info("Query commited " . ($commit ? 'successfuly' : 'with errors'));
			self::$log->info("Transaction finished");
			if(is_null(self::$mode)) {
				self::$mode = substr($sqltr, 0, 6);
				self::$log->debug($sqltr);
			}
			self::$log->info(sprintf("Statement [%s] closed", self::$mode));

			return $commit;
		}

		/**
		 * Log the statement [mixed] result
		 *
		 * @access private
		 *
		 * @param array|string|object $return The statement result
		 *
		 * @return array|string|object
		 */
		private function finalLog($return) {
			$this->setCallback(null);
			$this->setParam([]);
			self::$mode = null;
			self::$collection = $return;
			$ht = '';
			for($i = 0; $i <= 40; $i++) {
				$ht .= '#';
			}
			self::$log->info($ht);

			return $return;
		}

		/**
		 * Roll back the data
		 *
		 * @access private
		 *
		 * @param Exception $e The eexception
		 * @param string $sql The SQL statement
		 *
		 * @return bool Result rollback result
		 */
		private function revert(Exception $e, $sql = "") {
			$this->setCallback(null);
			$this->setParam([]);
			self::$mode = null;
			self::$collection = [];
			$rollback = self::$connection->rollBack();
			self::$log->warn("Transaction finished but nothing changed. All data rolled back.");
			self::doException($e, $sql);

			return $rollback;
		}

		/**
		 * Execute a query on database
		 *
		 * @access public
		 *
		 * @param string $sql SQL to be executed
		 * @param string $col Col id to return the last insert
		 *
		 * @return boolean True if query was executed successfuly, false otherwise
		 */
		public function squery($sql, $col = null) {
			try {
				self::$connection->beginTransaction();
				$st = self::$connection->prepare($sql);
				$st->execute();
				$lastCod = null;
				if(!is_null($col)) {
					$lastCod = self::$connection->lastInsertId($col);
				}
				$commit = self::$connection->commit();

				return (is_null($col) ? $commit : $lastCod);
			} catch(Exception $e) {
				self::$connection->rollBack();
				self::doException($e, $sql);
			}

			return false;
		}

		/**
		 * The DBManager::$collection size
		 *
		 * @access public
		 * @return int
		 */
		public function getSize() {
			return self::$size;
		}

		/**
		 * Executa um Select no banco de dados
		 *
		 * @param String $sql SQL com comando SELECT
		 *
		 * @throws Exception
		 * @return Array Se a SQL for executada corretamente, retorna um objeto/array com os valores no formato JS
		 *     DataTable
		 */
		protected function dataTableSelect($sql) {
			$return = [];
			if(!self::isInitialized()) {
				self::doException(new Exception("Database connection not established", 0, null), $sql);
			} else {
				try {
					self::$connection->beginTransaction();
					$st = self::$connection->prepare($sql);
					(!empty(self::$param)) ? $st->execute($this->getParam()) : $st->execute();
					$this->setSize($st->rowCount());
					$result = $st->fetchAll(PDO::FETCH_NUM);
					self::$connection->commit();

					$return = $result;
				} catch(Exception $e) {
					self::$connection->rollBack();
					self::doException($e, $sql);
				}
			}

			return $return;
		}

		/**
		 * Set DBManager::$collection size
		 *
		 * @access public
		 *
		 * @param $size
		 *
		 * @return DBManager Return this object
		 */
		public function setSize($size) {
			self::$size = $size;

			return $this;
		}

		/**
		 * Method unserialize private type to prevent cloning instance of this class.
		 *
		 * @access private
		 * @return void
		 */
		private function __clone() {
			self::$log->info("Database connection was cloned");
		}

	}

}