echurch.com<?php
$dir="../";
$title = "Reset Password";
include $dir."inc/header.php";
include $dir."inc/connection.php";

if (isset($_POST['submitemail'])){

    $email = addslashes(strtolower($_POST['email']));

    if (!$email) {
        $error1 = "<div class='alert alert-danger'>Sorry, you must provide an email address.</div>";
    } else {

        $query = "SELECT * from users WHERE email='".$email."'";
        $rowcount = mysqli_num_rows(mysqli_query($link,$query));

        if ($rowcount == 0){
            $error1 = "<div class='alert alert-danger'>Sorry, there is no account associated with this email address.</div>";
        } else {
            $row = mysqli_fetch_assoc(mysqli_query($link,$query));
            $subject = "Password Reset - eChurch";
            $message = "<p>Hi ".$row['name'].",</p>";
            $message .= "<p>Please <a href='https://echurch.miriamsnow.com/account/reset-password-script.php?id=".sha1($row['id'])."'>click here</a> to reset your password for eChurch.</p>";
            $message .= "<p>If you did not make this request, please ignore this email.</p>";
            $header = "From: ".$site_title." <".$admin_email_address."> \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            $retval = mail ($email,$subject,$message,$header);
            if ($retval){
                $error1 = "<div class='alert alert-success'>Please check your email for password reset instructions.</div>";
            } else {
                $error1 = "<div class='alert alert-danger'>Sorry there was an error.<br>Please try again.</div>";
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
                    <?php echo $error1; ?>
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
