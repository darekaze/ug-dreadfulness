<?php

require_once '_route.php';

if(array_key_exists('is_logged', $_SESSION) === FALSE && $_SESSION['level'] < 2) {
    header('Location: index.php');
    exit;
}

try {
  $sql = "SELECT * FROM orders WHERE order_status = 'PAID'";

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
    <title>Food Station - Admin panel</title>
</head>
<body>
    <h1>Food Station - Live Order</h1>
    <?php if(!empty($result)): ?>
    <table>
        <thead>
            <tr>
            <th>Price</th>
            <th>Name</th>
            <th>Ordered By</th>
            <th>Order Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($result as $row): ?>
            <tr>
            <td>HK$<?= escape($row["order_price"]); ?></td>
            <td><?= escape($row["order_item"]); ?></td>
            <td><?= escape($row["order_username"]); ?></td>
            <td><?= escape($row["order_status"]); ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <h3>No more order!</h3>
    <?php endif; ?>
    <br>
    <a href="adminHome.php">Back to Admin Panel</a>
</body>
</html>
