<?php

/**
 * defines the interface for all feed interfaces
 *
 * @author Christian Zenker <christian.zenker@599media.de>
 */
interface Tx_T3orgFeedparser_Domain_Model_FeedInterface {

    /**
     * set the url to fetch the feed from
     *
     * @abstract
     * @param string $url
     */
    public function setFeedUrl($url);

    /**
     * set the number of seconds the response of this feed might be cached
     *
     * @abstract
     * @param integer $seconds
     */
    public function setCacheTime($seconds);


}
?>