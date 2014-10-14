<?php

namespace tschwemley\trello;
use tschwemley\trello\BaseAbstract;

require_once 'OAuth.php';

class Trello extends BaseAbstract
{
    /**
     * Constructor
     *
     * Available Options:
     *
     * $config = array(
     *		'clientKey'	     =>	 User's OAuth Token Key,
     *		'clientSecret'	 =>  User's OAuth Token Secret
     * );
     *
     * @param array $config
     */
    public function __construct($config)
    {

        parent::__construct($config);

        $this->sha1Method = new \OAuthSignatureMethod_HMAC_SHA1();

        $this->consumer = new \OAuthConsumer($this->consumerKey, $this->consumerSecret);

        if (isset($config['clientKey']) && isset($config['clientSecret'])) {
            $this->token = new \OauthConsumer($config['clientKey'], $config['clientSecret']);
        } else {
            $this->token = null;
        }
    }

    /**
     * Makes a call to the Trello API
     *
     * Accepts an array of URI components, $callBuilder and builds the API call url with the passed arguments. Also
     * accepts an optional second array of arguments for URL argument appendage.
     *
     * For example if you wanted to get board information you would run:
     * $this->apiCall(array('boards', BOARD_ID);
     * [http://api.trello.com/board/BOARD_ID]
     *
     * If you wanted to get more advanced information, like the id and name for all lists a card is a member of you would run:
     *
     * $this->apiCall(
     *      array('cards', 'kCXdLmUA'),
     *      array(
     *           'fields' => 'name,idList',
     *       ));
     * [http://api.trello.com/lists/LIST_ID/cards/all]
     *
     * @param array $callBuilder An array of calls to build into URI
     * @param array $arguments An array of arguments for the call
     *
     * @return string JSON formatted
     */
    public function apiCall($callBuilder, $arguments = array())
    {
        // Build the base call
        $url = $this->apiUrl;
        foreach($callBuilder as $call) {
            $url .= "/$call";
        }

        // Add any additional arguments
        if (!empty($arguments)) {
            $url .= '?';

            foreach($arguments as $argumentName => $argument) {

                $url .= urlencode($argumentName) . '=' . urlencode($argument) . '&';
            }
        }

        return $this->_get($url);
    }

    /**
     * Get the data from the API call
     *
     * @param string $url Call URL
     * @param string $method method to use for call. Defaults to GET
     * @param array $params optional array of parameters to pass into OAuth request
     * @param bool $printJson whether to return json. Defaults to false (returns php array)
     */
    private function _get($url, $method = 'GET', $params = array(), $printJson = false)
    {
        $response = $this->_parseRequest($url, $method, $params);
        var_dump($response);

        if ($printJson === false) {
            return json_decode($response);
        } else {
            return $response;
        }
    }

    /**
     * Gets the OAuth request tokens
     *
     * @param string|null $oauthCallback callback URL (optional)
     */
    public function getRequestToken($oauthCallback = null)
    {

        $parameters = array();

        if ($oauthCallback !== null) {
            $parameters['oauth_callback'] = $oauthCallback;
        }

        $request = $this->_parseRequest($this->requestUrl, 'GET', $parameters);

        $token = \OAuthUtil::parse_parameters($request);

        $this->token = new \OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);

        return $token;
    }

    /**
     * Gets OAuth Authorize URL
     *
     * @param string $token
     * @param array|null $options Optional array of parameters
     *
     * @return string URL for OAuth authorization
     */
    public function getAuthorizeUrl($token, $options = null)
    {
        $url = $this->authorizeUrl . '?oauth_token=' . urlencode($token);

        if ($options !== null) {
            foreach ($options as $variable => $option) {
                $url .= "&$variable=" . urlencode($option);
            }
        }

        return $url;
    }

    /**
     * Gets the OAuth access tokens
     *
     * @param string|bool $oauthVerifier oauth_verifier string
     */
    public function getAccessToken($oauthVerifier = FALSE)
    {
        $parameters = array();

        if ($oauthVerifier !== FALSE) {
            $parameters['oauth_verifier'] = $oauthVerifier;
        }

        $request = $this->_parseRequest($this->accessUrl, 'GET', $parameters);
        $token = \OAuthUtil::parse_parameters($request);
        $this->token = new \OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);

        return $token;
    }

    /**
     * Maks the OAuth request
     *
     * @param string $url
     * @param string $method
     * @param array $parameters
     */
    private function _parseRequest($url, $method, $parameters)
    {
        $request = \OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);

        $request->sign_request($this->sha1Method, $this->consumer, $this->token);

        switch ($method) {
            case 'GET':
                return $this->requestCurl($request->to_url(), 'GET', array());
            default:
                return $this->requestCurl($request->get_normalized_http_url(), $method, $request->to_postdata());
        }
    }

    /**
     * Curls the request
     *
     * @param string $url
     * @param string $method
     * @param array $parameters
     */
    private function requestCurl($url, $method, $parameters)
    {
        $ch = curl_init();

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POST, $parameters);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        $result = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $this->httpCode = $httpCode;

        return $result;
    }
}
