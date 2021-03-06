<?php

require_once '_route.php';

if(array_key_exists('is_logged', $_SESSION) === FALSE && $_SESSION['level'] < 2) {
    header('Location: index.php');
    exit;
}

try {
    $sql = "SELECT * FROM menus";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetchAll();

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
    <h1>Food Station - Menu Manager</h1>
    <h2>Menu List</h2>
    <blockquote>
        <a href="adminMenuAdd.php">Add new menu</a>
    </blockquote>
    <table>
        <thead>
            <tr>
            <th>#</th>
            <th>Food Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Price</th>
            <th>Availability</th>
            <th colspan=2>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($result as $row): ?>
            <tr>
            <td><?= escape($row["food_id"]); ?></td>
            <td><?= escape($row["food_name"]); ?></td>
            <td><?= escape($row["food_category"]); ?></td>
            <td><?= escape($row["food_description"]); ?></td>
            <td>HK$<?= escape($row["food_price"]); ?></td>
            <td><?= escape($row["food_available"] ? "Yes" : "Disabled"); ?></td>
            <td><a href="adminMenuEdit.php?food_id=<?= escape($row["food_id"]); ?>">Edit</a></td>
            <td><a href="adminMenuDelete.php?food_id=<?= escape($row["food_id"]); ?>">Remove</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="adminHome.php">Back to admin panel</a>
</body>
</html>
