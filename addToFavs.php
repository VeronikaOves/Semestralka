<?php
    include "./includes/dbh.inc.php";
    $userid = $_POST["userid"];
    $recipeid = $_POST["recipeid"];

    $sql = "INSERT INTO favorites (user_id, recipe_id) VALUES (?,?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$userid, $recipeid]);
?>