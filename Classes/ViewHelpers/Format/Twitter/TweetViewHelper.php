<?php

/*                                                                        *
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
 * adds links to tweets
 * 
 * @author Christian Zenker <christian.zenker@599media.de>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_T3orgFeedparser_ViewHelpers_Format_Twitter_TweetViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	protected static $twitterSearchBase = 'http://search.twitter.com/search';
	protected static $twitterUserBase = 'http://twitter.com/';
	
	/**
	 * @param string $tweet
	 * @param boolean $linkHashTag
	 * @param boolean $linkUser
	 * @param boolean $linkUrl 
	 * @return string The formatted tweet
	 */
	public function render($tweet = null, $linkHashTag=true, $linkUser = true, $linkUrl = true) {
		if(empty($tweet)) {
			$tweet = trim($this->renderChildren());
		}
		$tweet = htmlspecialchars($tweet);
		
	
		if($linkUrl) {
			$tweet = preg_replace_callback('|http://[^\s]+|', array(get_class($this), 'linkUrl'), $tweet);
		}
		if($linkHashTag) {
			$tweet = preg_replace_callback('|#[^\s]+|', array(get_class($this), 'linkHashTag'), $tweet);
		}
		if($linkUser) {
			$tweet = preg_replace_callback('|@[^\s]+|', array(get_class($this), 'linkUser'), $tweet);
		}
		
		return $tweet;
		
	}
	
	protected function linkHashTag($match) {
		$hashTag = $match[0];
		return sprintf(
			'<a href="%s?q=%s" target="_blank">%s</a>',
			self::$twitterSearchBase,
			rawurlencode($hashTag),
			htmlspecialchars($hashTag)
		);
	}
	
	protected function linkUser($match) {
		$userName = substr($match[0], 1);
		return sprintf(
			'<a href="%s%s" target="_blank">@%s</a>',
			self::$twitterUserBase,
			rawurlencode($userName),
			htmlspecialchars($userName)
		);
	}
	
	protected function linkUrl($match) {
		$url = $match[0];
		return sprintf(
			'<a href="%s" target="_blank">%s</a>',
			htmlspecialchars($url),
			htmlspecialchars($url)
		);
	}
}
?>