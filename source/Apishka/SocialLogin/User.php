<?php

/**
 * Apishka social login user
 *
 * Fields:
 *  id
 *  fullname
 *  login
 *  email
 *  gender
 *  avatar
 *  birthdate
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_User
{
    /**
     * Constants
     */

    const GENDER_ALL    = 0;
    const GENDER_FEMALE = 1;
    const GENDER_MALE   = 2;

    /**
     * Data
     *
     * @var array
     * @access private
     */

    private $_data = array();

    /**
     * Construct
     *
     * @access public
     * @return void
     */

    public function __construct(array $data = array())
    {
        $this->_data = $data;
    }

    /**
     * Magic get
     *
     * @param string    $name
     * @access public
     * @return mixed
     */

    public function __get($name)
    {
        return $this->_data[$name];
    }

    /**
     * Set
     *
     * @param string    $name
     * @param mixed     $value
     * @access public
     * @return Apishka_SocialLogin_User
     */

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;

        return $this;
    }

    /**
     * Get
     *
     * @param string    $name
     * @access public
     * @return mixed
     */

    public function get($name)
    {
        return $this->__get($name);
    }

    /**
     * Set
     *
     * @param string    $name
     * @param mixed     $value
     * @access public
     * @return Apishka_SocialLogin_User
     */

    public function set($name, $value)
    {
        return $this->__set($name, $value);
    }
}
