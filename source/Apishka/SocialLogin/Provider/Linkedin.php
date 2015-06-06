<?php

/**
 * Apishka social login provider: linkedin
 *
 * @uses    Apishka_SocialLogin_Provider_OauthAbstract
 *
 * @author  Alex "grevus" Lobtsov <alex@lobtsov.com>
 */

class Apishka_SocialLogin_Provider_Linkedin extends Apishka_SocialLogin_Provider_Oauth2Abstract
{
    /**
     * Returns alias
     *
     * @return string
     */

    public function getAlias()
    {
        return 'linkedin';
    }

    /**
     * Returns user info
     *
     * @return Apishka_SocialLogin_User
     */

    public function getUserInfo()
    {
        $url = \GuzzleHttp\Url::fromString($this->getProfileInfoUrl());
        $url->setQuery(
            array(
                'format'                => 'json',
                'oauth2_access_token'   => $this->getStorage()->get($this->getAlias(), 'access_token'),
            )
        );

        $info = $this->makeRequest($url);

        $data = json_decode($info, true);

        $user = new Apishka_SocialLogin_User($data);

        $dob = count($data['dateOfBirth'] == 2)
            ? implode('-', $data['dateOfBirth']) . '-0000'
            : implode('-', $data['dateOfBirth'])
        ;

        $user
            ->setId($data['id'])
            ->setFullname($data['firstName'] . ' ' . $data['lastName'])
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setBirthday($dob)
            ->setEmail($data['emailAddress'])
            ->setAvatar($data['pictureUrl'])
        ;

        return $user;
    }

    /**
     * Returns oauth authorize url
     *
     * @return string
     */

    protected function getOauthAuthorizeUrl()
    {
        return 'https://www.linkedin.com/uas/oauth2/authorization';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getOauthAccessTokenUrl()
    {
        return 'https://www.linkedin.com/uas/oauth2/accessToken';
    }

    /**
     * Returns profile url
     *
     * @return string
     */

    protected function getProfileInfoUrl()
    {
        return 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,public-profile-url,picture-url,email-address,date-of-birth,phone-numbers,summary)';
    }

}
