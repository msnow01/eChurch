<?php
$dir="../";
$title = "Reset Password";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";


if (isset($_POST['submitemail'])){

    $email = addslashes(strtolower($_POST['email']));

    if (!$email) {
        $alert_message = printAlert('danger', 'Sorry, you must provide an email address.');
    } else {

        $query = "SELECT * from users WHERE email='".$email."'";
        $rowcount = mysqli_num_rows(mysqli_query($link,$query));

        if ($rowcount == 0){
            $alert_message = printAlert('danger', 'Sorry, there is no account associated with this email address.');
        } else {
            $row = mysqli_fetch_assoc(mysqli_query($link,$query));
            $subject = "Password Reset - ".$site_title;
            $message = "<p>Hi ".$row['name'].",</p>";
            $message .= "<p>Please <a href='".$base_url."account/reset-password-script.php?id=".sha1($row['id'])."'>click here</a> to reset your password for ".$site_title.".</p>";
            $message .= "<p>If you did not make this request, please ignore this email.</p>";
            $header = "From: ".$site_title." <".$noreply_email_address."> \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            $retval = mail ($email,$subject,$message,$header);
            if ($retval){
                $alert_message = printAlert('success', 'Success! Please check your email for password reset instructions.');
            } else {
                $alert_message = printAlert('danger', 'Sorry there was an error.<br>Please try again.');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<body>
    <?php include $dir."inc/main-menu.php"; ?>
    <div data-aos="fade-in">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 white-box" data-aos="fade-right">
                    <h2>Reset Password</h2>
                    <p>Confirm your email address below to reset your password, or <a href="<?php echo $dir;?>home" class="view-more">log in</a> instead.</p>
                    <?php echo $alert_message; ?>
                    <form method="post">
                        <p><label for="email" class="display-none">Email</label><input required autocomplete="off" type="email" class="form-control" name="email" value="<?php echo $myrow['email']; ?>" placeholder="Email"></p>
                        <p><label for="submitemail" class="display-none">Confirm</label><input type="submit" class="btn btn-dark" name="submitemail" value="Confirm"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
