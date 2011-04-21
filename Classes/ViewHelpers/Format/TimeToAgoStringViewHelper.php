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
 * take a given time and render some "... ago" string instead
 * 
 * @author Christian Zenker <christian.zenker@599media.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_T3orgFeedparser_ViewHelpers_Format_TimeToAgoStringViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @param string $time
	 * @return string The formatted value
	 */
	public function render($time = null) {
		if(empty($time)) {
			$time = trim($this->renderChildren());
		}
		
		if(is_string($time)) {
			$time = strtotime($time);
		} elseif(is_numeric($time)) {
			$time = intval($time);
		} else {
			throw new InvalidArgument('time needs to be a string integer.');
		}
		
		$secsAgo = time() - $time;
		
		if($secsAgo <= 5) {
			return 'a few seconds ago';
		} elseif($secsAgo < 60) {
			return sprintf('%d seconds ago', $secsAgo);
		} elseif($secsAgo < 120) {
			return 'a minute ago';
		} elseif($secsAgo < 300) {
			return 'a few minutes ago';
		} elseif($secsAgo < 3600) {
			return sprintf('%d minutes ago', round($secsAgo / 60));
		} elseif($secsAgo < 7200) {
			return 'about an hour ago';
		} elseif($secsAgo < 64800) {
			return sprintf('%d hours ago', round($secsAgo / 3600));
		} elseif($secsAgo < 151200) {
			return 'about one day ago';
		} else {
			return 'a few days ago';
		}
		
	}
}
?>