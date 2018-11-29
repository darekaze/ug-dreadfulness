<?php

require_once '_route.php';

if(array_key_exists('is_logged', $_SESSION) === FALSE && $_SESSION['level'] < 2) {
  header('Location: index.php');
  exit;
}

if (isset($_GET["food_id"])) {
  try {
    $id = $_GET["food_id"];

    $sql = "DELETE FROM menus WHERE food_id = :food_id";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':food_id', $id);
    $stmt->execute();
    $stmt->closeCursor();

    $success = "Item successfully deleted";
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
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
    <?php if ($success) echo $success; ?>
    <br>
    <a href="adminMenuManager.php">Back to menu manager</a>
</body>
</html>
