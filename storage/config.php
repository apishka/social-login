<?php

return array (
  'storage' => 
  array (
    'class' => 'Apishka_SocialLogin_Storage_Session',
  ),
  'providers' => 
  array (
    'facebook' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Facebook',
    ),
    'google' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Google',
    ),
    'linkedin' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Linkedin',
    ),
    'mailru' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Mailru',
    ),
    'odnoklassniki' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Odnoklassniki',
    ),
    'twitter' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Twitter',
    ),
    'vkontakte' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Vkontakte',
    ),
    'yandex' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Yandex',
    ),
  ),
);