<?php

// database config
$dbhost    = 'localhost:3306';
$dbname    = 'dbsa2';
$dbuser    = 'root';
$dbpassword  = '12345';
$dbcharset = 'utf8';

try {
    $db = new PDO(
        "mysql:host={$dbhost};dbname={$dbname};charset={$dbcharset}",
        $dbuser, $dbpassword, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '{$dbcharset}'"]
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;

} catch(Exception $e) {
    error_log('Connection failed: ' . $e->getMessage());
}
