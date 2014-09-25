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
            require $this->_getConfigFilePath(),
            $config
        );

        return $this;
    }

    /**
     * Returns path to config file
     *
     * @access protected
     * @return string
     */

    protected function _getConfigFilePath()
    {
        return realpath(__DIR__ . '/../../../storage/config.php');
    }
}
