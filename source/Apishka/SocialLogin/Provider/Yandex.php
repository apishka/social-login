<?php

/**
 * Apishka social login provider: yandex
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Yandex extends Apishka_SocialLogin_Provider_Oauth2Abstract
{
    /**
     * Returns alias
     *
     * @access public
     * @return string
     */

    public function getAlias()
    {
        return 'yandex';
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
        $url->setQuery(
            array(
                'format'        => 'json',
                'oauth_token'   => $this->getStorage()->get($this->getAlias(), 'access_token'),
            )
        );

        $info = $this->makeRequest($url);

        $data = json_decode($info, true);

        $user = new Apishka_SocialLogin_User();
        foreach ($data['response'][0] as $key => $value)
            $user->set($key, $value);

        $user
            ->set('id',             $user->uid)
            ->set('avatar',         $user->photo_big)
            ->set('fullname',       $user->real_name)
            ->set('gender',         $user->sex == 'female' ? Apishka_SocialLogin_User::GENDER_FEMALE : Apishka_SocialLogin_User::GENDER_MALE)
            ->set('login',          $user->nickname)
            ->set('birthdate',      $user->birthday)
            ->set('email',          $user->default_email)
        ;

        return $user;
    }

    /**
     * Returns oauth authorize url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://oauth.yandex.ru/authorize';
    }

    /**
     * Returns profile url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAccessTokenUrl()
    {
        return 'https://oauth.yandex.ru/token';
    }

    /**
     * Returns profile url
     *
     * @access protected
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'https://login.yandex.ru/info';
    }

}
