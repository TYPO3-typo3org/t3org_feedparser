<?php

/***************************************************************
 * Copyright notice
 *
 * (c) 2010 MaxServ B.V. - Arno Schoon <arno@maxserv.nl>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * The posts controller for the Blog package
 *
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_Maxfeed_SimpleXML_Element extends SimpleXMLElement {
	
	public function __call($x, $y) {
		if (strpos ( $x, 'get' ) !== false) {
			$p = substr ( $x, 3 );
			$p = strtolower ( substr ( $p, 0, 1 ) ) . substr ( $p, 1 );
			
			if(strpos($p, 'xmlns-') !== false){
				return $this->children(substr($p, 6), true);
			}
			
			switch(strtolower($p)) {
				case "item":
					$o = array ();
					foreach ( $this->$p as $i ) {
						$o [] = $i;
					}
					break;
				case "encoded": // deprecated since prefixing with "xmlns-[namespace]" does the trick 
					$o = $this->$p;
					$ns_dc = $this->children('http://purl.org/rss/1.0/modules/content/');
					//var_dump($ns_dc);
					$o = $ns_dc->encoded;
					break;				
				default:
					$o = $this->$p;
					break;
			}
			return $o;
		}
	}
	
	public function  __toString(){
		return strval($this);
	}

}

?>