<?php

namespace tschwemley\trello;

abstract class BaseAbstract
{
	/**
	 * Trello API Wrapper Version
	 *
	 * @var string
	 */
	private $version = '1';

	/**
	 * Trello API Version
	 *
	 * @var int
	 */
	protected $apiVersion = 1;

	/**
	 * OAuthConsumer Object.
	 *
	 * @var OAuthConsumer
	 */
	protected $consumer;

	/**
	 * OAuth Tokens
	 *
	 * @var array
	 */
	protected $token;

	/**
	 * sha1 signature
	 *
	 * @var OAuthSignatureMethod_HMAC_SHA1
	 */
	protected  $sha1Method;

	/**
	 * HTTP Response code
	 *
	 * @var string
	 */
	protected $httpCode;

    /**
     * Trello OAuth Authorization URL
     *
     * @var string
     */
    protected $authorizeUrl = 'https://trello.com/1/OAuthAuthorizeToken';

    /**
     * Trello OAuth Request URL
     *
     * @var string
     */
    protected $requestUrl = 'https://trello.com/1/OAuthGetRequestToken';

    /**
     * Trello OAuth Access URL
     *
     * @var string
     */
    protected $accessUrl = 'https://trello.com/1/OAuthGetAccessToken';

    /**
     * Trello Base API Url
     *
     * @var string
     */
    protected $apiUrl = 'https://api.trello.com/1';

    /**
     * Your Trello API consumer key
     *
     * @var string
     */
    protected $consumerKey = 'PUT_YOUR_CONSUMER_KEY_HERE';

    /**
     * Your Trello API consumer secret
     *
     * @var string
     */
    protected $consumerSecret = 'PUT_YOUR_CONSUMER_SECRET_HERE';
	/**
	 * Constructor
	 *
	 * Sets configuration
	 *
	 * @param array $config
	 * @throws \Exception
	 */
	public function __construct($config)
	{
		if (is_array($config) === true) {

			// Set App Configuration
			$this->_setConfigVariables($config);

		} else {
			throw new \Exception("Error: __construct() - Configuration array is missing");
		}
	}

	/**
	 * Set Configuration Variables
	 *
	 * Adds configuration items as properties
	 *
	 * @param array $config
	 * @return void
	 */
	private function _setConfigVariables($config)
	{
		foreach ($config as $variable => $value) {
			$this->$variable = $value;
		}

		return;
	}
}
