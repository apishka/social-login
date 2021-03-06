<?php

/**
 * Apishka social login provider: abstract oauth
 */

abstract class Apishka_SocialLogin_Provider_OauthAbstract extends Apishka_SocialLogin_ProviderAbstract
{
    /**
     * Do get request token
     *
     * @return array
     */

    protected function doGetRequestToken()
    {
        $url = new \GuzzleHttp\Psr7\Uri($this->getOauthRequestUrl());

        parse_str(
            $this->makeRequest($url),
            $output
        );

        return $output;
    }

    /**
     * Do authorize
     */

    protected function doAuthorizeRedirect()
    {
        $url = new \GuzzleHttp\Psr7\Uri($this->getOauthAuthorizeUrl());
        $url = $url->withQuery(
            http_build_query(
                array(
                    'oauth_token' => $this->getStorage()->get($this->getAlias(), 'request_token'),
                )
            )
        );

        header('Location: ' . $url->__toString(), true, 302);
        die;
    }

    /**
     * Do get access token
     *
     * @return array
     */

    protected function doGetAccessToken()
    {
        $url = new \GuzzleHttp\Psr7\Uri($this->getOauthAccessUrl());

        parse_str(
            $this->makeRequest(
                $url,
                'post',
                array(
                    'token'     => $this->getStorage()->get($this->getAlias(), 'oauth_token'),
                    'verifier'  => $this->getStorage()->get($this->getAlias(), 'oauth_verifier'),
                )
            ),
            $output
        );

        return $output;
    }

    /**
     * Auth
     *
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

            $this->getStorage()
                ->set($this->getAlias(), 'oauth_token',     $_GET['oauth_token'])
                ->set($this->getAlias(), 'oauth_verifier',  $_GET['oauth_verifier'])
            ;

            $request = $this->doGetAccessToken();

            $this->getStorage()
                ->delete($this->getAlias(), 'oauth_token')
                ->delete($this->getAlias(), 'oauth_verifier')
            ;

            $this->getStorage()
                ->set($this->getAlias(), 'oauth_token',         $request['oauth_token'])
                ->set($this->getAlias(), 'oauth_token_secret',  $request['oauth_token_secret'])
                ->set($this->getAlias(), 'auth_data',           $request)
            ;
        }

        return $this;
    }

    /**
     * Make request
     *
     * @param \GuzzleHttp\Psr7\Uri $url
     * @param string $method
     * @param array $oauth_params
     *
     * @return string
     */

    protected function makeRequest(\GuzzleHttp\Psr7\Uri $url, $method = 'post', array $oauth_params = array())
    {
        if (!isset($this->getProviderConfig()['consumer_key'], $this->getProviderConfig()['consumer_secret']))
            throw new InvalidArgumentException('Keys consumer_key and consumer_secret must be set in config');

        $oauth_params = array_replace(
            array(
                'consumer_key'      => $this->getProviderConfig()['consumer_key'],
                'consumer_secret'   => $this->getProviderConfig()['consumer_secret'],
                'callback'          => $this->getCallbackUrl(),
            ),
            $oauth_params
        );

        $oauth = new \GuzzleHttp\Subscriber\Oauth\Oauth1($oauth_params);

        $http_client = new \GuzzleHttp\Client(
            array(
                'defaults' => ['auth' => 'oauth'],
            )
        );

        $http_client->getEmitter()->attach($oauth);

        try
        {
            $result = $http_client->$method($url);
        }
        catch (\GuzzleHttp\Exception\RequestException $exception)
        {
            throw new Apishka_SocialLogin_Exception('Provider return an error', 0, $exception);
        }

        $result->getBody()->seek(0);

        return $result->getBody()->getContents();
    }

    /**
     * Returns api request url
     *
     *
     * @return string
     */

    abstract protected function getOauthRequestUrl();

    /**
     * Returns authorize url
     *
     *
     * @return string
     */

    abstract protected function getOauthAuthorizeUrl();

    /**
     * Returns oauth access url
     *
     *
     * @return string
     */

    abstract protected function getOauthAccessUrl();
}
