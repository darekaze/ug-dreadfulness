<?php

require_once '_route.php';

if(array_key_exists('is_logged', $_SESSION) === FALSE) {
    header('Location: login.php');
    exit;
}

if ($_POST && isset($_POST['search']) && $_POST['query'] !== '') {
    // Handle search here
    $sql = 'SELECT `note_id`, `note_title`, `create_at`, `is_encrypted`
    FROM `notes`
    WHERE `user_id` = :userid
    AND (`note_title` LIKE :query OR `note_content` LIKE :query)
    ORDER BY `create_at` DESC';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            ':userid' => $_SESSION['userid'],
            ':query' => '%'.$_POST['query'].'%'
        ));
    
        $result = $stmt->fetchAll();
    
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
} else {
    // Normal main page
    $sql = 'SELECT `note_id`, `note_title`, `create_at`, `is_encrypted`
    FROM `notes`
    WHERE `user_id` = :userid
    ORDER BY `create_at` DESC';

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute(array(':userid' => $_SESSION['userid']));
    
        $result = $stmt->fetchAll();
    
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
    <title>Mainpage</title>
</head>
<body>
    <h1>Main.php</h1>
    <p>Welcome back, <strong><?= $_SESSION['username']; ?></strong> | <a href="logout.php">Log out</a></p>
    <h3>List of all your notes || <a href="create_note.php">Create new note</a> ||</h3>

    <!-- Search form -->
    <form method="post" id="search_form">
        <div class='search'>
            <input type="text" name="query" id="query" placeholder="Search">
            <input class="but" type="submit" name="search" value="search">
        </div>
    </form>

    <!-- List of notes -->
    <table>
        <thead>
            <tr>
            <th>Title</th>
            <th>Create At</th>
            <th>Encrypted?</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($result as $row): ?>
        <tr>
            <td>
                <a href="view.php?note_id=<?= escape($row["note_id"]); ?>">
                    <?= escape($row["note_title"]); ?>
                </a>
            </td>
            <td>
                <?= escape($row["create_at"]); ?>
            </td>
            <td>
                <?= escape($row["is_encrypted"] ? "Yes" : "No"); ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
