<?php

/**
 * Apishka_SocialLogin_Provider_Oauth2Abstract
 */

abstract class Apishka_SocialLogin_Provider_Oauth2Abstract extends Apishka_SocialLogin_ProviderAbstract
{
    /**
     * State
     *
     * @var string
     */

    private $_state;

    /**
     * Do authorize redirect
     */

    public function doAuthorizeRedirect()
    {
        $this->getStorage()
            ->set($this->getAlias(), 'provider_state', $this->getOauthState())
        ;

        $url = new \GuzzleHttp\Psr7\Uri($this->getOauthAuthorizeUrl());
        $url = $url->withQuery(
            http_build_query($this->getAutorizeQueryParams())
        );

        header('Location: ' . $url->__toString(), true, 302);
        die;
    }

    /**
     * Is need re prompt
     *
     * @return array
     */

    protected function getAutorizeQueryParams()
    {
        return array(
            'client_id'     => $this->getProviderConfig()['client_id'],
            'scope'         => $this->getOauthScope(),
            'redirect_uri'  => $this->getCallbackUrl(),
            'response_type' => 'code',
            'state'         => $this->getOauthState(),
            'access_type'   => 'offline',
        );
    }

    /**
     * Do access token request
     *
     * @return array
     */

    public function doAccessTokenRequest()
    {
        $url = new \GuzzleHttp\Psr7\Uri($this->getOauthAccessTokenUrl());

        return $this->makeRequest(
            $url,
            'post',
            array(
                'client_id'     => $this->getProviderConfig()['client_id'],
                'client_secret' => $this->getProviderConfig()['client_secret'],
                'code'          => $_GET['code'],
                'redirect_uri'  => $this->getCallbackUrl(),
                'grant_type'    => 'authorization_code',
            )
        );
    }

    /**
     * Auth
     *
     * @return Apishka_SocialLogin_ProviderAbstract
     */

    public function auth()
    {
        if ($this->getStorage()->get($this->getAlias(), 'provider_state') === null)
        {
            $this->doAuthorizeRedirect();
        }
        else
        {
            $this->getStorage()
                ->delete($this->getAlias(), 'provider_state')
            ;

            if (!isset($_GET['state']))
                throw new Apishka_SocialLogin_Exception('Error in request: state not found');

            // https://tools.ietf.org/html/rfc6749#section-5.2
            if (isset($_GET['error']))
                throw new Apishka_SocialLogin_Exception('Error in response: ' . var_export($_GET['error'], true));

            $this->checkOauthState($_GET['state']);

            $request = json_decode($this->doAccessTokenRequest(), true);

            if (!array_key_exists('access_token', $request))
                throw new Apishka_SocialLogin_Exception('Error in request: access_token not found');

            $this->getStorage()
                ->set($this->getAlias(), 'access_token',        $request['access_token'])
                ->set($this->getAlias(), 'auth_data',           $request)
            ;

            if (array_key_exists('refresh_token', $request))
            {
                $this->getStorage()
                    ->set($this->getAlias(), 'refresh_token', $request['refresh_token'])
                ;
            }
        }

        return $this;
    }

    /**
     * Refresh
     *
     * @return Apishka_SocialLogin_ProviderAbstract
     */

    public function refresh()
    {
        $request = json_decode($this->doRefreshTokenRequest(), true);

        if (!array_key_exists('access_token', $request))
            throw new Apishka_SocialLogin_Exception('Error in refresh request: access_token not found');

        $this->getStorage()
            ->set($this->getAlias(), 'access_token',        $request['access_token'])
            ->set($this->getAlias(), 'auth_data',           $request)
        ;

        if (array_key_exists('refresh_token', $request))
        {
            $this->getStorage()
                ->set($this->getAlias(), 'refresh_token', $request['refresh_token'])
            ;
        }

        return $this;
    }

    /**
     * Do access token request
     *
     * @return array
     */

    public function doRefreshTokenRequest()
    {
        $url = new \GuzzleHttp\Psr7\Uri($this->getOauthAccessTokenUrl());

        return $this->makeRequest(
            $url,
            'post',
            array(
                'client_id'     => $this->getProviderConfig()['client_id'],
                'client_secret' => $this->getProviderConfig()['client_secret'],
                'refresh_token' => $this->getRefreshToken(),
                'redirect_uri'  => $this->getCallbackUrl(),
                'grant_type'    => 'refresh_token',
            )
        );
    }

    /**
     * Set refresh token
     *
     * @param string $refresh_token
     *
     * @return Apishka_SocialLogin_ProviderAbstract
     */

    public function setRefreshToken($refresh_token)
    {
        $this->getStorage()
            ->set($this->getAlias(), 'refresh_token', $refresh_token)
        ;

        return $this;
    }

    /**
     * Get refresh token
     *
     * @return string
     */

    public function getRefreshToken()
    {
        return $this->getStorage()
            ->get($this->getAlias(), 'refresh_token')
        ;
    }

    /**
     * Returns scope
     *
     * @return string
     */

    protected function getOauthScope()
    {
        return '';
    }

    /**
     * Returns oauth state
     *
     * @param string $time
     *
     * @return string
     */

    protected function getOauthState($time = 'now')
    {
        if ($this->_state === null)
            $this->_state = $this->buildOauthState($time);

        return $this->_state;
    }

    /**
     * Build oauth state
     *
     * @param string $time
     *
     * @return string
     */

    protected function buildOauthState($time)
    {
        return md5($this->getAlias() . date('H', strtotime($time)));
    }

    /**
     * Check oauth state
     *
     * @param string $state
     */

    protected function checkOauthState($state)
    {
        if ($state == $this->buildOauthState('now'))
            return;

        if ($state == $this->buildOauthState('-1 hour'))
            return;

        throw new Apishka_SocialLogin_Exception('Wrong state');
    }

    /**
     * Make request
     *
     * @param \GuzzleHttp\Psr7\Uri $url
     * @param string $method
     * @param array $post_params
     *
     * @return string
     */

    protected function makeRequest(\GuzzleHttp\Psr7\Uri $url, $method = 'get', array $post_params = array())
    {
        $http_client = new \GuzzleHttp\Client();

        try
        {
            switch ($method)
            {
                case 'get':
                    $result = $http_client->get($url);
                    break;

                case 'post':
                    $result = $http_client->post(
                        $url,
                        array(
                            'query' => $post_params,
                        )
                    );
                    break;

                default:
                    throw new Apishka_SocialLogin_Exception();
            }
        }
        catch (\GuzzleHttp\Exception\RequestException $exception)
        {
            throw new Apishka_SocialLogin_Exception('Provider return an error', 0, $exception);
        }

        $result->getBody()->seek(0);

        return $result->getBody()->getContents();
    }
}
