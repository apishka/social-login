<?php

/**
 * Apishka social login provider: mailru
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Mailru extends Apishka_SocialLogin_Provider_Oauth2Abstract
{
    /**
     * Returns alias
     *
     * @access public
     * @return string
     */

    public function getAlias()
    {
        return 'mailru';
    }

    /**
     * Returns user info
     *
     * @access public
     * @return Apishka_SocialLogin_User
     */

    public function getUserInfo()
    {
        $params = array(
            'client_id'     => $this->getProviderConfig()['client_id'],
            'format'        => 'json',
            'method'        => 'users.getInfo',
            'session_key'   => $this->getStorage()->get($this->getAlias(), 'access_token'),
            'secure'        => 1,
        );

        $params['sig'] = $this->buildSignature($params);

        $url = \GuzzleHttp\Url::fromString($this->getProfileInfoUrl());
        $url->setQuery($params);

        $info = $this->makeRequest($url);

        $data = json_decode($info, true);

        $user = new Apishka_SocialLogin_User();
        foreach ($data['response'][0] as $key => $value)
            $user->set($key, $value);

        $user
            ->set('id',             $user->uid)
            ->set('avatar',         $user->pic)
            ->set('fullname',       $user->first_name . ' ' . $user->last_name)
            ->set('gender',         $user->sex == 0 ? Apishka_SocialLogin_User::GENDER_MALE : Apishka_SocialLogin_User::GENDER_FEMALE)
            ->set('login',          $user->nick)
            ->set('birthdate',      $user->birthday)
        ;

        return $user;
    }

    /**
     * Build signature
     *
     * @param array     $params
     * @access private
     * @return string
     */

    private function buildSignature(array $params)
    {
        ksort($params);

        $sig = '';
        foreach ($params as $key => $value)
            $sig .= $key . '=' . $value;

        return md5($sig . $this->getProviderConfig()['client_secret']);
    }

    /**
     * Returns oauth authorize url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://connect.mail.ru/oauth/authorize';
    }

    /**
     * Returns profile url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAccessTokenUrl()
    {
        return 'https://connect.mail.ru/oauth/token';
    }

    /**
     * Returns profile url
     *
     * @access protected
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'http://www.appsmail.ru/platform/api';
    }

}