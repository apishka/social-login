<?php

/**
 * Apishka social login provider: yandex
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Yandex extends Apishka_SocialLogin_Provider_Oauth2Abstract
{
    /**
     * Returns alias
     *
     * @return string
     */

    public function getAlias()
    {
        return 'yandex';
    }

    /**
     * Returns user info
     *
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

        $user = new Apishka_SocialLogin_User($data);

        $user
            ->setId($data['id'])
            ->setFullname($data['real_name'])
            ->setGender(
                $data['sex'] == 'female'
                    ? Apishka_SocialLogin_User::GENDER_FEMALE
                    : Apishka_SocialLogin_User::GENDER_MALE
            )
            ->setLogin($data['login'])
            ->setBirthday($data['birthday'])
            ->setEmail($data['default_email'])
        ;

        return $user;
    }

    /**
     * Returns oauth authorize url
     *
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://oauth.yandex.ru/authorize';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getOauthAccessTokenUrl()
    {
        return 'https://oauth.yandex.ru/token';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'https://login.yandex.ru/info';
    }

}
