<?php

/**
 * Apishka social login provider abstract
 */

abstract class Apishka_SocialLogin_ProviderAbstract implements Apishka_SocialLogin_ProviderInterface
{
    /**
     * Base class
     *
     * @var Apishka_SocialLogin_SocialLogin
     */

    private $_base = null;

    /**
     * Construct
     */

    public function __construct()
    {
    }

    /**
     * Initialze
     *
     * @param Apishka_SocialLogin_SocialLogin $base
     *
     * @return Apishka_SocialLogin_ProviderInterface
     */

    public function initialize(Apishka_SocialLogin_SocialLogin $base)
    {
        $this->_base = $base;

        return $this;
    }

    /**
     * Returns auth data
     *
     * @return array
     */

    public function getAuthData()
    {
        return $this->getStorage()->get($this->getAlias(), 'auth_data');
    }

    /**
     * Returns user info
     *
     *
     * @return Apishka_SocialLogin_User
     */

    abstract public function getUserInfo();

    /**
     * Returns base
     *
     * @return Apishka_SocialLogin_SocialLogin
     */

    protected function getBase()
    {
        return $this->_base;
    }

    /**
     * Get storage
     *
     * @return Apishka_SocialLogin_StorageInterface
     */

    protected function getStorage()
    {
        return $this->getBase()->getStorage();
    }

    /**
     * Returns provider config
     *
     * @return string
     */

    protected function getProviderConfig()
    {
        return $this->getBase()->getConfig()['providers'][$this->getAlias()];
    }

    /**
     * Init callback url
     */

    protected function initCallbackUrl()
    {
        $url = 'http';

        if ($_SERVER['HTTP_X_FORWARDED_SCHEME'] == 'https' || $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || isset($_SERVER['HTTPS']))
            $url .= 's';

        $url .= '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $this->getStorage()
            ->set($this->getAlias(), 'callback_url', $url)
        ;
    }

    /**
     * Returns callback url
     *
     * @return string
     */

    protected function getCallbackUrl()
    {
        if (isset($this->getProviderConfig()['callback_url']))
            return $this->getProviderConfig()['callback_url'];

        if (!$this->getStorage()->get($this->getAlias(), 'callback_url'))
            $this->initCallbackUrl();

        return $this->getStorage()->get($this->getAlias(), 'callback_url');
    }
}
