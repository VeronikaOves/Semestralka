<?php
include "./includes/dbh.inc.php";
$userid = @$_POST["userid"];
$recipeid =@$_POST["recipeid"];


$sql = "DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$userid, $recipeid]);

?>