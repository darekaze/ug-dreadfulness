<?php

require_once '_route.php';

if(array_key_exists('is_logged', $_SESSION) === FALSE) {
    header('Location: login.php');
    exit;
}

$has_decrypted = false;

if (isset($_GET['note_id'])) {
  try {
    $sql = "SELECT * FROM `notes` WHERE note_id = :note_id AND `user_id` = :userid";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
      ':note_id' => $_GET['note_id'],
      ':userid' => $_SESSION['userid']
    ));

    $note = $stmt->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();	
  }
} else {
  echo "Something went wrong!";
  exit;
}

if ($_POST && isset($_POST['submit'])) {
  try {
    $sql = "SELECT `note_title`, `create_at`, `is_encrypted`,
        AES_DECRYPT(`note_content`, :note_password) as note_content
        FROM `notes`
        WHERE note_id = :note_id AND `user_id` = :userid";

    $stmt = $db->prepare($sql);
    $stmt->execute(array(
      ':note_id' => $_GET['note_id'],
      ':userid' => $_SESSION['userid'],
      ':note_password' => $_POST['note_password']
    ));

    $note = $stmt->fetch(PDO::FETCH_ASSOC);
    $has_decrypted = $note['note_content'] !== '';

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
    <title>View Note</title>
</head>
<body>
    <h1>Title: <?= escape($note['note_title']); ?></h1>
    <p><strong>Date: <?= escape($note['create_at']); ?></strong></p>

    <?php if($note['is_encrypted']) : ?>
    <div style="border: 1px solid red; padding: 0 10px; width: 400px;">
      <form method="post" id="note_decrypt">
          <p>This note is encrypted! Please enter the password to decrypt.</p>
          <p>If the password is correct, the content will be shown.</p>
          <label for="note_password">Password</label>
          <input type="password" name="note_password" id="note_password" required>
          <br>
          <input class="but" type="submit" name="submit" value="Submit">
      </form>
    </div>
    <?php endif; ?>
    
    <p class="content">
      <?= escape($note['is_encrypted'] && $has_decrypted === false
          ? base64_encode($note['note_content'])
          : $note['note_content']); 
      ?>
    </p>

    <br>
    <a href="main.php">Back to Main page</a>
</body>
</html>
