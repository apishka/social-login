<?php

/**
 * Apishka_SocialLogin_Provider_Oauth2Abstract
 *
 * @uses    Apishka_SocialLogin_ProviderAbstract
 * @abstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

abstract class Apishka_SocialLogin_Provider_Oauth2Abstract extends Apishka_SocialLogin_ProviderAbstract
{
    /**
     * Do authorize redirect
     *
     * @access public
     * @return void
     */

    public function doAuthorizeRedirect()
    {
        $url = \GuzzleHttp\Url::fromString($this->getOauthAuthorizeUrl());
        $url->setQuery(
            array(
                'client_id'     => $this->getProviderConfig()['client_id'],
                'scope'         => $this->getScope(),
                'redirect_uri'  => $this->getCallbackUrl(),
                'response_type' => 'code',
            )
        );

        header('Location: ' . $url->__toString(), true, 302);
        die;
    }

    /**
     * Do access token request
     *
     * @access public
     * @return array
     */

    public function doAccessTokenRequest()
    {
        $url = \GuzzleHttp\Url::fromString($this->getOauthAccessTokenUrl());
        $url->setQuery(
            array(
                'client_id'     => $this->getProviderConfig()['client_id'],
                'client_secret' => $this->getProviderConfig()['client_secret'],
                'code'          => $_GET['code'],
                'redirect_uri'  => $this->getCallbackUrl(),
                'response_type' => 'code',
            )
        );

        return $this->makeRequest($url);
    }

    /**
     * Auth
     *
     * @access public
     * @return string
     */

    public function auth()
    {
        if (!isset($_GET['code']))
        {
            $this->doAuthorizeRedirect();
        }
        else
        {
            $request = json_decode($this->doAccessTokenRequest());

            $this->getStorage()
                ->set($this->getAlias(), 'access_token',        $request['access_token'])
                ->set($this->getAlias(), 'auth_data',           $request)
            ;
        }
    }

    /**
     * Returns scope
     *
     * @access protected
     * @return void
     */

    protected function getScope()
    {
        return '';
    }

    /**
     * Make request
     *
     * @param \GuzzleHttp\Url   $url
     * @param string            $method
     * @param array             $oauth_params
     * @access protected
     * @return string
     */

    protected function makeRequest(\GuzzleHttp\Url $url, $method = 'post')
    {
        $http_client = new \GuzzleHttp\Client();

        //$http_client->getEmitter()->attach(new \GuzzleHttp\Subscriber\Log\LogSubscriber(null, \GuzzleHttp\Subscriber\Log\Formatter::DEBUG));

        try
        {
            $result = $http_client->$method($url);
        }
        catch (GuzzleHttp\Exception\RequestException $exception)
        {
            if ($exception->hasResponse()) {
                echo $exception->getResponse();
            }
            die;

            throw new Apishka_SocialLogin_Exception('Provider return an error', 0, $exception);
        }

        $result->getBody()->seek(0);

        return $result->getBody()->getContents();
    }
}
