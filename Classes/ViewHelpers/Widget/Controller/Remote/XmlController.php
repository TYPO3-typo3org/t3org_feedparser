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
class Tx_T3orgFeedparser_ViewHelpers_Widget_Controller_Remote_XmlController extends Tx_Fluid_Core_Widget_AbstractWidgetController {


	/**
	 * the action to place (a usually empty) container to the 
	 * template to be filled remotely
	 * 
	 * @return void
	 */
	public function indexAction() {
		$key = $this->getHashFromArray($content);
		$this->writeRegistry($key, $this->widgetConfiguration);
		$this->view->assign('remoteArguments', array('key' => $key));
	}
	
	/**
	 * the remote action called via AJAX
	 * 
	 * @param string $key a hashed key to fetch configuration from database
	 */
	public function remoteAction($key) {
		
		try {
			// restore the configuration from the database
			$this->widgetConfiguration = $this->readRegistry($key);
			if($content = $this->readCache()) {
				return $content;
			}
			
			// check if the result was already cached and is still valid
			if(empty($this->widgetConfiguration)) {
				throw new RuntimeException('Could not find configuration for this key.');
			}
		
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
    		
    		return $this->writeCache($key);
	    	
    	} catch (Exception $e) {
    		t3lib_div::sysLog($e->getMessage(), 't3org_feedparser', LOG_ERR);
    		$this->view->assign('error', $e->getMessage());
    	}
	}
	
	/**
	 * the registry handling sys_register
	 * @var t3lib_Registry
	 */
	protected $registry = null;
	
	/**
	 * get the registry handling sys_register
	 * @return t3lib_Registry
	 */
	protected function getRegistry() {
		if(is_null($this->registry)) {
			$this->registry = t3lib_div::makeInstance('t3lib_Registry');
		}
		return $this->registry;
	}
	
	/**
	 * read a value from the registry
	 * @param string $key
	 */
	protected function readRegistry($key) {
		return $this->getRegistry()->get(
			'tx_'.$this->request->getControllerExtensionKey(),
			$key
		);
	}
	
	/**
	 * write a value to the registry
	 * @param string $key
	 * @param mixed $content
	 */
	protected function writeRegistry($key, $content) {
		
		if($content instanceof Tx_Fluid_Core_ViewHelper_Arguments) {
			/* if this is an argument class -> make it a plain array
			 * otherwise its not possible to add values later on (caching)
			 */
			$content = array(
				'feedUrl' => $this->widgetConfiguration['feedUrl'],
				'templatePathAndName' => $this->widgetConfiguration['templatePathAndName'],
				'cacheTime' => $this->widgetConfiguration['cacheTime'],
			);
		}
		
		$this->getRegistry()->set(
			'tx_'.$this->request->getControllerExtensionKey(),
			$key,
			$content
		);
		return $key;
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
	
	/**
	 * read a page from cache
	 * 
	 * @return string|false
	 */
	protected function readCache() {
		if(array_key_exists('cacheContent', $this->widgetConfiguration) && array_key_exists('cacheDiscardAt', $this->widgetConfiguration)) {
			if(intval($this->widgetConfiguration['cacheDiscardAt']) >= time()) {
				return $this->widgetConfiguration['cacheContent'];
			} 
		}
		return false;
	}
	
	/**
	 * store a page to cache
	 * @param string $key
	 * @return the rendered content
	 */
	protected function writeCache($key) {
		$renderContent = $this->view->render();
		$this->widgetConfiguration['cacheContent'] = $renderContent;
		$this->widgetConfiguration['cacheDiscardAt'] = time() + $this->widgetConfiguration['cacheTime'];
		$this->writeRegistry($key, $this->widgetConfiguration);
		return $renderContent;
	}
	
}

?>