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
     */

    private $_data = array();

    /**
     * Normalized
     *
     * @var array
     */

    private $_normalized = array();

    /**
     * Construct
     */

    public function __construct(array $data = array(), array $normalized = array())
    {
        $this->_data        = $data;
        $this->_normalized  = $normalized;
    }

    /**
     * Sets id
     *
     * @param string $id
     *
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
     * @return string
     */

    public function getId()
    {
        if (!isset($this->_normalized['id']))
            return;

        return $this->_normalized['id'];
    }

    /**
     * Sets fullname
     *
     * @param string $name
     *
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
     * @return string
     */

    public function getFullname()
    {
        if (!isset($this->_normalized['fullname']))
            return;

        return $this->_normalized['fullname'];
    }

    /**
     * Sets first name
     *
     * @param string $name
     *
     * @return Apishka_SocialLogin_User this
     */

    public function setFirstName($name)
    {
        $this->_normalized['first_name'] = $name;

        return $this;
    }

    /**
     * Returns first name
     *
     * @return string
     */

    public function getFirstName()
    {
        if (!isset($this->_normalized['first_name']))
            return;

        return $this->_normalized['first_name'];
    }

    /**
     * Sets last name
     *
     * @param string $name
     *
     * @return Apishka_SocialLogin_User this
     */

    public function setLastName($name)
    {
        $this->_normalized['last_name'] = $name;

        return $this;
    }

    /**
     * Returns last name
     *
     * @return string
     */

    public function getLastName()
    {
        if (!isset($this->_normalized['last_name']))
            return;

        return $this->_normalized['last_name'];
    }

    /**
     * Set login
     *
     * @param string $login
     *
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
     * @return string
     */

    public function getLogin()
    {
        if (!isset($this->_normalized['login']))
            return;

        return $this->_normalized['login'];
    }

    /**
     * Set email
     *
     * @param string $email
     *
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
     * @return string
     */

    public function getEmail()
    {
        if (!isset($this->_normalized['email']))
            return;

        return $this->_normalized['email'];
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
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
     * @return string
     */

    public function getGender()
    {
        if (!isset($this->_normalized['gender']))
            return;

        return $this->_normalized['gender'];
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
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
     * @return string
     */

    public function getAvatar()
    {
        if (!isset($this->_normalized['avatar']))
            return;

        return $this->_normalized['avatar'];
    }

    /**
     * Set birthday
     *
     * @param string $birthday
     *
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
     * @return DateTime
     */

    public function getBirthday()
    {
        if (!isset($this->_normalized['birthday']))
            return;

        return $this->_normalized['birthday'];
    }

    /**
     * Set data
     *
     * @param array $data
     *
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
     * @return array
     */

    public function getData()
    {
        return $this->_data;
    }
}
