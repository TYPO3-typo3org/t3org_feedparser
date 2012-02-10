<?php

/*
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
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
 * This class does not store widget contexts inside a user session 
 * but accessible by everyone.
 * 
 * This is needed when you like to cache the CE that creates the widget.
 * So you should better not use this for sensible data
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_T3orgFeedparser_Fluid_AjaxWidgetContextHolder extends Tx_Fluid_Core_Widget_AjaxWidgetContextHolder implements t3lib_Singleton {
//
//	/**
//	 * An array $ajaxWidgetIdentifier => $widgetContext
//	 * which stores the widget context.
//	 *
//	 * @var array
//	 */
//	protected $widgetContexts = array();
//
	/**
	 * has to start with tx_*
	 * @var string
	 */
	protected $widgetContextsStorageKey = 'tx_T3orgFeedparser_Fluid_AjaxWidgetContextHolder_widgetContexts';
//
//	/**
//	 * Constructor
//	 */
//	public function __construct() {
//		$this->loadWidgetContexts();
//	}
//
//	/**
//	 * Loads the windget contexts from the TYPO3 user session
//	 *
//	 * @return void
//	 */
//	protected function loadWidgetContexts() {
//		if (TYPO3_MODE === 'FE') {
//			$this->widgetContexts = unserialize($GLOBALS['TSFE']->fe_user->getKey('ses', $this->widgetContextsStorageKey));
//		} else {
//			$this->widgetContexts = unserialize($GLOBALS['BE_USER']->uc[$this->widgetContextsStorageKey]);
//			$GLOBALS['BE_USER']->writeUC();
//		}
//	}

	/**
	 * Get the widget context for the given $ajaxWidgetId.
	 *
	 * @param string $ajaxWidgetId
	 * @return Tx_Fluid_Core_Widget_WidgetContext
	 */
	public function get($ajaxWidgetId) {
		$widgetContext = $this->readRegistry($ajaxWidgetId);
		if(is_null($widgetContext)) {
			throw new Tx_Fluid_Core_Widget_Exception_WidgetContextNotFoundException('No widget context was found for the Ajax Widget Identifier "' . $ajaxWidgetId . '". This only happens if AJAX URIs are called without including the widget on a page.', 1284793775);
		}
		return $widgetContext;
	}

	/**
	 * Stores the WidgetContext inside the Context, and sets the
	 * AjaxWidgetIdentifier inside the Widget Context correctly.
	 *
	 * @param Tx_Fluid_Core_Widget_WidgetContext $widgetContext
	 * @return void
	 */
	public function store(Tx_Fluid_Core_Widget_WidgetContext $widgetContext) {
		$ajaxWidgetId = $this->getHashFromWidgetContext($widgetContext);
		$widgetContext->setAjaxWidgetIdentifier($ajaxWidgetId);
		$this->writeRegistry($ajaxWidgetId, $widgetContext);
	}

//	/**
//	 * Persists the widget contexts in the TYPO3 user session
//	 * @return void
//	 */
//	protected function storeWidgetContexts() {
//		if (TYPO3_MODE === 'FE') {
//			$GLOBALS['TSFE']->fe_user->setKey(
//				'ses',
//				$this->widgetContextsStorageKey,
//				serialize($this->widgetContexts)
//			);
//			$GLOBALS['TSFE']->fe_user->storeSessionData();
//		} else {
//			$GLOBALS['BE_USER']->uc[$this->widgetContextsStorageKey] = serialize($this->widgetContexts);
//			$GLOBALS['BE_USER']->writeUc();
//		}
//	}
		
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
     * @return mixed
     */
	protected function readRegistry($key) {
		return $this->getRegistry()->get(
			$this->widgetContextsStorageKey,
			$key
		);
	}
	
	/**
	 * write a value to the registry
	 * @param string $key
	 * @param mixed $content
     * @return string
     */
	protected function writeRegistry($key, $content) {
		$this->getRegistry()->set(
			$this->widgetContextsStorageKey,
			$key,
			$content
		);
		return $key;
	}

    /**
     * create a hash from a given object
     *
     * (no necessarity to be a secure hash - any semi-random value would do)
     *
     * @param Tx_Fluid_Core_Widget_WidgetContext $widgetContext
     * @return string
     */
	protected function getHashFromWidgetContext(Tx_Fluid_Core_Widget_WidgetContext $widgetContext) {
		return md5(serialize($widgetContext));
	}
}

?>