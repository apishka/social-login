<?php

/**
 * Apishka social login interface for providers
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

interface Apishka_SocialLogin_ProviderInterface
{
    /**
     * Returns alias for provider
     *
     * @access public
     * @return string
     */

    public function getAlias();
}
