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
 * The posts controller for the Blog package
 *
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_Maxfeed_Controller_FeedController extends Tx_Extbase_MVC_Controller_ActionController {

    /**
     * @var array
     */
    protected $flexFormConfiguration;

    /**
     * Initializes the current action
     *
     * @return void
     */
    public function initializeAction() {
        $cObjData = $this->request->getContentObjectData();
        $this->flexFormConfiguration = Tx_Maxskel_Utility_FlexForm::convertFlexFormToArray($cObjData['pi_flexform']);
    }

    /**
     * List action for this controller. Displays latest posts
     *
     * @return string
     */
    public function showAction() {
    	$this->view->assign('cObjData', $this->request->getContentObjectData());
    	$this->view->assign('configuration', $this->flexFormConfiguration);
    	$feedStr = t3lib_div::getUrl($this->flexFormConfiguration['feedUrl']);
    	
    	try{
    		$sxe = new Tx_Maxfeed_SimpleXML_Element($feedStr, LIBXML_NOCDATA);
    		//die(print_r($sxe,true));
    		$this->view->assign('feed', $sxe);
    	} catch(Exception $e){
    		return $e->getMessage();
    	}
    }

}

?>