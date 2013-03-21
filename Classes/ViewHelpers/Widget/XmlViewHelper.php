<?php

/*                                                                        *
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
 * 
 * @author Christian Zenker <christian.zenker@599media.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_T3orgFeedparser_ViewHelpers_Widget_XmlViewHelper extends Tx_Fluid_Core_Widget_AbstractWidgetViewHelper {

	/**
	 * @var Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_XmlController
	 */
	protected $controller;

	/**
	 * @param Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_XmlController $controller
	 * @return void
	 */
	public function injectController(Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_XmlController $controller) {
		$this->controller = $controller;
	}

	/**
	 * @param string $templatePathAndName
	 * @param string $feedUrl
	 * @param integer $cacheTime
	 * @param string $oAuthBearerToken
	 * @return string
	 */
	public function render($templatePathAndName = '', $feedUrl = '', $cacheTime = 0, $oAuthBearerToken = '') {
		return $this->initiateSubRequest();
	}
}

?>
