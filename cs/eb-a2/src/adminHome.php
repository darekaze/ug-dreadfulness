<?php

require_once '_route.php';

if(array_key_exists('is_logged', $_SESSION) === FALSE && $_SESSION['level'] < 2) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Food Station - Admin panel</title>
</head>
<body>
    <h1>Food Station - Admin panel</h1>
<?php 
if(array_key_exists('is_logged', $_SESSION) === FALSE) { ?>
    <p><a href="userLogin.php">Login</a></p>
<?php 
} else { ?>
    <p>Welcome back, <strong><?= $username; ?></strong> ! | <a href="userLogout.php">Log out</a></p>
<?php 
} ?>
    <ul>
        <li><a href="adminViewLiveOrder.php">Live Order</a></li>
        <li><a href="adminMenuManager.php">Menu Manager</a></li>
        <li><a href="adminViewUser.php">View All User</a></li>
    </ul>
</body>
</html>