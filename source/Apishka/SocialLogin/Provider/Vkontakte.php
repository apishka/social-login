<?php

/**
 * Apishka social login provider: vkontakte
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Vkontakte extends Apishka_SocialLogin_Provider_Oauth2Abstract
{
    /**
     * Returns alias
     *
     * @access public
     * @return string
     */

    public function getAlias()
    {
        return 'vkontakte';
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
                'uid'           => $this->getAuthData()['user_id'],
                'fields'        => 'first_name,last_name,nickname,screen_name,sex,bdate,photo_big',
                'access_token'  => $this->getStorage()->get($this->getAlias(), 'access_token'),
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
            ->set('fullname',       $user->first_name . ' ' . $user->last_name)
            ->set('gender',         $user->sex)
            ->set('login',          $user->nickname)
            ->set('birthdate',      $user->birthdate)
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
        return 'https://oauth.vk.com/authorize';
    }

    /**
     * Returns profile url
     *
     * @access protected
     * @return string
     */

    protected function getOauthAccessTokenUrl()
    {
        return 'https://oauth.vk.com/oauth/access_token';
    }

    /**
     * Returns profile url
     *
     * @access protected
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'https://api.vk.com/method/getProfiles';
    }

}
