<?php
// Connects to your Database
require_once '_route.php';

// if not logged redirect to the sign in page
if(array_key_exists('is_logged', $_SESSION) === FALSE && $_SESSION['level'] < 2) {
    header('Location: index.php');
    exit;
}

if ($_POST && isset($_POST['submit'])) {
    $new_menu = array(
        "food_name" => $_POST['food_name'],
        "food_category" => $_POST['food_category'],
        "food_image_url" => $_POST['food_image_url'],
        "food_description" => $_POST['food_description'],
        "food_price" => $_POST['food_price'],
        "food_available"  => $_POST['food_available']
    );

    print_r(array_values($new_menu));

    try {
        $sql = sprintf(
            "INSERT INTO %s (%s) values (%s)",
            "menus",
            implode(", ", array_keys($new_menu)),
            ":" . implode(", :", array_keys($new_menu))
        );
        $stmt = $db->prepare($sql);
        $stmt->execute($new_menu);
        $stmt->closeCursor();

        echo "<p>" . $new_menu['food_name'] . " has been added -- <a href=\"adminMenuManager.php\">Back to Menu Manager</a></p>";

    } catch (PDOException $e) {
        $stmt->closeCursor();
        error_log('Database insert query failed: ' . $e->getMessage());
    } catch (Exception $e) {
        error_log('Error: ' . $e->getMessage());
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
    <h1>Add new menu</h1>
    <form method="post" id="menu_add">
        <label for="food_name">Food Name</label>
        <input type="text" name="food_name" id="food_name" required>
        <label for="food_category">Category</label>
        <select name="food_category" required>
            <option value="mains">Mains</option>
            <option value="drinks">Drinks</option>
            <option value="others">Others</option>
        </select>
        <label for="food_image_url">Image URL</label>
        <input type="url" name="food_image_url" id="food_image_url">
        <label for="food_description">Description</label>
        <textarea name="food_description" id="food_description" form="menu_add" cols="30" rows="10" required></textarea>
        <label for="food_price">Price (HK$)</label>
        <input type="number" name="food_price" id="food_price" required>

        <label for="food_available">Availability</label>
        <input type="radio" name="food_available" value="1" required>Available<br>
        <input type="radio" name="food_available" value="0">Out of Order<br>
        <br>
        <input class="but" type="submit" name="submit" value="Submit">
    </form>
    <br>
    <a href="adminMenuManager.php">Back to menu manager</a>
</body>
</html>
