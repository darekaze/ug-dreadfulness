<?php
// Connects to your Database
require_once '_route.php';

// if not logged redirect to the sign in page
if(array_key_exists('is_logged', $_SESSION) === FALSE && $_SESSION['level'] < 2) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Food Station - Menu Manager</title>
</head>
<body>
    <h1>Food Station - Menu Manager</h1>
    <ul>
        <li><a href="adminMenuAdd.php">Create</a> - Add new menu item</li>
    </ul>
</body>
</html>
