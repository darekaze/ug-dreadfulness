<?php

session_start();

if(array_key_exists('is_logged', $_SESSION)) {
    unset($_SESSION['is_logged']);
    unset($_SESSION['username']);
}

header('Location: index.php', TRUE, 302);
exit;