<?php
session_start();

$_SESSION = array();

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

session_start();
$_SESSION["message"] = "Vous avez été déconnecté avec succès.";
$_SESSION["message_type"] = "success";

header("location: ../index.php");
exit;
?>
