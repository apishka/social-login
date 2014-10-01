<?php

/**
 * Apishka social login provider: yahoo
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Yahoo extends Apishka_SocialLogin_Provider_OauthAbstract
{
    /**
     * Returns alias
     *
     * @access public
     * @return string
     */

    public function getAlias()
    {
        return 'yahoo';
    }

    /**
     * Returns oauth base url
     *
     * @access public
     * @return string
     */

    public function getOauthBaseUrl()
    {
        return 'https://api.login.yahoo.com/';
    }

    /**
     * Returns oauth request url
     *
     * @access public
     * @return string
     */

    public function getOauthRequestUrl()
    {
        return '/oauth/v2/get_request_token';
    }

    /**
     * Returns oauth authorize url
     *
     * @access public
     * @return string
     */

    public function getOauthAuthorizeUrl()
    {
        return '/oauth/v2/authorize';
    }
}
