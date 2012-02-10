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
class Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_Remote_JsonController extends Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_Remote_XmlController {

	
	/**
	 * the remote action called via AJAX
	 *
     * @return string
	 */
	public function remoteAction() {
		
		try {
		
			if(empty($this->widgetConfiguration)) {
				throw new RuntimeException('Could not find configuration for this key.');
			}
		
			if(!$this->widgetConfiguration['feedUrl']) {
	    		throw new InvalidArgumentException('feedUrl is not configured.');
	    	}
	    	$feedUrl = $this->widgetConfiguration['feedUrl'];
	    	$cacheTime = $this->widgetConfiguration['cacheTime'];
	    	
	    	if(!empty($this->widgetConfiguration['templatePathAndName'])) {
	    		$this->view->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($this->widgetConfiguration['templatePathAndName']));
	    	}
    		
	    	/**
	    	 * some lazy fetching feed
	    	 * 
	    	 * it just does its time-consuming work when it is actually needed
	    	 * 
	    	 * @var Tx_T3orgFeedparser_Domain_Model_LazyJson
	    	 */
	    	$feed = new Tx_T3orgFeedparser_Domain_Model_LazyJson();
	    	$feed->setFeedUrl($feedUrl);
	    	$feed->setCacheTime($cacheTime);
	    	
    		$this->view->assign('feed', $feed);

            $this->setCachingHeaders();
    		
			if($this->widgetConfiguration['arguments'] && is_array($this->widgetConfiguration['arguments'])) {
    			foreach($this->widgetConfiguration['arguments'] as $argkey=>$value) {
    				$this->view->assign($argkey,$value);
    			}
    		}
	    	
    		return $this->view->render();
	    	
    	} catch (Exception $e) {
    		t3lib_div::sysLog($e->getMessage(), 't3org_feedparser', LOG_ERR);
    		$this->view->assign('error', $e->getMessage());
    	}
	}


    /**
     * set individual cache times for each request on TYPO3 response
     */
    protected function setCachingHeaders() {
        $cacheTime = $this->widgetConfiguration['cacheTime'];
        $GLOBALS['TSFE']->set_cache_timeout_default($cacheTime);
    }

}

?>