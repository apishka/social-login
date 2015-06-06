<?php

/**
 * Apishka social login storage interface
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

interface Apishka_SocialLogin_StorageInterface
{
    /**
     * Set value
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return Apishka_SocialLogin_StorageInterface
     */

    public function set();

    /**
     * Get value
     *
     * @param string $key
     *
     * @return mixed
     */

    public function get();

    /**
     * Delete
     *
     * @param string $key
     *
     * @return Apishka_SocialLogin_StorageInterface
     */

    public function delete();
}
