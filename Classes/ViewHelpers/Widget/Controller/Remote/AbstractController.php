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
abstract class Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_Remote_AbstractController extends Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_AbstractController {

    /**
     * the action to place (a usually empty) container to the
     * template to be filled remotely
     *
     * @return void
     */
    public function indexAction() {
		$this->view->assign('settings', $this->widgetConfiguration);
    }

    /**
     * the remote action called via AJAX
     *
     * @return string
     */
    public function remoteAction() {
        $return = $this->main();
        $this->setCachingHeaders();
        return $return;
    }


    /**
     * set individual cache times for each request on TYPO3 response
     */
    protected function setCachingHeaders() {
        $cacheTime = $this->widgetConfiguration['cacheTime'];
        if($this->widgetConfiguration['cacheTime'] > 0 &&
            $GLOBALS['TSFE']->cacheTimeOutDefault > 0 &&
            $this->widgetConfiguration['cacheTime'] < $GLOBALS['TSFE']->cacheTimeOutDefault
        ) {
            $GLOBALS['TSFE']->set_cache_timeout_default($cacheTime);
        }
    }
}

?>