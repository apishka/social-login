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
     * Returns user info
     *
     * @access public
     * @return Apishka_SocialLogin_User
     */

    public function getUserInfo()
    {
    }

    /**
     * Returns oauth request url
     *
     * @access protected
     * @return string
     */

    protected function getOauthRequestUrl()
    {
        return 'https://api.login.yahoo.com/oauth/v2/get_request_token';
    }

    /**
     * Returns oauth authorize url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://api.login.yahoo.com/oauth/v2/request_auth';
    }

    /**
     * Returns oauth access url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAccessUrl()
    {
        return 'https://api.login.yahoo.com/oauth/v2/get_token';
    }
}
