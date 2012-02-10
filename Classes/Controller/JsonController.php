<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011 Christian Zenker <christian.zenker@599media.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * The main controller to show items from a json stream
 *
 * @author Christian Zenker <christian.zenker@599media.de>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_T3orgFeedparser_Controller_JsonController extends Tx_T3orgFeedparser_Controller_AbstractController {


    /**
     * get the corresponding feed object
     *
     * @return Tx_T3orgFeedparser_Domain_Model_FeedInterface
     */
    protected function getFeedObject() {
        return new Tx_T3orgFeedparser_Domain_Model_LazyJson();
    }
}

?>