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
 * a controller to help generating a bearer token for Twitter
 *
 * @author Christian Zenker <christian.zenker@599media.de>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_T3orgFeedparser_Controller_OauthBackendAdminController extends Tx_Extbase_MVC_Controller_ActionController {

    public function indexAction() {
        $key = t3lib_div::_GP('key');
        $secret = t3lib_div::_GP('secret');

        if($key && $secret) {
            try {
                $this->view->assign('bearerToken', $this->getBearerToken($key, $secret));
            } catch(Tx_T3orgFeedparser_OAuth_TwitterApiException $e) {
                $this->view->assign('bearerTokenError', $e->getMessage());
            }
        }
    }

    protected function getBearerToken($key, $secret) {
        $twitterBearerTokenGenerator = $this->objectManager->get('Tx_T3orgFeedparser_OAuth_TwitterBearerTokenGenerator');
        return $twitterBearerTokenGenerator->getBearerToken($key, $secret);
    }
}

?>