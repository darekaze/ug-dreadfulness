<?php
// Connects to your Database
require_once '_route.php';

// if not logged redirect to the sign in page
if(array_key_exists('is_logged', $_SESSION) === FALSE && $_SESSION['level'] < 2) {
    header('Location: index.php');
    exit;
}

try {
    $sql = "SELECT * FROM menus";

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
    <h1>Food Station - Menu Manager</h1>
    <ul>
        <li><a href="adminMenuAdd.php">Create</a> - Add new menu item</li>
    </ul>

    <h2>Menu List</h2>
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
            <td><?= escape($row["food_price"]); ?></td>
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
