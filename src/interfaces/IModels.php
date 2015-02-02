<?php
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 31/01/2015
 * Time: 10:10
 */

namespace SRC\Impl {

	/**
	 * Interface IModels
	 *
	 * @access public
	 */
	interface IModels {

		/**
		 * @param array $data values of the object
		 *
		 * @return \IModels
		 */
		public static function init($data);

	}
}