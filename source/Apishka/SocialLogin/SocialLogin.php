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
        if (!array_key_exists($alias, $this->getConfig()['providers']))
            throw new InvalidArgumentException('Provider ' . var_export($alias) . ' not present in config');

        $class =  $this->getConfig()['providers'][$alias]['class'];

        $object = new $class();

        return $object
            ->initialize($this)
        ;
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

        return $object
            ->initialize($this)
        ;
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
