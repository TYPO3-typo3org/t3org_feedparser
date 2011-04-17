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
 * The main controller to show items from a feed
 *
 * @author Arno Schoon
 * @author Christian Zenker <christian.zenker@599media.de>
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_T3orgFeedparser_Controller_FeedController extends Tx_Extbase_MVC_Controller_ActionController {

    /**
     * Show teasers from an rss feed
     *
     * assigned to template:
     * =====================
     *  * feed - Tx_T3orgT3blogrefviewer_Domain_Model_Feed - a representation of the feed
     *  * error - contains an error message if something went wrong when trying to create the feed object
     *  
     * @return string
     */
    public function teaserAction() {
    	try {
	    	if(!$this->settings['feedUrl']) {
	    		throw new InvalidArgumentException('feedUrl is not configured.');
	    	}
	    	$feedUrl = $this->settings['feedUrl'];
	    	
	    	if(!empty($this->settings['templatePathAndName'])) {
	    		$this->view->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($this->settings['templatePathAndName']));
	    	}
	    	
	    	$feedStr = t3lib_div::getUrl($feedUrl);
	    	
	    	if(empty($feedStr)) {
	    		//if: empty return or false (=exception)
	    		throw new RuntimeException(sprintf(
	    			'The url "%s" could not be fetched.',
	    			$feedUrl
	    		));
	    	}
	    	
	    	//throws errors on its own
    		$feed = new Tx_T3orgFeedparser_Domain_Model_Feed($feedStr, LIBXML_NOCDATA);
    		
    		$this->view->assign('feed', $feed);
	    	
    	} catch (Exception $e) {
    		t3lib_div::sysLog($e->getMessage(), 't3org_t3blogrefviewer', LOG_ERR);
    		$this->view->assign('error', $e->getMessage());
    	}
    }
	
}

?>