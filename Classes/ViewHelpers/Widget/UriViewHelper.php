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
 * an enhanced version of Tx_Fluid_ViewHelpers_Widget_UriViewHelper
 *
 * realUrl friendly
 * ================
 * Fluid does not run its constructed urls through realUrl. That's
 * a missing feature in Fluids core. We fix that here
 *
 * additional argument "cacheable"
 * ===============================
 * usually fluid ajax widgets are noncacheable. But if this is set to TRUE
 * the request is done through pagetype 7077 that uses USER (instead
 * of USER_INT)
 *
 * @author Christian Zenker <christian.zenker@599media.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_T3orgFeedparser_ViewHelpers_Widget_UriViewHelper extends Tx_Fluid_ViewHelpers_Widget_UriViewHelper {

    public function initializeArguments() {
        parent::initializeArguments();
        $this->registerArgument('cacheable', 'boolean', 'if this ajax request is cacheable', false, false);
    }
	/**
	 * Get the URI for an AJAX Request.
	 *
	 * @return string the AJAX URI
	 */
	protected function getAjaxUri() {
		$action = $this->arguments['action'];
		$arguments = $this->arguments['arguments'];
        $cacheable = $this->arguments['cacheable'];

		if ($action === NULL) {
			$action = $this->controllerContext->getRequest()->getControllerActionName();
		}
//		$arguments['id'] = $GLOBALS['TSFE']->id;
//		// TODO page type should be configurable
//		$arguments['type'] = 7076;
//		$arguments['fluid-widget-id'] = $this->controllerContext->getRequest()->getWidgetContext()->getAjaxWidgetIdentifier();
//		$arguments['action'] = $action;
//
//        return '?' . http_build_query($arguments, NULL, '&');

        $uriBuilder = $this->controllerContext->getUriBuilder();
        return $uriBuilder
            ->reset()
            ->setTargetPageUid($GLOBALS['TSFE']->id)
            ->setTargetPageType($cacheable ? 7077 : 7076)
            ->setArguments(array(
                'fluid-widget-id' => $this->controllerContext->getRequest()->getWidgetContext()->getAjaxWidgetIdentifier(),
                'action' => $action
            ))
            ->setSection($this->arguments['section'])
            ->setAddQueryString(TRUE)
            ->setArgumentsToBeExcludedFromQueryString(array('cHash'))
            ->setFormat($this->arguments['format'])
            ->build();
	}
}

?>