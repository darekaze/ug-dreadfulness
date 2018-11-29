<?php
// Connects to your Database
require_once '_route.php';

// if not logged redirect to the sign in page
if(array_key_exists('is_logged', $_SESSION) === FALSE && $_SESSION['level'] < 2) {
    header('Location: index.php');
    exit;
}

try {
    $sql = "SELECT `id`, `username`, `level` FROM users";

    $statement = $db->prepare($sql);
    $statement->execute();

    $result = $statement->fetchAll();

} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Food Station - Menu Manager</title>
</head>
<body>
    <h1>Food Station - User Database</h1>
    <table>
        <thead>
            <tr>
            <th>#</th>
            <th>Username</th>
            <th>Level</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($result as $row): ?>
            <tr>
            <td><?= escape($row["id"]); ?></td>
            <td><?= escape($row["username"]); ?></td>
            <td><?= escape($row["level"]); ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="adminHome.php">Back to admin panel</a>
</body>
</html>
