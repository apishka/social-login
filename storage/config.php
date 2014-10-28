<?php

return array (
  'storage' => 
  array (
    'class' => 'Apishka_SocialLogin_Storage_Session',
  ),
  'providers' => 
  array (
    'twitter' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Twitter',
    ),
    'vkontakte' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Vkontakte',
    ),
    'yahoo' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Yahoo',
    ),
    'yandex' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Yandex',
    ),
  ),
);
