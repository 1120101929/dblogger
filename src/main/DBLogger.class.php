<?php

/**
 * Class DBLogger - Simple class to handle a logger if apache/logger is
 * initialized or not (if don't, use basic logger).
 */
namespace SRC\Manager {
	use Exception;
	use Logger;

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
	class DBLogger {

		/**
		 * Define if is custom logger
		 *
		 * @type null|resource
		 */
		private static $isCustom = null;

		/**
		 * The logger
		 *
		 * @type null|\SRC\Manager\DBLogger
		 */
		protected $dbLog = null;

		/**
		 * Constructor.
		 *
		 * @param string $name Name of the logger.
		 */
		private function __construct($name) {
			if(class_exists('Logger') && (!is_null(self::$isCustom))) {
				$this->dbLog = Logger::getLogger($name);
			} else {
				$this->dbLog = $this;
			}

			return $this;
		}

		/**
		 * Returns a DBLogger by name.
		 *
		 * @param string $name The logger name
		 *
		 * @param null $custom
		 *
		 * @return DBLogger
		 */
		public static function init($name, $custom = null) {
			self::$isCustom = $custom;

			return new DBLogger($name);
		}

		/**
		 * Log a message object with the TRACE level.
		 *
		 * @param mixed $message message
		 * @param Exception $throwable Optional throwable information to include
		 *   in the logging event.
		 */
		public function trace($message, $throwable = null) {
			if($this->dbLog instanceof Logger && (!is_null(self::$isCustom))) {
				$this->dbLog->trace($message, $throwable);
			}
		}

		/**
		 * Log a message object with the DEBUG level.
		 *
		 * @param mixed $message message
		 * @param Exception $throwable Optional throwable information to include
		 *   in the logging event.
		 */
		public function debug($message, $throwable = null) {
			if($this->dbLog instanceof Logger && (!is_null(self::$isCustom))) {
				$this->dbLog->debug($message, $throwable);
			}
		}

		/**
		 * Log a message object with the INFO Level.
		 *
		 * @param mixed $message message
		 * @param Exception $throwable Optional throwable information to include
		 *   in the logging event.
		 */
		public function info($message, $throwable = null) {
			if($this->dbLog instanceof Logger && (!is_null(self::$isCustom))) {
				$this->dbLog->info($message, $throwable);
			}
		}

		/**
		 * Log a message with the WARN level.
		 *
		 * @param mixed $message message
		 * @param Exception $throwable Optional throwable information to include
		 *   in the logging event.
		 */
		public function warn($message, $throwable = null) {
			if($this->dbLog instanceof Logger && (!is_null(self::$isCustom))) {
				$this->dbLog->warn($message, $throwable);
			}
		}

		/**
		 * Log a message object with the ERROR level.
		 *
		 * @param mixed $message message
		 * @param Exception $throwable Optional throwable information to include
		 *   in the logging event.
		 */
		public function error($message, $throwable = null) {
			if($this->dbLog instanceof Logger && (!is_null(self::$isCustom))) {
				$this->dbLog->error($message, $throwable);
			}
		}

		/**
		 * Log a message object with the FATAL level.
		 *
		 * @param mixed $message message
		 * @param Exception $throwable Optional throwable information to include
		 *   in the logging event.
		 */
		public function fatal($message, $throwable = null) {
			if($this->dbLog instanceof Logger && (!is_null(self::$isCustom))) {
				$this->dbLog->fatal($message, $throwable);
			}
		}

		/**
		 * Clear the logger
		 *
		 * @return void
		 */
		public function clear() {
			if($this->dbLog instanceof Logger && (!is_null(self::$isCustom))) {
				$this->dbLog->clear();
			}
		}

	}

}