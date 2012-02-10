<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010 MaxServ B.V. - Arno Schoon <arno@maxserv.nl>
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
 * an abstract controller
 *
 * @author Christian Zenker <christian.zenker@599media.de>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
abstract class Tx_T3orgFeedparser_Controller_AbstractController extends Tx_Extbase_MVC_Controller_ActionController {

    /**
     * the main action to show contents of a feed
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function teaserAction() {
    	try {
	    	if(!$this->settings['feedUrl']) {
	    		throw new InvalidArgumentException('feedUrl is not configured.');
	    	}

	    	$feedUrl = $this->settings['feedUrl'];
	    	$cacheTime = intval($this->settings['cacheTime']);
	    	
	    	if(!empty($this->settings['templatePathAndName'])) {
	    		$this->view->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($this->settings['templatePathAndName']));
	    	}

            $feed = $this->getFeedObject();
	    	$feed->setFeedUrl($feedUrl);
	    	$feed->setCacheTime($cacheTime);
	    	
    		$this->view->assign('feed', $feed);
	    	$this->view->assign('feedUrl', $feedUrl);
	    	$this->view->assign('cacheTime', $cacheTime);
	    	
	    	// call explicitly to fetch exceptions thrown by Domain_Model_LazyFeed
	    	return $this->view->render();
    		
    	} catch (Exception $e) {
    		t3lib_div::sysLog($e->getMessage(), 't3org_feedparser', LOG_ERR);
    		$this->view->assign('error', $e->getMessage());
    	}
    }

    /**
     * get the corresponding feed object
     *
     * @abstract
     * @return Tx_T3orgFeedparser_Domain_Model_FeedInterface
     */
    abstract protected function getFeedObject();
	
}

?>