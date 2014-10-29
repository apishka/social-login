<?php

/**
 * Apishka social login provider: facebook
 *
 * @uses    Apishka_SocialLogin_ProviderAbstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Facebook extends Apishka_SocialLogin_ProviderAbstract
{
    /**
     * Default scope
     *
     * @var array
     * @access private
     */

    private $_default_scope = array(
        'email',
        'user_about_me',
        'user_birthday',
    );

    /**
     * Facebook session
     *
     * @var \Facebook\FacebookSession
     * @access private
     */

    private $_facebook_session = null;

    /**
     * Returns alias
     *
     * @access public
     * @return string
     */

    public function getAlias()
    {
        return 'facebook';
    }

    /**
     * Auth
     *
     * @access public
     * @return Apishka_SocialLogin_ProviderAbstract
     */

    public function auth()
    {
        \Facebook\FacebookSession::setDefaultApplication(
            $this->getProviderConfig()['client_id'],
            $this->getProviderConfig()['client_secret']
        );

        $helper = new \Facebook\FacebookRedirectLoginHelper($this->getCallbackUrl());
        try
        {
            $session = $helper->getSessionFromRedirect();

            if ($session === null)
            {
                $url = $helper->getLoginUrl(
                    $this->getScope()
                );

                header('Location: ' . $url, true, 302);
            }
        }
        catch(\Facebook\FacebookRequestException $ex)
        {
            throw new Apishka_SocialLogin_Exception('Provider return an error', 0, $exception);
        }

        if ($session)
            $this->setFacebookSession($session);

        return $this;
    }

    /**
     * Returns scope
     *
     * @access protected
     * @return array
     */

    protected function getScope()
    {
        if (isset($this->getProviderConfig['scope']))
            return $this->getProviderConfig['scope'];

        return $this->_default_scope;
    }

    /**
     * Returns user info
     *
     * @access public
     * @return Apishka_SocialLogin_User
     */

    public function getUserInfo()
    {
        $me = (new \Facebook\FacebookRequest($this->getFacebookSession(), 'GET', '/me'))
            ->execute()
            ->getGraphObject(
                \Facebook\GraphUser::className()
            )
        ;

        foreach ($me->asArray() as $key => $value)
            $user->set($key, $value);

        $user
            ->set('avatar',         'https://graph.facebook.com/' . $user->id . '/picture?type=large')
            ->set('fullname',       $user->name)
            ->set('gender',         $user->gender == 'female' ? Apishka_SocialLogin_User::GENDER_FEMALE : Apishka_SocialLogin_User::GENDER_MALE)
            ->set('birthdate',      $user->birthday)
        ;

        return $user;
    }

    /**
     * Set facebook session
     *
     * @param \Facebook\FacebookSession     $session
     * @access private
     * @return Apishka_SocialLogin_User this
     */

    private function setFacebookSession(\Facebook\FacebookSession $session)
    {
        $this->_facebook_session = $session;

        return $this;
    }

    /**
     * Returns facebook session
     *
     * @access private
     * @return \Facebook\FacebookSession
     */

    private function getFacebookSession()
    {
        if (!$this->_facebook_session)
            throw new Apishka_SocialLogin_Exception('Wrong facebook session');

        return $this->_facebook_session;
    }
}
