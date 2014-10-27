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
        $url = \GuzzleHttp\Url::fromString($this->getProfileInfoUrl());

        $info = $this->makeRequest(
            $url,
            'get',
            array(
                'token'         => $this->getStorage()->get($this->getAlias(), 'oauth_token'),
                'token_secret'  => $this->getStorage()->get($this->getAlias(), 'oauth_token_secret'),
            )
        );

        $data = json_decode($info, true);

        $user = new Apishka_SocialLogin_User();
        foreach ($data as $key => $value)
            $user->set($key, $value);

        $user
            ->set('fullname',       $user->name)
            ->set('login',          $user->screen_name)
            ->set('gender',         Apishka_SocialLogin_User::GENDER_ALL)
            ->set('avatar',         $user->profile_image_url_https)
        ;

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
