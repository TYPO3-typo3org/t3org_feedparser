<?php
/**
 * a lazy version of the Domain_Model_Feed
 * 
 * this is used to allow the Remoteable Widget to work
 * (fetching external feeds is quite costy)
 * 
 * @author Christian Zenker <christian.zenker@599media.de>
 */
class Tx_T3orgFeedparser_Domain_Model_LazyFeed {

	/**
	 * @var string the feedUrl
	 */
	protected $feedUrl = null;
	
	/**
	 * @var Tx_T3orgFeedparser_Domain_Model_Feed
	 */
	protected $object = null;
	
	/**
	 * set the feedUrl
	 * @param string $url
	 */
	public function setFeedUrl($url) {
		$this->feedUrl = $url;
		return $this;
	}
	
	/**
	 * __call()
	 * @param string $methodName
	 * @param arguments $arguments
	 */
	public function __call($methodName, $arguments) {
		if(is_null($this->object)) {
			$this->initObject();
		}
		return call_user_method_array($methodName, $this->object, $arguments);
	}
	
	/**
	 * initialize the real object 
	 * 
	 * (aka. Don't be lazy anymore)
	 */
	protected function initObject() {
		
		if(is_null($this->feedUrl)) {
			throw new LogicException('There was no feedUrl set.');
		}
		
		$feedStr = t3lib_div::getUrl(
    		$this->feedUrl,
    		0,
    		/* forge.typo3.org will just refuse connection (403) if
    		 * the user agent is empty
    		 */ 
    		array('User-Agent: typo3.org/FeedParser')
    	);
    	
    	if(empty($feedStr)) {
    		//if: empty return or false (=exception)
    		throw new RuntimeException(sprintf(
    			'The url "%s" could not be fetched.',
    			$this->feedUrl
    		));
    	}
    	
    	$this->object = new Tx_T3orgFeedparser_Domain_Model_Feed($feedStr, LIBXML_NOCDATA);
	}
	
}
?>