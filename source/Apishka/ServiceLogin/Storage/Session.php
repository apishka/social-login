<?php

/**
 * Apishka social login storage session
 *
 * @uses    Apishka_SocialLogin_Storage_StorageInterface
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Storage_Session implements Apishka_SocialLogin_Storage_StorageInterface
{
    /**
     * Constants
     */

    const STORAGE_PREFIX = 'Apishka_SocialLogin';

    /**
     * Construct
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        if (session_status() == PHP_SESSION_DISABLED || session_status() == PHP_SESSION_NONE)
            throw new LogicException('Session must be enabled and started');

        if (!array_key_exists(self::STORAGE_PREFIX, $_SESSION))
            $_SESSION[self::STORAGE_PREFIX] = array();
    }

    /**
     * Set value
     *
     * @param string    $key
     * @param mixed     $value
     * @access public
     * @return Apishka_SocialLogin_Storage_StorageInterface
     */

    public function set($key, $value)
    {
        $_SESSION[self::STORAGE_PREFIX][$key] = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @param string    $key
     * @access public
     * @return mixed
     */

    public function get($key)
    {
        if (!array_key_exists($key, $_SESSION[self::STORAGE_PREFIX]))
            return null;

        return $_SESSION[self::STORAGE_PREFIX][$key];
    }

    /**
     * Delete
     *
     * @param string    $key
     * @access public
     * @return Apishka_SocialLogin_Storage_StorageInterface
     */

    public function delete($key)
    {
        unset($_SESSION[self::STORAGE_PREFIX][$key]);

        return $this;
    }
}
