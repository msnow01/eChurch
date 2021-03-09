<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER" && $_SESSION['login_type'] != "ADMIN") {
    $location = "location: ".$dir."home";
    header($location);
}
$title = "Dashboard";
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

?>

<!DOCTYPE html>
<html lang="en">

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <h3>Welcome <?php echo $session_row['name']; ?>!</h3>
        <p>&nbsp;</p>
        <div class="row justify-content-around text-middle">
            <?php
            if ($_SESSION['login_type'] == "SUPER") {
                printModule("Users", "fas fa-users");
            }

            if ($_SESSION['login_type'] == "SUPER") {
                printModule("Audio", "fas fa-volume-up");
            }

            if ($_SESSION['login_type'] == "SUPER") {
                printModule("Videos", "fas fa-video");
            }
            ?>
        </div>
        <p>&nbsp;</p>
        <div class="row justify-content-around text-middle">
            <?php
            printModule("Notices", "fas fa-bell");
            printModule("Alerts", "fas fa-exclamation-triangle");
            printModule("Reports", "fas fa-clipboard");

            ?>
        </div>
        <p>&nbsp;</p>
        <div class="row justify-content-around text-middle">
            <?php
            if ($_SESSION['login_type'] == "SUPER") {
                printModule("Categories", "fas fa-check-square");
            }

            if ($_SESSION['login_type'] == "SUPER") {
                printModule("Files", "fas fa-file-alt");
            }
            
            if ($_SESSION['login_type'] == "SUPER") {
                printModule("Resources", "fas fa-link");
            }

            ?>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
