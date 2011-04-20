<?php
/**
 * a lazy Json Feed
 * 
 * this is used to allow the Remoteable Widget to work
 * (fetching external feeds is quite costy)
 * 
 * @author Christian Zenker <christian.zenker@599media.de>
 */
class Tx_T3orgFeedparser_Domain_Model_LazyJson implements IteratorAggregate {

	/**
	 * @var string the feedUrl
	 */
	protected $feedUrl = null;
	
	/**
	 * @var array
	 */
	protected $data = null;
	
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
		if(is_null($this->data)) {
			$this->fetchData();
		}
		if (strncmp($functionName, 'get', 3) === 0) {
			$propertyName = t3lib_div::underscoredToLowerCamelCase(substr($functionName, 3));
			
			return $this->data[$propertyName];
		}
	}
	
	/**
	 * initialize the real object 
	 * 
	 * (aka. Don't be lazy anymore)
	 */
	protected function fetchData() {
		
		if(is_null($this->feedUrl)) {
			throw new LogicException('There was no feedUrl set.');
		}
		
		$this->data = t3lib_div::getUrl(
    		$this->feedUrl,
    		0,
    		/* forge.typo3.org will just refuse connection (403) if
    		 * the user agent is empty
    		 */ 
    		array('User-Agent: typo3.org/FeedParser')
    	);
    	
    	if(empty($this->data)) {
    		$this->data = null;
    		//if: empty return or false (=exception)
    		throw new RuntimeException(sprintf(
    			'The url "%s" could not be fetched.',
    			$this->feedUrl
    		));
    	}
    	
    	$this->data = json_decode($this->data, true);
    	
		if(empty($this->data)) {
    		$this->data = null;
    		//if: empty return or false (=exception)
    		throw new RuntimeException(sprintf(
    			'The url "%s" did not return a json object.',
    			$this->feedUrl
    		));
    	}
	}
	
	public function getIterator () {
		if(is_null($this->data)) {
			$this->fetchData();
		}
		return new ArrayObject($this->data);
	}
	
}
?>