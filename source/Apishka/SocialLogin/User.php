<?php

/**
 * Apishka social login user model
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_User
{
    /**
     * Constants
     */

    const GENDER_NONE   = 'none';
    const GENDER_FEMALE = 'female';
    const GENDER_MALE   = 'male';

    /**
     * Data
     *
     * @var array
     * @access private
     */

    private $_data = array();

    /**
     * Normalized
     *
     * @var array
     * @access private
     */

    private $_normalized = array();

    /**
     * Construct
     *
     * @access public
     * @return void
     */

    public function __construct(array $data = array(), array $normalized = array())
    {
        $this->_data        = $data;
        $this->_normalized  = $normalized;
    }

    /**
     * Sets id
     *
     * @param string    $id
     * @access public
     * @return Apishka_SocialLogin_User this
     */

    public function setId($id)
    {
        $this->_normalized['id'] = (string) $id;

        return $this;
    }

    /**
     * Returns id
     *
     * @access public
     * @return string
     */

    public function getId()
    {
        if (!isset($this->_normalized['id']))
            return null;

        return $this->_normalized['id'];
    }

    /**
     * Sets fullname
     *
     * @param string    $name
     * @access public
     * @return Apishka_SocialLogin_User this
     */

    public function setFullname($name)
    {
        $this->_normalized['fullname'] = $name;

        return $this;
    }

    /**
     * Returns fullname
     *
     * @access public
     * @return string
     */

    public function getFullname()
    {
        if (!isset($this->_normalized['fullname']))
            return null;

        return $this->_normalized['fullname'];
    }

    /**
     * Set login
     *
     * @param string    $login
     * @access public
     * @return Apishka_SocialLogin_User this
     */

    public function setLogin($login)
    {
        $this->_normalized['login'] = $login;

        return $this;
    }

    /**
     * Returns login
     *
     * @access public
     * @return string
     */

    public function getLogin()
    {
        if (!isset($this->_normalized['login']))
            return null;

        return $this->_normalized['login'];
    }

    /**
     * Set email
     *
     * @param string    $email
     * @access public
     * @return Apishka_SocialLogin_User this
     */

    public function setEmail($email)
    {
        $this->_normalized['email'] = $email;

        return $this;
    }

    /**
     * Returns email
     *
     * @access public
     * @return string
     */

    public function getEmail()
    {
        if (!isset($this->_normalized['email']))
            return null;

        return $this->_normalized['email'];
    }

    /**
     * Set gender
     *
     * @param string    $gender
     * @access public
     * @return Apishka_SocialLogin_User this
     */

    public function setGender($gender)
    {
        $this->_normalized['gender'] = $gender;

        return $this;
    }

    /**
     * Returns gender
     *
     * @access public
     * @return string
     */

    public function getGender()
    {
        if (!isset($this->_normalized['gender']))
            return null;

        return $this->_normalized['gender'];
    }

    /**
     * Set avatar
     *
     * @param string    $avatar
     * @access public
     * @return Apishka_SocialLogin_User this
     */

    public function setAvatar($avatar)
    {
        $this->_normalized['avatar'] = $avatar;

        return $this;
    }

    /**
     * Returns avatar
     *
     * @access public
     * @return string
     */

    public function getAvatar()
    {
        if (!isset($this->_normalized['avatar']))
            return null;

        return $this->_normalized['avatar'];
    }

    /**
     * Set birthday
     *
     * @param string    $birthday
     * @access public
     * @return Apishka_SocialLogin_User this
     */

    public function setBirthday($birthday)
    {
        try
        {
            $this->_normalized['birthday'] = new DateTime($birthday);
        }
        catch (Exception $e)
        {
            return $this;
        }

        return $this;
    }

    /**
     * Returns birthday
     *
     * @access public
     * @return DateTime
     */

    public function getBirthday()
    {
        if (!isset($this->_normalized['birthday']))
            return null;

        return $this->_normalized['birthday'];
    }

    /**
     * Set data
     *
     * @param array     $data
     * @access public
     * @return Apishka_SocialLogin_User this
     */

    public function setData(array $data)
    {
        $this->_data = $data;

        return $this;
    }

    /**
     * Returns original data
     *
     * @access public
     * @return array
     */

    public function getData()
    {
        return $this->_data;
    }
}
