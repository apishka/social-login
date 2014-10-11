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
     * Returns oauth base url
     *
     * @access public
     * @return string
     */

    public function getOauthBaseUrl()
    {
        return 'https://api.twitter.com/';
    }

    /**
     * Returns oauth request url
     *
     * @access public
     * @return string
     */

    public function getOauthRequestUrl()
    {
        return '/oauth/request_token';
    }

    /**
     * Returns oauth authorize url
     *
     * @access public
     * @return string
     */

    public function getOauthAuthorizeUrl()
    {
        return '/oauth/authenticate';
    }
}