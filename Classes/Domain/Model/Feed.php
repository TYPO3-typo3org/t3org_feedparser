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
 * model representing an RSS feed
 * 
 * usage:
 * ======
 * This class is very closely related to the structure of the RSS.
 * The basics are pretty simple:
 * 
 * Assume this simple rss feed
 * <code>
 * 	<?xml version="1.0" encoding="utf-8"?>
 *  <rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
 *  <channel>
 *    <title>T3BLOG References RSS-feed</title>
 *    <description>Latest TYPO3 references of T3BLOG</description>
 *    <image>
 *      <url>http://www.example.org/typo3conf/ext/t3blog/icons/rss.png</url>
 *    </image>
 *    <item>
 *      <title>"42" is not an answer</title>
 *      <description></description>
 *    </item>
 *    <item>
 *      <title>T3O rocks</title>
 *    </item>
 *  </channel>
 *  </rss>
 * </code>
 * 
 * You might use these variables
 * <code>
 *   {feed.channel.title}
 *   {feed.channel.image.url}
 *   <f:for each="{feed.channel.item}" as=item">
 *   	<h3>{item.title}</h3>
 *   </f:for>
 * </code>
 * 
 * namespaces:
 * ===========
 * 
 * When using namespaces use this syntax:
 * <code>
 *   <item>
 *     <title>T3O rocks</title>
 *     <content:encoded><![CDATA[It sure does!]]></content:encoded>
 *   </item>
 * </code>
 * <code>
 * 	{item.xmlns-content.encoded}
 * </code>
 * 
 *
 * @author Arno Schoon
 * @author Christian Zenker <christian.zenker@599media.de>
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_T3orgFeedparser_Domain_Model_Feed extends SimpleXMLElement {
	
	/**
	 * do some magic and implement all getters
	 * 
	 * @param string $functionName function name
	 * @param array $arguments arguments
	 */
	public function __call($functionName, $arguments) {
		if (strncmp($functionName, 'get', 3) === 0) {
			$propertyName = t3lib_div::underscoredToLowerCamelCase(substr($functionName, 3));
			
			if(strncmp($propertyName, 'xmlns-', 6) === 0){
				return $this->children(substr($propertyName, 6), true);
			} elseif(strncmp($propertyName, 'attribute-', 10) === 0) {
				$attributes = $this->attributes();
				$attributeName = t3lib_div::underscoredToLowerCamelCase(substr($propertyName, 10));
				return $attributes->$attributeName;
			}
			
			return $this->$propertyName;
		}
	}
	
	/**
	 * __toString()
	 * @return string
	 */
	public function  __toString(){
		return strval($this);
	}

}

?>