<?php

/**
 * Apishka social login storage interface
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

interface Apishka_SocialLogin_Storage_StorageInterface
{
    /**
     * Set value
     *
     * @param string    $key
     * @param mixed     $value
     * @access public
     * @return Apishka_SocialLogin_Storage_StorageInterface
     */

    public function set($key, $value);

    /**
     * Get value
     *
     * @param string    $key
     * @access public
     * @return mixed
     */

    public function get($key);

    /**
     * Delete
     *
     * @param string    $key
     * @access public
     * @return Apishka_SocialLogin_Storage_StorageInterface
     */

    public function delete($key);
}
