<?php
// Connects to your Database
require_once '_route.php';

// if not logged redirect to the sign in page
if(array_key_exists('is_logged', $_SESSION) === FALSE && $_SESSION['level'] < 2) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['submit'])) {
  $menu = array(
    "food_id" => $_GET['food_id'],
    "food_name" => $_POST['food_name'],
    "food_category" => $_POST['food_category'],
    "food_image_url" => $_POST['food_image_url'],
    "food_description" => $_POST['food_description'],
    "food_price" => $_POST['food_price'],
    "food_available"  => $_POST['food_available']
  );
  try {
    $sql = "UPDATE menus 
            SET food_name = :food_name, 
              food_category = :food_category, 
              food_image_url = :food_image_url, 
              food_description = :food_description,
              food_price = :food_price,
              food_available = :food_available
            WHERE food_id = :food_id";
    $stmt = $db->prepare($sql);
    $stmt->execute($menu);
    $stmt->closeCursor();

    echo "<p>" . $menu['food_name'] . " has been updated -- <a href=\"adminMenuManager.php\">Back to Menu Manager</a></p>";

  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
}

if (isset($_GET['food_id'])) {
  try {
    $id = $_GET['food_id'];

    $sql = "SELECT * FROM menus WHERE food_id = :food_id";
    $statement = $db->prepare($sql);
    $statement->bindValue(':food_id', $id);
    $statement->execute();

    $menu = $statement->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();	
  }
} else {
  echo "Something went wrong!";
  exit;
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
    <h1>Edit menu</h1>
    <form method="post" id="menu_add">
        <label for="food_name">Food Name</label>
        <input type="text" name="food_name" id="food_name" required
          value="<?= escape($menu['food_name']); ?>">
        <label for="food_category">Category</label>
        <select name="food_category" required>
            <option value="mains">Mains</option>
            <option value="drinks">Drinks</option>
            <option value="others">Others</option>
        </select>
        <label for="food_image_url">Image URL</label>
        <input type="url" name="food_image_url" id="food_image_url"
          value="<?= escape($menu['food_image_url']); ?>">
        <label for="food_description">Description</label>
        <textarea name="food_description" id="food_description" form="menu_add" cols="30" rows="10" required><?=
          escape($menu['food_description']);
        ?></textarea>
        <label for="food_price">Price (HK$)</label>
        <input type="number" name="food_price" id="food_price" required
          value="<?= escape($menu['food_price']); ?>">

        <label for="food_available">Availability</label>
        <input type="radio" name="food_available" value="1" required checked="checked">Available<br>
        <input type="radio" name="food_available" value="0">Out of Order<br>
        <br>
        <input class="but" type="submit" name="submit" value="Submit">
    </form>
    <br>
    <a href="adminMenuManager.php">Back to menu manager</a>
</body>
</html>
