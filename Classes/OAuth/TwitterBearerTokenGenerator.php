<?php


class Tx_T3orgFeedparser_OAuth_TwitterBearerTokenGenerator implements t3lib_Singleton {

    protected $url = 'https://api.twitter.com/oauth2/token';

    public function getBearerToken($key, $secret) {
        $key = urlencode($key);
        $secret = urlencode($secret);

        $credentials = base64_encode($key . ':' . $secret);


        $response = $this->postUrl(
            $this->url,
            'grant_type=client_credentials',
            array(
                'User-Agent: typo3.org/FeedParser',
                'Authorization: Basic ' . $credentials,
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
            )
        );

        $response = json_decode($response, true);

        // error handling
        if(!$response) {
            throw new Tx_T3orgFeedparser_OAuth_TwitterApiException(sprintf('%s did not return valid JSON.', $this->url));
        }

        if(!array_key_exists('token_type', $response)) {
            throw new Tx_T3orgFeedparser_OAuth_TwitterApiException('The response has no field named "token_type".');
        }

        if($response['token_type'] !== 'bearer') {
            throw new Tx_T3orgFeedparser_OAuth_TwitterApiException(
                sprintf('The returned token_type is "%s". "bearer" expected.', $response['token_type'])
            );
        }

        if(!array_key_exists('access_token', $response)) {
            throw new Tx_T3orgFeedparser_OAuth_TwitterApiException('The response has no field named "access_token".');
        }
        if(!$response['access_token']) {
            throw new Tx_T3orgFeedparser_OAuth_TwitterApiException('The value of field "access_token" is empty although it should not be.');
        }

        return $response['access_token'];

    }

    /**
     * sends a post request via curl
     *
     * Mostly an adapted clone of t3lib_div::getUrl, but it just uses curl and sends POST-requests
     *
     * @param string $url
     * @param string $data POST data to send with the request
     * @param array $headers
     * @return bool
     */
    protected function postUrl($url, $data, $headers = array()) {
        if (!$GLOBALS['TYPO3_CONF_VARS']['SYS']['curlUse'] == '1') {
            throw new RuntimeException("\$TYPO3_CONF_VARS['SYS']['curlUse'] must be enabled in order to send a POST request.");
        }
        $ch = curl_init();

        if (!$ch) {
            throw new UnexpectedValueException('Couldn\'t initialize cURL.');
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, max(0, intval($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlTimeout'])));

        if($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if (is_array($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // (Proxy support implemented by Arco <arco@appeltaart.mine.nu>)
        if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyServer']) {
            curl_setopt($ch, CURLOPT_PROXY, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyServer']);

            if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyTunnel']) {
                curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyTunnel']);
            }
            if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyUserPass']) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $GLOBALS['TYPO3_CONF_VARS']['SYS']['curlProxyUserPass']);
            }
        }
        $content = curl_exec($ch);

        curl_close($ch);

        if($content === FALSE) {
            throw new Tx_T3orgFeedparser_OAuth_TwitterApiException(sprintf('cURL failed with code %d: "%s"', curl_errno($ch), curl_error($ch)));
        }

        return $content;
    }

}