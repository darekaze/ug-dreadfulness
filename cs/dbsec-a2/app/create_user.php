<?php

require_once '_route.php';

if (array_key_exists('is_logged', $_SESSION)) {
    header('Location: main.php');
    exit;
}

// This code runs if the form has been submitted
if ($_POST && isset($_POST['submit'])) {
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
    $passconf = trim(filter_input(INPUT_POST, 'passconf', FILTER_SANITIZE_STRING));

    if (_is_valid($username) === FALSE || _is_valid($password) === FALSE || _is_valid($passconf) === FALSE)
        _log_die('Missing some required fields');

    if ($password != $passconf)
        _log_die('The passwords did not match.');

    $stmt = $db->prepare('SELECT `user_name` FROM `users` WHERE `user_name` = :username LIMIT 1');
    $stmt->execute(array(':username' => $username));
    $stmt->closeCursor();

    $num = $db->query('SELECT FOUND_ROWS()')->fetchColumn();

    if ($num > 0)
        _log_die("Sorry, the username '{$username}' is already in use. <a href=\"create_user.php\">Go back</a>.</p>");

    try {
        $stmt = $db->prepare('INSERT INTO `users` (`user_name`, `user_password`) VALUES (:username, :password)');
        $stmt->execute(array(':username' => $username, ':password' => password_hash($password, PASSWORD_BCRYPT)));
        $stmt->closeCursor();

    } catch (PDOException $e) {
        $stmt->closeCursor();
        error_log('Database insert query failed: ' . $e->getMessage());
    } catch (Exception $e) {
        error_log('Error: ' . $e->getMessage());
    }
    echo "<h1>Registered</h1>";
    echo "<p>Thank you, you have registered - you may now <a href=\"login.php\">login</a>.</p>";
}

else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
<form method="post">
    <table border="0">
        <tr><td colspan=2><h1>Sign up</h1></td></tr>
        <tr><td colspan=2>
            <a href="login.php">Got an account?</a>
        </td></tr>
        <tr><td colspan=2></td></tr>
        <tr><td>Username:</td><td>
            <input type="text" name="username" maxlength="60">
        </td></tr>
        <tr><td>Password:</td><td>
            <input type="password" name="password" maxlength="20">
        </td></tr>
        <tr><td>Confirm Password:</td><td>
            <input type="password" name="passconf" maxlength="20">
        </td></tr>
        <tr><th colspan=2>
            <input type="submit" name="submit" value="Register">
        </th></tr>
    </table>
</form>
</body>
</html>

<?php
}
