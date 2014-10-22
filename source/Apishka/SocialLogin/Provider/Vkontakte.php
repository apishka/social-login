<?php

/**
 * Apishka social login provider: vkontakte
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Vkontakte extends Apishka_SocialLogin_Provider_Oauth2Abstract
{
    /**
     * Returns alias
     *
     * @access public
     * @return string
     */

    public function getAlias()
    {
        return 'vkontakte';
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
     * Returns oauth authorize url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://oauth.vk.com/authorize';
    }

    /**
     * Returns profile url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAccessTokenUrl()
    {
        return 'https://oauth.vk.com/oauth/access_token';
    }
}
