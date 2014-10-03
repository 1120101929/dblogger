<?php

/**
 * Accounts class file
 */

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
class Accounts {

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
     * Get the account login
     * 
     * @access public
     * @return string Return the account login
     */
    public function getLogin() {
        return $this->login;
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
     * Get the access level from account
     * 
     * @access public
     * @return string Return the access level from account
     */
    public function getAccessLevel() {
        return $this->access_level;
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
     * Set the account login
     * 
     * @access public
     * @param string $login The new login
     * @return \Accounts Return this object
     */
    public function setLogin($login) {
        $this->login = $login;
        return $this;
    }

    /**
     * Set the account email
     * 
     * @access public
     * @param string $email The new email
     * @return \Accounts Return this object
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Set the access level from account
     * 
     * @access public
     * @param int $access_level The new access level (unsigned)
     * @return \Accounts Return this object
     */
    public function setAccessLevel($access_level) {
        $this->access_level = $access_level;
        return $this;
    }

    /**
     * Set the vip level from account
     * 
     * @access public
     * @param int $vip_level The new vip level
     * @return \Accounts Return this object
     */
    public function setVipLevel($vip_level) {
        $this->vip_level = $vip_level;
        return $this;
    }

    /**
     * Create new object Accounts
     * 
     * @access public
     * @param Object $data Object containing data
     * @return \Accounts
     */
    public function __construct($data) {
        $this->login = $data->login;
        $this->email = $data->email;
        $this->access_level = $data->access_level;
        $this->vip_level = $data->vip_level;

        return $this;
    }

}
