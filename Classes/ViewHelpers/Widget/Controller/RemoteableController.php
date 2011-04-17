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
class Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_RemoteableController extends Tx_Fluid_Core_Widget_AbstractWidgetController {

	/*
	 * widgetConfiguration
	 */

	/**
	 * @param string $containerId
	 * @return void
	 */
	public function indexAction() {
		$key = $this->getHashFromArray($this->widgetConfiguration);
		$this->getRegistry()->set(
			'tx_'.$this->request->getControllerExtensionKey(),
			$key,
			$this->widgetConfiguration
		);
		$this->view->assign('remoteArguments', array('key' => $key));
	}
	
	/**
	 * 
	 * @param string $key
	 */
	public function remoteAction($key) {
		$this->widgetConfiguration = $this->getRegistry()->get(
			'tx_'.$this->request->getControllerExtensionKey(),
			$key
		);
		
		try {
	    	if(!$this->widgetConfiguration['feedUrl']) {
	    		throw new InvalidArgumentException('feedUrl is not configured.');
	    	}
	    	$feedUrl = $this->widgetConfiguration['feedUrl'];
	    	
	    	if(!empty($this->widgetConfiguration['templatePathAndName'])) {
	    		$this->view->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($this->widgetConfiguration['templatePathAndName']));
	    	}
    		
	    	$feed = new Tx_T3orgFeedparser_Domain_Model_LazyFeed();
	    	$feed->setFeedUrl($feedUrl);
	    	
    		$this->view->assign('feed', $feed);
    		$this->view->assign('feedUrl', $feedUrl);
	    	
    	} catch (Exception $e) {
    		t3lib_div::sysLog($e->getMessage(), 't3org_feedparser', LOG_ERR);
    		$this->view->assign('error', $e->getMessage());
    	}
	}
	
	protected $registry = null;
	
	protected function getRegistry() {
		if(is_null($this->registry)) {
			$this->registry = t3lib_div::makeInstance('t3lib_Registry');
		}
		return $this->registry;
	}
	
	/**
	 * create a hash from a given array
	 * 
	 * (no necessarity to be a secure hash - any semi-random value would do)
	 * 
	 * @param array $array
	 * @return string
	 */
	protected function getHashFromArray($array) {
		return md5(serialize($array));
	}
}

?>