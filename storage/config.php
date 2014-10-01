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
    'yahoo' => 
    array (
      'class' => 'Apishka_SocialLogin_Provider_Yahoo',
    ),
  ),
);