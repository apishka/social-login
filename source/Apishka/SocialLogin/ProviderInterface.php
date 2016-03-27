<?php

/**
 * Apishka social login interface for providers
 */

interface Apishka_SocialLogin_ProviderInterface
{
    /**
     * Returns alias for provider
     *
     * @return string
     */

    public function getAlias();
}
