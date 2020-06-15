<?php

require_once '_route.php';

// if not logged redirect to the sign in page
if(array_key_exists('is_logged', $_SESSION) === FALSE) {
    header('Location: main.php');
    exit;
}

if ($_POST && isset($_POST['submit'])) {
    $new_note = null;
    $sql = null;

    if ($_POST['note_password'] !== '') {
        // Handle note encryption SQL
        $new_note = array(
            "note_title" => $_POST['note_title'],
            "note_content" => $_POST['note_content'],
            "user_id" => $_SESSION['userid'],
            "is_encrypted" => 1,
            "note_password" => $_POST['note_password']
        );
        $sql = 'INSERT INTO notes (`note_title`, `note_content`, `user_id`, `is_encrypted`)
            VALUES (:note_title, AES_ENCRYPT(:note_content, :note_password, @init_vector), :user_id, :is_encrypted)';
    } else {
        $new_note = array(
            "note_title" => $_POST['note_title'],
            "note_content" => $_POST['note_content'],
            "user_id" => $_SESSION['userid']
        );
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            "notes",
            implode(", ", array_keys($new_note)),
            ":" . implode(", :", array_keys($new_note))
        );
    }

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($new_note);
        $stmt->closeCursor();

        echo "<p>Note has been created -- <a href=\"main.php\">Back to Main page</a></p>";

    } catch (PDOException $e) {
        $stmt->closeCursor();
        echo $e;
        error_log('Database insert query failed: ' . $e->getMessage());
    } catch (Exception $e) {
        echo $e;
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
    <title>Create Note</title>
</head>
<body>
    <h1>Create New Note</h1>
    <form method="post" id="note_add">
        <label for="note_title">Title</label>
        <input type="text" name="note_title" id="note_title" required>

        <label for="note_content">Content</label>
        <textarea name="note_content" id="note_content" form="note_add" cols="60" rows="12"></textarea>

        <div style="padding-bottom: 20px">
            <label for="note_password">If you want to encrypt this note, please enter a password:</label>
            <input type="password" name="note_password" id="note_password">
        </div>

        <input class="but" type="submit" name="submit" value="Submit">
    </form>
    <br>
    <a href="main.php">Back to Main page</a>
</body>
</html>
