<?php

/**
 * Apishka social login provider: odnoklassniki
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Odnoklassniki extends Apishka_SocialLogin_Provider_Oauth2Abstract
{
    /**
     * Returns alias
     *
     * @return string
     */

    public function getAlias()
    {
        return 'odnoklassniki';
    }

    /**
     * Returns user info
     *
     * @return Apishka_SocialLogin_User
     */

    public function getUserInfo()
    {
        if (!array_key_exists('client_key', $this->getProviderConfig()))
            throw new Apishka_SocialLogin_Exception('For odnoklassniki you have to define client_key in provider config');

        $params = array(
            'application_key'   => $this->getProviderConfig()['client_key'],
            'method'            => 'users.getCurrentUser',
            'fields'            => 'uid,first_name,last_name,name,gender,age,birthday,pic_4,email',
        );

        $params['sig']          = $this->buildSignature($params);
        $params['access_token'] = $this->getStorage()->get($this->getAlias(), 'access_token');

        $url = \GuzzleHttp\Url::fromString($this->getProfileInfoUrl());
        $url->setQuery($params);

        $info = $this->makeRequest($url);

        $data = json_decode($info, true);

        if (!array_key_exists('uid', $data))
            throw new Apishka_SocialLogin_Exception('API error: ' . var_export($info, true));

        $user = new Apishka_SocialLogin_User($data);

        $user
            ->setId($data['uid'])
            ->setAvatar($data['pic_4'])
            ->setFullname($data['name'])
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setGender(
                $data['gender'] == 'male'
                    ? Apishka_SocialLogin_User::GENDER_MALE
                    : Apishka_SocialLogin_User::GENDER_FEMALE
            )
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

        $sig .= md5($this->getStorage()->get($this->getAlias(), 'access_token') . $this->getProviderConfig()['client_secret']);

        return md5($sig);
    }

    /**
     * Returns scope
     *
     * @return string
     */

    protected function getOauthScope()
    {
        return 'GET_EMAIL';
    }

    /**
     * Returns oauth authorize url
     *
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'http://www.odnoklassniki.ru/oauth/authorize';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getOauthAccessTokenUrl()
    {
        return 'https://api.odnoklassniki.ru/oauth/token.do';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'http://api.odnoklassniki.ru/fb.do';
    }

}
