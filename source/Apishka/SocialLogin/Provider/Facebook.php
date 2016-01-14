<?php

/**
 * Apishka social login provider: facebook
 *
 * @uses    Apishka_SocialLogin_ProviderAbstract
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Facebook extends Apishka_SocialLogin_ProviderAbstract
{
    /**
     * Default scope
     *
     * @var array
     */

    private $_default_scope = array(
        'email',
        'user_about_me',
        'user_birthday',
    );

    /**
     * Facebook token
     *
     * @var \Facebook\Authentication\AccessToken
     */

    private $_facebook_token = null;

    /**
     * Returns alias
     *
     * @return string
     */

    public function getAlias()
    {
        return 'facebook';
    }

    /**
     * Auth
     *
     * @return Apishka_SocialLogin_ProviderAbstract
     */

    public function auth()
    {
        $fb     = $this->getFBObject();
        $helper = $fb->getRedirectLoginHelper();

        try
        {
            $token = $helper->getAccessToken();

            if ($token === null)
            {
                $url = $helper->getLoginUrl(
                    $this->getCallbackUrl(),
                    $this->getScope()
                );

                header('Location: ' . $url, true, 302);
            }
        }
        catch(\Facebook\Exceptions\FacebookSDKException $ex)
        {
            throw new Apishka_SocialLogin_Exception('Provider return an error', 0, $exception);
        }

        if ($token)
            $this->setFacebookToken($token);

        return $this;
    }

    /**
     * Returns scope
     *
     * @return array
     */

    protected function getScope()
    {
        if (isset($this->getProviderConfig()['scope']))
            return $this->getProviderConfig()['scope'];

        return $this->_default_scope;
    }

    /**
     * Returns user info
     *
     * @return Apishka_SocialLogin_User
     */

    public function getUserInfo()
    {
        $fb     = $this->getFBObject();

        try
        {
            $response = $fb->get('/me?fields=id,name,email,gender', $this->getFacebookToken()->getValue());
        }
        catch(Facebook\Exceptions\FacebookResponseException $exception)
        {
            throw new Apishka_SocialLogin_Exception('Provider return an error', 0, $exception);
        }
        catch(Facebook\Exceptions\FacebookSDKException $exception)
        {
            throw new Apishka_SocialLogin_Exception('Provider return an error', 0, $exception);
        }

        $data = $response->getGraphUser()->asArray();
        $user = new Apishka_SocialLogin_User($data);

        $user
            ->setId($data['id'])
            ->setEmail($data['email'])
            ->setAvatar('https://graph.facebook.com/' . $data['id'] . '/picture?type=large')
            ->setFullname($data['name'])
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setGender(
                $data['gender'] == 'female'
                    ? Apishka_SocialLogin_User::GENDER_FEMALE
                    : Apishka_SocialLogin_User::GENDER_MALE
            )
            ->setBirthday($data['birthday'])
        ;

        return $user;
    }

    /**
     * Get facebook object
     *
     * @return \Facebook\Facebook
     */

    private function getFBObject()
    {
        return new \Facebook\Facebook([
            'app_id' => $this->getProviderConfig()['client_id'],
            'app_secret' => $this->getProviderConfig()['client_secret'],
            'default_graph_version' => 'v2.2',
        ]);
    }

    /**
     * Set facebook token
     *
     * @param \Facebook\Authentication\AccessToken $token
     *
     * @return Apishka_SocialLogin_Provider_Facebook this
     */

    private function setFacebookToken(\Facebook\Authentication\AccessToken $token)
    {
        $this->getStorage()
            ->set($this->getAlias(), 'access_token',    $token->getValue())
            ->set($this->getAlias(), 'expiresAt',       $token->getExpiresAt())
            ->set($this->getAlias(), 'auth_data',       ['access_token' => $token->getValue(), 'expiresAt' => $token->getExpiresAt()])
        ;

        $this->_facebook_token = $token;

        return $this;
    }

    /**
     * Returns facebook token
     *
     * @return \Facebook\Authentication\AccessToken
     */

    private function getFacebookToken()
    {
        if (!$this->_facebook_token)
        {
            $this->_facebook_token = new \Facebook\Authentication\AccessToken(
                $this->getStorage()->get($this->getAlias(), 'access_token'),
                $this->getStorage()->get($this->getAlias(), 'expiresAt')
            );
        }

        return $this->_facebook_token;
    }
}
