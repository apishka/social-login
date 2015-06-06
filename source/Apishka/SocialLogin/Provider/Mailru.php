<?php

/**
 * Apishka social login provider: mailru
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Mailru extends Apishka_SocialLogin_Provider_Oauth2Abstract
{
    /**
     * Returns alias
     *
     * @return string
     */

    public function getAlias()
    {
        return 'mailru';
    }

    /**
     * Returns user info
     *
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

        $decoded = json_decode($info, true);
        $data = $decoded[0];

        $user = new Apishka_SocialLogin_User($data);

        $user
            ->setId($data['uid'])
            ->setEmail($data['email'])
            ->setAvatar($data['pic'])
            ->setFullname($data['first_name'] . ' ' . $data['last_name'])
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setGender(
                $data['sex'] == 0
                    ? Apishka_SocialLogin_User::GENDER_MALE
                    : Apishka_SocialLogin_User::GENDER_FEMALE
            )
            ->setLogin($data['nick'])
            ->setBirthday($data['birthday'])
        ;

        return $user;
    }

    /**
     * Build signature
     *
     * @param array $params
     *
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
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://connect.mail.ru/oauth/authorize';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getOauthAccessTokenUrl()
    {
        return 'https://connect.mail.ru/oauth/token';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'http://www.appsmail.ru/platform/api';
    }

}
