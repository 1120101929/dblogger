<?php

/**
 * Class DBManagerException - Handlers custom exception
 */

/**
 * This class extends Exception class to handler custom database exceptions
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
 * @package Database
 * @subpackage Exception
 * @author Victor Mendonca <victor.mendonca@live.com>
 * @copyright copyright (c) copyright 2014
 * @since   October 1, 2014 — Last update October 3, 2014
 * @license http://opensource.org/licenses/gpl-license.php GNU Public Licence (GPL)
 * @version 0.2.0
 * @link http://vmendonca.com.br
 */
class DBManagerException extends Exception {

    /**
     * The default exception message
     * 
     * @var string $message
     */
    protected $message = 'Unknown DBManagerException';     // Exception message

    /**
     * Get the DBManagerException instance
     * 
     * @access public
     * @param string $message Exception message
     * @param int $code Exception error code
     * @param Exception $previous Previous exception
     * @return DBManagerException The instance of DBManagerException
     */

    public static function getInstance($message = null, $code = null, $previous = null) {
        return new DBManagerException($message, $code, $previous);
    }

    /**
     * Create a new object of DBManagerException
     * 
     * @access public
     * @param string $message The message
     * @param int $code The code
     * @param Exception $previous The previous exception
     * @throws DBManagerException
     * @return DBManagerException The instance of DBManagerException
     */
    public function __construct($message = null, $code = null, $previous = null) {
        if (!$message) {
            throw new $this($this->message);
        }
        $this->code = $code;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Convert the object to string when using print methods
     * 
     * @return string
     */
    public function __toString() {
        return get_class($this) . " {$this->message}";
    }

    /**
     * Set the $code
     * 
     * @param string|int $code The new code
     * @return $this
     */
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    /**
     * Set the $statement
     * 
     * @param string $statement The statement
     * @return $this
     */
    public function setStatement($statement) {
        $this->statement = $statement;
        return $this;
    }

}
