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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Food Station</title>
</head>
<body>
    <h1>Food Station</h1>
<?php 
if(array_key_exists('is_logged', $_SESSION) === FALSE) { ?>
    <p><a href="userLogin.php">Login</a> | <a href="userRegister.php">Register</a></p>
    <p>Please login or register to perform your order</p>
<?php
} else { ?>
    <p>Welcome back, <strong><?= $_SESSION['username']; ?></strong> | <a href="userLogout.php">Log out</a></p>
<?php 
} ?>
    <h2>Items</h2>
    <!-- Show all food here, requires login to add items -->

</body>
</html>