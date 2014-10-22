<?php

/**
 * Apishka social login provider abstract
 *
 * @uses    Apishka_SocialLogin_ProviderInterface
 * @abstract
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

abstract class Apishka_SocialLogin_ProviderAbstract implements Apishka_SocialLogin_ProviderInterface
{
    /**
     * Base class
     *
     * @var Apishka_SocialLogin_SocialLogin
     * @access private
     */

    private $_base = null;

    /**
     * Construct
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
    }

    /**
     * Initialze
     *
     * @param Apishka_SocialLogin_SocialLogin $base
     * @access public
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
     * @access public
     * @return array
     */

    public function getAuthData()
    {
        return $this->getStorage()->get($this->getAlias(), 'auth_data');
    }

    /**
     * Returns user info
     *
     * @abstract
     * @access public
     * @return Apishka_SocialLogin_User
     */

    abstract public function getUserInfo();

    /**
     * Returns base
     *
     * @access protected
     * @return Apishka_SocialLogin_SocialLogin
     */

    protected function getBase()
    {
        return $this->_base;
    }

    /**
     * getStorage
     *
     * @access protected
     * @return void
     */

    protected function getStorage()
    {
        return $this->getBase()->getStorage();
    }

    /**
     * Returns provider config
     *
     * @access protected
     * @return string
     */

    protected function getProviderConfig()
    {
        return $this->getBase()->getConfig()['providers'][$this->getAlias()];
    }

    /**
     * Init callback url
     *
     * @access protected
     * @return void
     */

    protected function initCallbackUrl()
    {
        $url = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])
            $url .= 's';

        $url .= '://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $this->getStorage()
            ->set($this->getAlias(), 'callback_url', $url)
        ;
    }

    /**
     * Returns callback url
     *
     * @access protected
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
