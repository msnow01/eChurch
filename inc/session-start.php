<?php
session_start();
if (!isset($_SESSION['login_id'])) {
    $location = "location: ../";
    header($location);
}
?>
