<?php

/**
 * Apishka social login provider: google
 *
 * @uses    Apishka_SocialLogin_Provider_Oauth2Abstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Google extends Apishka_SocialLogin_Provider_Oauth2Abstract
{
    /**
     * Returns alias
     *
     * @access public
     * @return string
     */

    public function getAlias()
    {
        return 'google';
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
                'oauth_token'   => $this->getStorage()->get($this->getAlias(), 'access_token'),
            )
        );

        $info = $this->makeRequest($url);

        $data = json_decode($info, true);

        $user = new Apishka_SocialLogin_User();
        foreach ($data as $key => $value)
            $user->set($key, $value);

        $user
            ->set('avatar',         $user->image['url'])
            ->set('fullname',       $user->name['givenName'] . ' ' . $user->name['familyName'])
            ->set('gender',         $user->gender == 'female' ? Apishka_SocialLogin_User::GENDER_FEMALE : Apishka_SocialLogin_User::GENDER_MALE)
            ->set('login',          $user->nickname)
            ->set('birthdate',      $user->birthday)
            ->set('email',          $user->emails[0]['value'])
        ;

        return $user;
    }

    /**
     * Returns scope
     *
     * @access protected
     * @return string
     */

    protected function getScope()
    {
        return 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/plus.profile.emails.read';
    }

    /**
     * Returns oauth authorize url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://accounts.google.com/o/oauth2/auth';
    }

    /**
     * Returns profile url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAccessTokenUrl()
    {
        return 'https://accounts.google.com/o/oauth2/token';
    }

    /**
     * Returns profile url
     *
     * @access protected
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'https://www.googleapis.com/plus/v1/people/me';
    }

}
