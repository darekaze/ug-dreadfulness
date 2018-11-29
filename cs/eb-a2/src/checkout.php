<?php

require_once '_route.php';

if(array_key_exists('is_logged', $_SESSION) === FALSE) {
  header('Location: index.php');
  exit;
}

// remove cart item
if(array_key_exists('delete_item',$_POST)){
  try {
    $id = $_POST["order_id"];

    $sql = "DELETE FROM orders WHERE order_id = :order_id";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':order_id', $id);
    $stmt->execute();
    $stmt->closeCursor();

    $success = "";

    echo "<p>One item has been successfully removed from cart!</p>";
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}

// Fire order
if(array_key_exists('fire',$_POST)){

}

try {
  $sql = "SELECT * FROM orders WHERE order_status='IN_CART'";

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
    <title>Food Station</title>
</head>
<body>
    <h1>Food Station --- Cart</h1>
    <?php if(!empty($result)): ?>
    <table>
        <thead>
            <tr>
            <th>Name</th>
            <th>Price</th>
            <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($result as $row): ?>
            <tr>
            <td><?= escape($row["order_item"]); ?></td>
            <td>HK$<?= escape($row["order_price"]); ?></td>
            <td>
            <form method="post">
              <input type="hidden" id="order_id" name="order_id"
                  value="<?= escape($row['order_id']); ?>">
              <input type="submit" name="delete_item" id="delete_item" value="remove"/>
            </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <?php
      $sum = 0.0;
      foreach($result as $row) {
        $sum += $row["order_price"];
      }
      echo '<h3>Total: HK$' . $sum . '</h3>';
    ?>
    <br>
    <form method="post">
        <input type="submit" name="fire" id="fire" value="FIRE YOUR ORDER!!"/>
    </form>
    <?php else: ?>
    <h3>No Item in cart!</h3>
    <?php endif; ?>
    <br>
    <a href="index.php">Back to home</a>
</body>
</html>
