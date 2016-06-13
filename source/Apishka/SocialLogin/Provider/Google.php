<?php

/**
 * Apishka social login provider: google
 */

class Apishka_SocialLogin_Provider_Google extends Apishka_SocialLogin_Provider_Oauth2Abstract
{
    /**
     * Is need reprompt
     *
     * @var bool
     */

    private $_is_need_reprompt = false;

    /**
     * Returns alias
     *
     * @return string
     */

    public function getAlias()
    {
        return 'google';
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
                'oauth_token'   => $this->getStorage()->get($this->getAlias(), 'access_token'),
            )
        );

        $info = $this->makeRequest($url);

        $data = json_decode($info, true);

        if (!isset($data['id']))
            throw new Apishka_SocialLogin_Exception('Data retrieval error');

        $user = new Apishka_SocialLogin_User($data);

        $user
            ->setId($data['id'])
            ->setAvatar($data['image']['url'])
            ->setFullname($data['name']['givenName'] . ' ' . $data['name']['familyName'])
            ->setFirstName($data['name']['givenName'])
            ->setLastName($data['name']['familyName'])
            ->setGender(
                $data['gender'] == 'female'
                    ? Apishka_SocialLogin_User::GENDER_FEMALE
                    : Apishka_SocialLogin_User::GENDER_MALE
            )
            ->setLogin($data['nickname'])
            ->setBirthday($data['birthday'])
            ->setEmail($data['emails'][0]['value'])
        ;

        return $user;
    }

    /**
     * Is need re prompt
     *
     * @return array
     */

    protected function getAutorizeQueryParams()
    {
        $params = parent::getAutorizeQueryParams();

        if ($this->isNeedRePrompt())
            $params['prompt'] = 'consent';

        return $params;
    }

    /**
     * Is need re prompt
     *
     * @param bool $value
     *
     * @return bool|this
     */

    public function isNeedRePrompt($value = null)
    {
        if ($value === null)
            return $this->_is_need_reprompt;

        $this->_is_need_reprompt = (bool) $value;

        return $this;
    }

    /**
     * Returns scope
     *
     * @return string
     */

    protected function getOauthScope()
    {
        if ($this->getProviderConfig()['scope'])
            return $this->getProviderConfig()['scope'];

        return 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/plus.profile.emails.read';
    }

    /**
     * Returns oauth authorize url
     *
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://accounts.google.com/o/oauth2/auth';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getOauthAccessTokenUrl()
    {
        return 'https://www.googleapis.com/oauth2/v3/token';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'https://www.googleapis.com/plus/v1/people/me';
    }
}
