<?php

/**
 * Apishka social login provider: twitter
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Twitter extends Apishka_SocialLogin_Provider_OauthAbstract
{
    /**
     * Returns alias
     *
     * @access public
     * @return string
     */

    public function getAlias()
    {
        return 'twitter';
    }

    /**
     * Returns api base url
     *
     * @access public
     * @return string
     */

    public function getApiBaseUrl()
    {
        return 'https://api.twitter.com/1.1/';
    }

    /**
     * Returns api request url
     *
     * @access public
     * @return string
     */

    public function getApiRequestUrl()
    {
        return '/oauth/request_token';
    }
}
