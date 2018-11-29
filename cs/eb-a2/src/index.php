<?php
// Connects to Database
require_once '_route.php';

// add to order
if(array_key_exists('add',$_POST)){
    $new_order = array(
        "order_username" => $_SESSION['username'],
        "order_item" => $_POST['order_item'],
        "order_price" => $_POST['order_price'],
        "order_status" => "IN_CART",
    );
    try {
        $sql = sprintf(
            "INSERT INTO %s (%s) values (%s)",
            "orders",
            implode(", ", array_keys($new_order)),
            ":" . implode(", :", array_keys($new_order))
        );
        $stmt = $db->prepare($sql);
        $stmt->execute($new_order);
        $stmt->closeCursor();

        echo "<p>" . $new_order['order_item'] . " has been added to cart!</p>";

    } catch (PDOException $e) {
        $stmt->closeCursor();
        error_log('Database insert query failed: ' . $e->getMessage());
    } catch (Exception $e) {
        error_log('Error: ' . $e->getMessage());
    }
}

try {
    $sql = "SELECT * FROM menus WHERE food_available=1";

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
    <h1>Food Station</h1>
    
<?php 
if(array_key_exists('is_logged', $_SESSION) === FALSE) { ?>
    <h4>Login/Register for using this service, plus 10% off!</h4>
    <p><a href="userLogin.php">Login</a> | <a href="userRegister.php">Register</a></p>
<?php
} else { ?>
    <p>Welcome back, <strong><?= $_SESSION['username']; ?></strong> | <a href="checkout.php">Cart</a> | <a href="userLogout.php">Log out</a></p>
    <p style="color:red;">As you are our member, all items are 10% off!</p>
<?php 
} ?>
    <h2>Menu</h2>
    <!-- Show all food here, requires login to use cart -->
    <h3>Main Dishes</h3>
    <table>
        <thead>
            <tr>
            <th colspan=2>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($result as $row):
            if($row["food_category"] === "mains"): ?>
            <tr>
            <td><img src="<?= $row["food_image_url"]; ?>" alt=""/></td>
            <td><?= escape($row["food_name"]); ?></td>
            <td><?= escape($row["food_description"]); ?></td>
            <td>HK$<?= escape(
                array_key_exists('is_logged', $_SESSION) 
                ? $row["food_price"] * 0.9 : $row["food_price"]
            ); ?></td>
            <td>
            <?php if(array_key_exists('is_logged', $_SESSION)): ?>
            <form method="post">
                <input type="hidden" id="order_item" name="order_item"
                    value="<?= escape($row['food_name']); ?>">
                <input type="hidden" id="order_price" name="order_price"
                    value="<?= $row["food_price"] * 0.9; ?>">
                <input type="submit" name="add" id="add" value="Add to Cart"/>
            </form>
            <?php endif; ?>
            </td>
        </tr>
        <?php endif; endforeach; ?>
        </tbody>
    </table>
    <br>
    <h3>Drinks</h3>
    <table>
        <thead>
            <tr>
            <th colspan=2>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($result as $row):
            if($row["food_category"] === "drinks"): ?>
            <tr>
            <td><img src="<?= $row["food_image_url"]; ?>" alt=""/></td>
            <td><?= escape($row["food_name"]); ?></td>
            <td><?= escape($row["food_description"]); ?></td>
            <td>HK$<?= escape(
                array_key_exists('is_logged', $_SESSION) 
                ? $row["food_price"] * 0.9 : $row["food_price"]
            ); ?></td>
            <td>
            <?php if(array_key_exists('is_logged', $_SESSION)): ?>
            <form method="post">
                <input type="hidden" id="order_item" name="order_item"
                    value="<?= escape($row['food_name']); ?>">
                <input type="hidden" id="order_price" name="order_price"
                    value="<?= $row["food_price"] * 0.9; ?>">
                <input type="submit" name="add" id="add" value="Add to Cart"/>
            </form>
            <?php endif; ?>
            </td>
        </tr>
        <?php endif; endforeach; ?>
        </tbody>
    </table>
    <br>
    <h3>Others</h3>
    <table>
        <thead>
            <tr>
            <th colspan=2>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($result as $row):
            if($row["food_category"] === "others"): ?>
            <tr>
            <td><img src="<?= $row["food_image_url"]; ?>" alt=""/></td>
            <td><?= escape($row["food_name"]); ?></td>
            <td><?= escape($row["food_description"]); ?></td>
            <td>HK$<?= escape(
                array_key_exists('is_logged', $_SESSION) 
                ? $row["food_price"] * 0.9 : $row["food_price"]
            ); ?></td>
            <td>
            <?php if(array_key_exists('is_logged', $_SESSION)): ?>
            <form method="post">
                <input type="hidden" id="order_item" name="order_item"
                    value="<?= escape($row['food_name']); ?>">
                <input type="hidden" id="order_price" name="order_price"
                    value="<?= $row["food_price"] * 0.9; ?>">
                <input type="submit" name="add" id="add" value="Add to Cart"/>
            </form>
            <?php endif; ?>
            </td>
        </tr>
        <?php endif; endforeach; ?>
        </tbody>
    </table>
</body>
</html>