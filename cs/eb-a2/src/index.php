<?php
// Connects to your Database
require_once '_route.php';

// fetch items...

// if loggin do discount 10%
if(array_key_exists('is_logged', $_SESSION)) {
    
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Food Station</title>
</head>
<body>
    <h1>Food Station</h1>
<?php 
if(array_key_exists('is_logged', $_SESSION) === FALSE) { ?>
    <p><a href="userLogin.php">Login</a> | <a href="userRegister.php">Register</a></p>
<?php 
} else { ?>
    <p>Welcome back, <strong><?= $_SESSION['username']; ?></strong> | <a href="userLogout.php">Log out</a></p>
<?php 
} ?>
    <h2>Items</h2>


</body>
</html>