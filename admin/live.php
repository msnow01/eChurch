<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER") {
    $location = "location: ".$dir."home";
    header($location);
}
?>

<!DOCTYPE html>
<html lang="en">

<?php
$title = "Live Stream Manager";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

//save changes form functionality
if (isset($_POST['submitchanges'])) {
    $livelink = addslashes($_POST['livelink']);
    $query = "UPDATE content SET value='".$livelink."' WHERE type='live_stream_link'";
    if (mysqli_query($link,$query)){
        $alert_message = printAlert('success', 'Success!');
    } else {
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    }
}

include $dir."inc/header.php";

?>

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin" class="view-more" title="Administration Dashboard"><i class="fas fa-angle-left"></i>&nbsp;Back to dashboard</a></p>
        <?php echo $alert_message; ?>

        <p>&nbsp;</p>

        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <h2>Live Stream</h2>
                <hr>
                <br>
                <form method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <h3><label for="livelink">URL</label></h3>
                            <input type="text" name="livelink" class="form-control" value="<?php echo $live_stream_link; ?>">
                            <p>&nbsp;</p>
                            <hr>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><label for="submitchanges" class="display-none">Save Changes</label>
                            <input type="submit" name="submitchanges" value="Save Changes" class="btn btn-dark"></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
