<?php

/**
 * Apishka social login provider: abstract oauth
 *
 * @uses    Apishka_SocialLogin_ProviderAbstract
 * @abstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

abstract class Apishka_SocialLogin_Provider_OauthAbstract extends Apishka_SocialLogin_ProviderAbstract
{
    /**
     * Http client
     *
     * @var \GuzzleHttp\Client
     * @access private
     */

    private $_http_client = null;

    /**
     * Do get request token
     *
     * @access protected
     * @return string
     */

    protected function doGetRequestToken()
    {
        $result = $this->getHttpClient()->post(
            $this->getApiRequestUrl(),
            array(
                'stream' => false,
                'headers' => array(
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ),
                'config' => array(
                    'curl' => array(
                        CURLOPT_RETURNTRANSFER => 1,
                    ),
                ),
            )
        );

        $result->getBody()->seek(0);

        return $result->getBody()->getContents();
    }

    /**
     * Auth
     *
     * @access public
     * @return string
     */

    public function auth()
    {
        $this->doGetRequestToken();
    }

    /**
     * Returns http client
     *
     * @access protected
     * @return string
     */

    protected function getHttpClient()
    {
        if ($this->_http_client === null)
            $this->initHttpClient();

        return $this->_http_client;
    }

    /**
     * Init http client
     *
     * @access protected
     * @return void
     */

    protected function initHttpClient()
    {
        $providers = $this->getBase()->getConfig()['providers'];

        if (!isset($providers[$this->getAlias()]['consumer_key'], $providers[$this->getAlias()]['consumer_secret']))
            throw new InvalidArgumentException('Keys consumer_key and consumer_secret must be set in config');

        $oauth = new \GuzzleHttp\Subscriber\Oauth\Oauth1(
            array(
                'consumer_key'      => $providers[$this->getAlias()]['consumer_key'],
                'consumer_secret'   => $providers[$this->getAlias()]['consumer_secret'],
                'token'             => $this->getToken(),
                'token_secret'      => $this->getTokenSecret(),
            )
        );

        $this->_http_client = new \GuzzleHttp\Client(
            array(
                'base_url' => $this->getApiBaseUrl(),
                'defaults' => ['auth' => 'oauth'],
            )
        );

        $this->_http_client->getEmitter()->attach($oauth);
        $this->_http_client->getEmitter()->attach(new \GuzzleHttp\Subscriber\Log\LogSubscriber());
    }

    /**
     * Get token
     *
     * @access protected
     * @return string|null
     */

    protected function getToken()
    {
        return $this->getStorage()->get(
            $this->getAlias(),
            'token'
        );
    }

    /**
     * Returns token secret
     *
     * @access protected
     * @return string|null
     */

    protected function getTokenSecret()
    {
        return $this->getStorage()->get(
            $this->getAlias(),
            'token_secret'
        );
    }

    /**
     * Returns api base url
     *
     * @abstract
     * @access protected
     * @return string
     */

    abstract protected function getApiBaseUrl();

    /**
     * Returns api request url
     *
     * @abstract
     * @access protected
     * @return string
     */

    abstract protected function getApiRequestUrl();
}
