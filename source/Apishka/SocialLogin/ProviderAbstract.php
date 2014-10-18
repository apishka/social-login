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
     * Returns abstract data
     *
     * @abstract
     * @access public
     * @return array
     */

    abstract public function getAuthData();

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
}
