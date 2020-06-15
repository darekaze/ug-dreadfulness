<?php

require_once '_route.php';

if (array_key_exists('is_logged', $_SESSION)) {
    header('Location: main.php');
    exit;
}

// if the login form is submitted
if ($_POST && isset($_POST['submit'])) {

    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

    if (_is_valid($username) === FALSE)
        _log_die('You did not fill in a username. <a href="login.php">Go Back</a>');

    if (_is_valid($password) === FALSE)
        _log_die('You did not fill in a password. <a href="login.php">Go Back</a>');

    $stmt = $db->prepare('SELECT * FROM `users` WHERE `user_name` = :username LIMIT 1');
    $stmt->execute(array(':username' => $username));

    $num = $db->query('SELECT FOUND_ROWS()')->fetchColumn();

    if($num == 0)
        _log_die('Incorrect username or password, please <a href="login.php">try again</a>.');

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if(password_verify($password, $row['user_password']) === FALSE)
        _log_die('Incorrect username or password, please <a href="login.php">try again</a>.');

    // if login is ok then we add a cookie
    $_SESSION['is_logged'] = TRUE;
    $_SESSION['userid']  = $row['user_id'];
    $_SESSION['username']  = $username;

    // redirect
    header('Location: main.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
<form method="post">
    <table border="0">
        <tr><td colspan=2><h1>Login</h1></td></tr>
        <tr><td colspan=2>
            Don't have an account? <a href="create_user.php">Sign Up here</a>
        </td></tr>
        <tr><td colspan=2></td></tr>
        <tr><td>Username:</td><td>
            <input type="text" name="username" maxlength="60">
        </td></tr>
        <tr><td>Password:</td><td>
            <input type="password" name="password" maxlength="20">
        </td></tr>
        <tr><td colspan="2" align="center">
            <input type="submit" name="submit" value="Login">
        </td></tr>
    </table>
</form>
</body>
</html>
