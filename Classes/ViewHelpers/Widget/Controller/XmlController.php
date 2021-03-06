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
 * a widget to display an XML feed inline
 *
 * @author Christian Zenker <christian.zenker@599media.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_XmlController extends Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_AbstractController {

    /**
     * action to render the content of the feed
     *
     * @see Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_AbstractController::main()
     * @return string
     */
    public function indexAction() {
    	return $this->main();
    }

    /**
     * @return Tx_T3orgFeedparser_Domain_Model_LazyFeed
     */
    protected function getFeedObject() {
        return new Tx_T3orgFeedparser_Domain_Model_LazyFeed();
    }
}

?>