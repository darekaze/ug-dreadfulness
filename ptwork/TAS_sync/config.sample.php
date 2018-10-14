<?php
// To include, use: $configs = include('config.php');
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
        'loginEmail' => 'admin',
        'loginPassword' => 'password'
    ),

    // Field List for Hash Table
    'fieldList' => array(
        "name","description",
        "start_day","start_month","start_year","start_seconds",
        "end_day","end_month","end_year","end_seconds",
        "area","rooms[]","type","confirmed","private",
        "f_tas_import","f_tas_period","f_tas_sem","f_tas_user_comp_acc","f_tas_subject_code",
        "rep_type","rep_end_day","rep_end_month","rep_end_year","rep_day[]","rep_num_weeks",
        "returl","create_by","rep_id","edit_type","f_tas_syndate"
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