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
     * Returns user info
     *
     * @access public
     * @return Apishka_SocialLogin_User
     */

    public function getUserInfo()
    {
        $data = json_decode($this->getProfileInfo(), true);

        $user = new Apishka_SocialLogin_User();
        foreach ($data as $key => $value)
            $user->set($key, $value);

        return $user;
    }

    /**
     * Returns oauth request url
     *
     * @access protected
     * @return string
     */

    protected function getOauthRequestUrl()
    {
        return 'https://api.twitter.com/oauth/request_token';
    }

    /**
     * Returns oauth authorize url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://api.twitter.com/oauth/authenticate';
    }

    /**
     * Returns oauth access url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAccessUrl()
    {
        return 'https://api.twitter.com/oauth/access_token';
    }

    /**
     * Returns profile url
     *
     * @access protected
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'https://api.twitter.com/1.1/account/verify_credentials.json';
    }
}
