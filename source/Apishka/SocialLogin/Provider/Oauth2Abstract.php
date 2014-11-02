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
     * @access public
     * @return Apishka_SocialLogin_ProviderAbstract
     */

    public function auth()
    {
        if (!isset($_GET['code']))
        {
            $this->doAuthorizeRedirect();
        }
        else
        {
            $request = json_decode($this->doAccessTokenRequest(), true);

            if (!array_key_exists('access_token', $request))
                throw new Apishka_SocialLogin_Exception('Error in request');

            $this->getStorage()
                ->set($this->getAlias(), 'access_token',        $request['access_token'])
                ->set($this->getAlias(), 'auth_data',           $request)
            ;
        }

        return $this;
    }

    /**
     * Returns scope
     *
     * @access protected
     * @return string
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
     * @param array             $post_params
     * @access protected
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
