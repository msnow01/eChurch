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
$title = "Contact Manager";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

//save changes form functionality
if (isset($_POST['submitchanges'])) {
    $header_address_input = addslashes($_POST['header_address_input']);
    $site_title_input = addslashes($_POST['site_title_input']);
    $admin_email_address_input = addslashes($_POST['admin_email_address_input']);

    $query = "UPDATE content SET value='".$header_address_input."' WHERE type='header_address'";
    if (!mysqli_query($link,$query)){
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    }

    $query = "UPDATE content SET value='".$admin_email_address_input."' WHERE type='admin_email_address'";
    if (!mysqli_query($link,$query)){
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    }

    $query = "UPDATE content SET value='".$site_title_input."' WHERE type='site_title'";
    if (!mysqli_query($link,$query)){
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
                <h2>Contact Details</h2>
                <hr>
                <br>
                <form method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <h3><label for="site_title_input">Website Title</label></h3>
                            <input type="text" name="site_title_input" class="form-control" value="<?php echo $site_title; ?>">
                            <p>&nbsp;</p>

                            <h3><label for="admin_email_address_input">Email Address</label></h3>
                            <input type="text" name="admin_email_address_input" class="form-control" value="<?php echo $admin_email_address; ?>">
                            <p>&nbsp;</p>

                            <h3><label for="header_address_input">Address / Details</label></h3>
                            <textarea name="header_address_input" class="form-control" rows="5"><?php echo $header_address; ?></textarea>
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
