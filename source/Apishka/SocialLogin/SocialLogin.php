<?php

/**
 * Apishka social login main class
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_SocialLogin
{
    /**
     * Config
     *
     * @var array
     * @access private
     */

    private $_config = null;

    /**
     * Providers cache
     *
     * @var array
     * @access private
     */

    private $_providers_cache = array();

    /**
     * Construct
     *
     * @param array     $config
     * @access public
     * @return void
     */

    public function __construct(array $config)
    {
        $this->initialize($config);
    }

    /**
     * Initialize
     *
     * @param array     $config
     * @access protected
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
     * @param string    $alias
     * @access public
     * @return Apishka_SocialLogin_ProviderInterface
     */

    public function getProvider($alias)
    {
        if (!array_key_exists($alias, $this->_providers_cache))
        {
            if (!isset($this->getConfig()['providers'][$alias]['class']))
                throw new InvalidArgumentException('Provider ' . var_export($alias, true) . ' not exists in config');

            $class =  $this->getConfig()['providers'][$alias]['class'];

            $object = new $class();

            $this->_providers_cache[$alias] = $object
                ->initialize($this)
            ;
        }

        return $this->_providers_cache[$alias];
    }

    /**
     * Returns config
     *
     * @access public
     * @return array
     */

    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Returns storage
     *
     * @access public
     * @return Apishka_SocialLogin_StorageInterface
     */

    public function getStorage()
    {
        if (!array_key_exists('storage', $this->getConfig()) || !array_key_exists('class', $this->getConfig()['storage']))
            throw new LogicException('Wrong storage config');

        $class = $this->getConfig()['storage']['class'];

        return new $class();
    }

    /**
     * Returns path to config file
     *
     * @access protected
     * @return string
     */

    protected function getConfigFilePath()
    {
        return realpath(__DIR__ . '/../../../storage/config.php');
    }
}
