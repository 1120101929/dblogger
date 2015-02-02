<?php

/**
 * Accounts class file
 */
namespace SRC\Models {

	use SRC\Impl\IModels;
	use SRC\Loggers\AccountsLogger;

	/**
	 * The accounts object (test class)
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
	 * @package src
	 * @subpackage Accounts
	 * @author Victor Mendonca <victor.mendonca@live.com>
	 * @copyright copyright (c) copyright 2014
	 * @since   October 1, 2014 — Last update October 3, 2014
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public Licence (GPL)
	 * @version 0.2.0
	 * @link http://vmendonca.com.br
	 */
	class Accounts extends AccountsLogger implements IModels {

		/**
		 * Account login
		 *
		 * @var string $login
		 */
		private $login;

		/**
		 * Account email
		 *
		 * @var string $email
		 */
		private $email;

		/**
		 * Account access level
		 *
		 * @var int $access_level
		 */
		private $access_level;

		/**
		 * Account vip level
		 *
		 * @var int $vip_level
		 */
		private $vip_level;

		/**
		 * Create new object Accounts
		 *
		 * @access public
		 *
		 * @param array|Object $data Object containing data
		 *
		 */
		public function __construct($data = null) {
			if(!empty($data) && !is_null($data)) {
				foreach($data as $key => $val) {
					$this->$key = $val;
				}
			}

			return $this;
		}

		/**
		 * @param array $data values of the object
		 *
		 * @return Accounts
		 */
		public static function init($data) {
			return new Accounts($data);
		}

		/**
		 * Get the account login
		 *
		 * @access public
		 * @return string Return the account login
		 */
		public function getLogin() {
			return $this->login;
		}

		/**
		 * Set the account login
		 *
		 * @access public
		 *
		 * @param string $login The new login
		 *
		 * @return Accounts Return this object
		 */
		public function setLogin($login) {
			$this->login = $login;

			return $this;
		}

		/**
		 * Get the account email
		 *
		 * @access public
		 * @return string Return the account email
		 */
		public function getEmail() {
			return $this->email;
		}

		/**
		 * Set the account email
		 *
		 * @access public
		 *
		 * @param string $email The new email
		 *
		 * @return Accounts Return this object
		 */
		public function setEmail($email) {
			$this->email = $email;

			return $this;
		}

		/**
		 * Get the access level from account
		 *
		 * @access public
		 * @return string Return the access level from account
		 */
		public function getAccessLevel() {
			return $this->access_level;
		}

		/**
		 * Set the access level from account
		 *
		 * @access public
		 *
		 * @param int $access_level The new access level (unsigned)
		 *
		 * @return Accounts Return this object
		 */
		public function setAccessLevel($access_level) {
			$this->access_level = $access_level;

			return $this;
		}

		/**
		 * Get the vip level from account
		 *
		 * @access public
		 * @return string Return the vip level from account
		 */
		public function getVipLevel() {
			return $this->vip_level;
		}

		/**
		 * Set the vip level from account
		 *
		 * @access public
		 *
		 * @param int $vip_level The new vip level
		 *
		 * @return Accounts Return this object
		 */
		public function setVipLevel($vip_level) {
			$this->vip_level = $vip_level;

			return $this;
		}

	}

}