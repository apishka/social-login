<?php

/**
 * Apishka social login main class
 */

class Apishka_SocialLogin_SocialLogin
{
    /**
     * Config
     *
     * @var array
     */

    private $_config = null;

    /**
     * Providers cache
     *
     * @var array
     */

    private $_providers_cache = array();

    /**
     * Construct
     *
     * @param array $config
     */

    public function __construct(array $config)
    {
        $this->initialize($config);
    }

    /**
     * Initialize
     *
     * @param array $config
     *
     * @return Apishka_SocialLogin_SocialLogin this
     */

    protected function initialize(array $config)
    {
        $this->_config = array_replace_recursive(
            require $this->getConfigFilePath(),
            $config
        );

        return $this;
    }

    /**
     * Returns provider
     *
     * @param string $alias
     *
     * @return Apishka_SocialLogin_ProviderInterface
     */

    public function getProvider($alias)
    {
        try
        {
            $this->_providers_cache[$alias] = Apishka_SocialLogin_ProviderRouter::apishka()->getItem($alias)
                ->initialize($this)
            ;
        }
        catch (LogicException $e)
        {
            throw new Apishka_SocialLogin_Exception($e->getMessage());
        }

        return $this->_providers_cache[$alias];
    }

    /**
     * Returns config
     *
     * @return array
     */

    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Returns storage
     *
     * @return Apishka_SocialLogin_StorageInterface
     */

    public function getStorage()
    {
        if (!array_key_exists('storage', $this->getConfig()) || !array_key_exists('class', $this->getConfig()['storage']))
            throw new LogicException('Wrong storage config');

        $class = $this->getConfig()['storage']['class'];

        return $class::apishka();
    }

    /**
     * Returns path to config file
     *
     * @return string
     */

    protected function getConfigFilePath()
    {
        return realpath(__DIR__ . '/../../../storage/config.php');
    }
}
