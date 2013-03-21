<?php
/**
 * a lazy Json Feed
 * 
 * this is used to allow the Remoteable Widget to work
 * (fetching external feeds is quite costy)
 * 
 * @author Christian Zenker <christian.zenker@599media.de>
 */
class Tx_T3orgFeedparser_Domain_Model_LazyJson implements IteratorAggregate, Tx_T3orgFeedparser_Domain_Model_FeedInterface {

	/**
	 * @var string the feedUrl
	 */
	protected $feedUrl = null;
	
	/**
	 * @var the number of seconds this might be cached internally
	 */
	protected $cacheTime = null;
	
	/**
	 * @var array|null
	 */
	protected $data = null;

	/**
	 * @var array header to send with the request
	 */
	protected $feedHeaders = array();

	public function __construct() {
		$this->addFeedHeader('User-Agent: typo3.org/FeedParser');
	}

	/**
	 * set the feedUrl
	 * @param string $url
     * @return Tx_T3orgFeedparser_Domain_Model_LazyJson
     */
	public function setFeedUrl($url) {
		$this->feedUrl = $url;
		return $this;
	}
	
	/**
	 * set the number of seconds this feeds result might be cached
	 * 
	 * @param integer $seconds
     * @return Tx_T3orgFeedparser_Domain_Model_LazyJson
     */
	public function setCacheTime($seconds) {
		$this->cacheTime = $seconds;
		return $this;
	}
	
	/**
	 * __call()
	 * @param string $methodName
	 * @param array $arguments
	 */
	public function __call($methodName, $arguments) {
		if(is_null($this->data)) {
			$this->fetchData();
		}
		if (strncmp($methodName, 'get', 3) === 0) {
			$propertyName = t3lib_div::underscoredToLowerCamelCase(substr($methodName, 3));
			
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
		
		/**
		 * if the result was fetched from the cache
		 * @var boolean
		 */
		$fromCache = false;
		
		if($this->cacheTime > 0) {
			$feedStr = $this->fetchFromCache();
			$fromCache = true;
		}
		
		if(!$feedStr) {
			$feedStr = $this->fetchFromUrl();
			$fromCache = false;
		}
		
    	if(empty($feedStr)) {
    		//if: empty return or false (=exception)
    		throw new RuntimeException(sprintf(
    			'The url "%s" could not be fetched.',
    			$this->feedUrl
    		));
    	}
    	
    	
		$this->data = json_decode($feedStr, true);
		
		
    	
    	if(empty($this->data)) {
    		$this->data = null;
    		//if: empty return or false (=exception)
    		throw new RuntimeException(sprintf(
    			'The url "%s" could not be fetched.',
    			$this->feedUrl
    		));
    	}
    	
    	
    	
		if(empty($this->data)) {
    		$this->data = null;
    		//if: empty return or false (=exception)
    		throw new RuntimeException(sprintf(
    			'The url "%s" did not return a json object.',
    			$this->feedUrl
    		));
    	}
    	
		if($this->cacheTime > 0 && !$fromCache) {
    		$this->setCache($feedStr);	
    	}
	}
	
	public function getIterator() {
		if(is_null($this->data)) {
			$this->fetchData();
		}
		return new ArrayObject($this->data);
	}
	
	/**
	 * get the data from the given url
	 * 
	 * @return string
	 */
	protected function fetchFromUrl() {

		return t3lib_div::getUrl(
    		$this->feedUrl,
    		0,
    		/* forge.typo3.org will just refuse connection (403) if
    		 * the user agent is empty
    		 */ 
    		$this->feedHeaders
    	);
	}
	
	/**
	 * fetch the data from the cache
	 * 
	 * @return string|null
	 */
	protected function fetchFromCache() {
		$cacheIdentifier = 't3org_feedparser-' . $this->feedUrl;
   		$cacheHash = md5($cacheIdentifier);
   		
    	$result = t3lib_pageSelect::getHash($cacheHash, $this->cacheTime);
    	if(is_string($result)) {
    		
    		$result = unserialize($result);
    		return array_pop($result);
    	}
    	return null;
	}
	
	/**
	 * write the result to the cache
	 * @param string $content
	 */
	protected function setCache($content) {
		$cacheIdentifier = 't3org_feedparser-' . $this->feedUrl;
   		$cacheHash = md5($cacheIdentifier);
   		
   		t3lib_pageSelect::storeHash(
	        $cacheHash,
	        serialize(array($content)),
	        $cacheHash,
	        $this->cacheTime
	    );
	}



	/**
	 * add a header line
	 *
	 * @param string $header
	 * @return null
	 */
	public function addFeedHeader($header)
	{
		$this->feedHeaders[] = $header;
	}
	
}
?>