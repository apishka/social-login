<?php

/**
 * Apishka social login provider: twitter
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Twitter extends Apishka_SocialLogin_Provider_OauthAbstract
{
    /**
     * Returns alias
     *
     * @return string
     */

    public function getAlias()
    {
        return 'twitter';
    }

    /**
     * Returns user info
     *
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

        $user = new Apishka_SocialLogin_User($data);

        $user
            ->setId($data['id'])
            ->setFullname($data['name'])
            ->setLogin($data['screen_name'])
            ->setAvatar($data['profile_image_url_https'])
        ;

        return $user;
    }

    /**
     * Returns oauth request url
     *
     * @return string
     */

    protected function getOauthRequestUrl()
    {
        return 'https://api.twitter.com/oauth/request_token';
    }

    /**
     * Returns oauth authorize url
     *
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://api.twitter.com/oauth/authenticate';
    }

    /**
     * Returns oauth access url
     *
     * @return string
     */

    protected function getOauthAccessUrl()
    {
        return 'https://api.twitter.com/oauth/access_token';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'https://api.twitter.com/1.1/account/verify_credentials.json';
    }
}
