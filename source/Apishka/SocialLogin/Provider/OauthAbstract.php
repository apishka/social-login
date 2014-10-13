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
        try
        {
            $result = $this->getHttpClient()->post(
                $this->getOauthRequestUrl()
            );
        }
        catch (GuzzleHttp\Exception\RequestException $exception)
        {
            throw $exception;
        }

        $result->getBody()->seek(0);

        parse_str(
            $result->getBody()->getContents(),
            $output
        );

        var_dump($output);

        return $output;
    }

    /**
     * Do authorize
     *
     * @access protected
     * @return void
     */

    protected function doAuthorizeRedirect()
    {
        $url = \GuzzleHttp\url::fromString($this->getOauthAuthorizeUrl());
        $url->setQuery(
            array(
                'oauth_token' => $this->getStorage()->get($this->getAlias(), 'request_token'),
            )
        );

        header('Location: ' . $url->__toString(), true, 302);
        die;
    }

    /**
     * Auth
     *
     * @access public
     * @return string
     */

    public function auth()
    {
        if (!$this->getStorage()->get($this->getAlias(), 'request_token'))
        {
            $request = $this->doGetRequestToken();

            $this->getStorage()
                ->set($this->getAlias(), 'request_token',           $request['oauth_token'])
                ->set($this->getAlias(), 'request_token_secret',    $request['oauth_token_secret'])
            ;

            $this->doAuthorizeRedirect();
        }
        else
        {
            $this->getStorage()
                ->delete($this->getAlias(), 'request_token')
                ->delete($this->getAlias(), 'request_token_secret')
            ;

            if (!isset($_GET['oauth_token']) || !isset($_GET['oauth_verifier']))
                throw new Apishka_SocialLogin_Exception('User not allow access');
        }
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
            )
        );

        $this->_http_client = new \GuzzleHttp\Client(
            array(
                'defaults' => ['auth' => 'oauth'],
            )
        );

        $this->_http_client->getEmitter()->attach($oauth);
        //$this->_http_client->getEmitter()->attach(new \GuzzleHttp\Subscriber\Log\LogSubscriber());
    }

    /**
     * Returns api request url
     *
     * @abstract
     * @access protected
     * @return string
     */

    abstract protected function getOauthRequestUrl();

    /**
     * Returns authorize url
     *
     * @abstract
     * @access protected
     * @return string
     */

    abstract protected function getOauthAuthorizeUrl();
}
