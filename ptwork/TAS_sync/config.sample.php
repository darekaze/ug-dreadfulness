<?php
/* To include, use: $configs = include('config.php'); */
return (object) array(
    'TAS' => (object) array(
        'db' => '//xxx.comp.polyu.edu.hk:1521/xxx',
        'username' => 'xxx',
        'password' => 'xxx'
    ),
    'RBS' => (object) array(
        'db' => 'myxxx.comp.polyu.edu.hk/rbs',
        'username' => 'xxx',
        'password' => 'xxx',
        'URL' => 'https://xxx.comp.polyu.edu.hk/Web/reservation.php',
        'loginURL' => 'https://xxx.comp.polyu.edu.hk/Web/index.php',
        // New Apis
        'loginEmail' => 'admin',
        'loginPassword' => 'password',
        'Api_Auth' => 'https://devrbs.comp.polyu.edu.hk/Web/Services/index.php/Authentication/Authenticate',
        'Api_Reserve' => 'https://devrbs.comp.polyu.edu.hk/Web/Services/index.php/Reservations/'
    ),
    // TAS Sync Configuration
    'period' => '2018-2019',
    'sem' => '1',
    'start_day'=>'11',
    'start_month'=>'1',
    'start_year'=>'2018',
    'end_day'=>'16',
    'end_month'=>'4',
    'end_year'=>'2019'
);
?>