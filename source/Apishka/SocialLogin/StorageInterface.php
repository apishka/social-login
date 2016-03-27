<?php

/**
 * Apishka social login storage interface
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
