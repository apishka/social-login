<?php

/**
 * Apishka social login storage session
 *
 * @uses    Apishka_SocialLogin_StorageInterface
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Storage_Session implements Apishka_SocialLogin_StorageInterface
{
    /**
     * Traits
     */

    use Apishka\EasyExtend\Helper\ByClassNameTrait;

    /**
     * Constants
     */

    const STORAGE_PREFIX = 'Apishka_SocialLogin';

    /**
     * Construct
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
     * @param string $key
     * @param mixed  $value
     *
     * @return Apishka_SocialLogin_StorageInterface
     */

    public function set()
    {
        $args   = func_get_args();
        $value  = array_pop($args);
        $key    = implode(':', $args);

        $_SESSION[self::STORAGE_PREFIX][$key] = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @param string $key
     *
     * @return mixed
     */

    public function get()
    {
        $key = implode(':', func_get_args());

        if (!array_key_exists($key, $_SESSION[self::STORAGE_PREFIX]))
            return;

        return $_SESSION[self::STORAGE_PREFIX][$key];
    }

    /**
     * Delete
     *
     * @param string $key
     *
     * @return Apishka_SocialLogin_StorageInterface
     */

    public function delete()
    {
        $key = implode(':', func_get_args());

        unset($_SESSION[self::STORAGE_PREFIX][$key]);

        return $this;
    }
}
