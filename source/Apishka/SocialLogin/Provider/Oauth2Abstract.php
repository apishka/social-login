<?php

/**
 * Apishka_SocialLogin_Provider_Oauth2Abstract
 */

abstract class Apishka_SocialLogin_Provider_Oauth2Abstract extends Apishka_SocialLogin_ProviderAbstract
{
    /**
     * Do authorize redirect
     */

    public function doAuthorizeRedirect()
    {
        $state = $this->getOauthState();

        $this->getStorage()
            ->set($this->getAlias(), 'provider_state', $state)
        ;

        $url = \GuzzleHttp\Url::fromString($this->getOauthAuthorizeUrl());
        $url->setQuery(
            array(
                'client_id'     => $this->getProviderConfig()['client_id'],
                'scope'         => $this->getOauthScope(),
                'redirect_uri'  => $this->getCallbackUrl(),
                'response_type' => 'code',
                'state'         => $state,
                'access_type'   => 'offline',
            )
        );

        header('Location: ' . $url->__toString(), true, 302);
        die;
    }

    /**
     * Do access token request
     *
     * @return array
     */

    public function doAccessTokenRequest()
    {
        $url = \GuzzleHttp\Url::fromString($this->getOauthAccessTokenUrl());

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
        $url = \GuzzleHttp\Url::fromString($this->getOauthAccessTokenUrl());

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
        return md5($this->getAlias() . date('H', strtotime($time)));
    }

    /**
     * Check oauth state
     *
     * @param string $state
     */

    protected function checkOauthState($state)
    {
        if ($state == $this->getOauthState('now'))
            return;

        if ($state == $this->getOauthState('-1 hour'))
            return;

        throw new Apishka_SocialLogin_Exception('Wrong state');
    }

    /**
     * Make request
     *
     * @param \GuzzleHttp\Url $url
     * @param string          $method
     * @param array           $post_params
     *
     * @return string
     */

    protected function makeRequest(\GuzzleHttp\Url $url, $method = 'get', array $post_params = array())
    {
        $http_client = new \GuzzleHttp\Client();

        //$http_client->getEmitter()->attach(new \GuzzleHttp\Subscriber\Log\LogSubscriber(null, \GuzzleHttp\Subscriber\Log\Formatter::DEBUG));

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
                            'body' => $post_params,
                        )
                    );
                    break;

                default:
                    throw new Apishka_SocialLogin_Exception();
            }
        }
        catch (GuzzleHttp\Exception\RequestException $exception)
        {
            //if ($exception->hasResponse()) {
            //    echo $exception->getResponse();
            //}
            //die;

            throw new Apishka_SocialLogin_Exception('Provider return an error', 0, $exception);
        }

        $result->getBody()->seek(0);

        return $result->getBody()->getContents();
    }
}
