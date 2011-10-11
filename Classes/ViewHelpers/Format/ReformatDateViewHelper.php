<?php

/*                                                                        *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * take a string strtotime understand and return it formated somehow else
 * 
 * @author Christian Zenker <christian.zenker@599media.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_T3orgFeedparser_ViewHelpers_Format_ReformatDateViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @param string $time
	 * @param string $format
	 * @return string The formatted value
	 */
	public function render($time = null, $format = '%Y-%m-%d') {
		if(empty($time)) {
			$time = trim($this->renderChildren());
		}
		
		if(is_object($time) && !$time instanceof DateTime) {
			$time = (string) $time;
		}
		
		if(is_string($time)) {
			$time = strtotime($time);
		} elseif(is_numeric($time)) {
			$time = intval($time);
		} else {
			throw new InvalidArgumentException('time needs to be a string integer.');
		}
		
		return strftime($format, $time);
	}
}
?>