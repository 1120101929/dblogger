<?php

/**
 * AccountsLogger class file
 */

/**
 * Log a custom info when a object of Accounts was created (test class)
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
 * @package Accounts
 * @subpackage Logger
 * @author Victor Mendonca <victor.mendonca@live.com>
 * @copyright copyright (c) copyright 2014
 * @since   October 1, 2014 — Last update October 3, 2014
 * @license http://opensource.org/licenses/gpl-license.php GNU Public Licence (GPL)
 * @version 0.2.0
 * @link http://vmendonca.com.br
 */
class AccountsLogger implements LoggerRenderer {

    /**
     * Render a custom message of Accounts logger
     * 
     * @param Accounts $a The Accounts object
     * @return string
     */
    public function render($a) {
        return get_class($a) . " created: {$a->getLogin()} <{$a->getEmail()}> ({$a->getAccessLevel()}, {$a->getVipLevel()})";
    }

}
