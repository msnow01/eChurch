<?php
$dir="../";
include $dir."inc/connection.php";
$title = "Sign Up";
include $dir."inc/header.php";
include $dir."inc/functions.php";

//sign up form functionality
if (isset($_POST['submitsignup'])) {

    $name = addslashes(upperWords($_POST['name']));
    $email = addslashes(strtolower($_POST['email']));
    $city = addslashes(upperWords($_POST['city']));
    $country = addslashes(upperWords($_POST['country']));
    $pass = addslashes($_POST['password2']);
    $pass3 = addslashes($_POST['password3']);
    $password = sha1($pass); //encrypt password
    $error1 = "";

    //check that all fields are filled
    if (!$name || !$email || !$pass || !$city || !$country){
        $error1 = "<div class='alert alert-danger'>Sorry, you must provide your name, email address, city, country, and a password.</div>";
    } else if ($pass != $pass3){
        $error1 = "<div class='alert alert-danger'>Passwords do not match.</div>";
    } else {
        //check for existing account
        $querycheck = "SELECT * from users WHERE email='".$email."'";
        $rowcount = mysqli_num_rows(mysqli_query($link,$querycheck));
        if ($rowcount > 0){
            $error1 = "<div class='alert alert-danger'>Sorry, there is already an account associated with this email address.</div>";
        } else {
            $query = "INSERT INTO users (email, password, name, city, country, type) VALUES ('".$email."', '".$password."', '".$name."', '".$city."', '".$country."', 'PENDING')";

            if (mysqli_query($link,$query)){

                //email to admin for new sign up action
                $to = $admin_email_address;
                $subject = "New Account";
                $message = "<p>A new account has been created for the following contact:</p>";
                $message .= "<p>Name: ".$name."</p>";
                $message .= "<p>Email: ".$email."</p>";
                $message .= "<p>City: ".$city."</p>";
                $message .= "<p>Country: ".$country."</p>";
                $message .= "<p><a href='https://echurch.miriamsnow.com/admin'>Click here</a> to verify this account.</p>";
                $header = "From: ".$site_title." <".$admin_email_address."> \r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-type: text/html\r\n";
                $retval = mail ($to,$subject,$message,$header);
                if ($retval == TRUE) {
                    $error1 = "<div class='alert alert-success'>Thank you for signing up. You will receive a confirmation email shortly.</div>";
                } else {
                    $error1 = "<div class='alert alert-danger'>Sorry, there was an error.<br>Please try again later.</div>";
                }


                //send email to person signing up
                $subject = "Sign Up Confirmation";
                $message = "<p>Thank you for requesting access to eChurch website.</p><p>Your request is being reviewed and if approved, your access will be granted within 24 hours.</p>";
                $header = "From: ".$site_title." <".$admin_email_address."> \r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-type: text/html\r\n";
                $retval = mail ($email,$subject,$message,$header);
                if (!$retval) {
                    $error1 = "<div class='alert alert-danger'>Sorry, there was an error.<br>Please try again later.</div>";
                }

            } else {
                $error1 = "<div class='alert alert-danger'>Sorry, there was an error.<br>Please try again later.</div>";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<body>
    <div class="bible"></div>
    <?php include $dir."inc/main-menu.php"; ?>
    <div data-aos="fade-in">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 white-box" data-aos="fade-in">
                    <h2>Sign Up</h2>
                    <p>If you have an existing account,<br>please <a href="<?php echo $dir;?>account/login.php" class="view-more" title="Log In">log in</a>.</p>
                    <?php echo $error1; ?>
                    <form method="post">
                        <p><label for="name" class="display-none">Full Name</label><input required autocomplete="off" type="text" class="form-control" name="name" id="name" placeholder="Full Name"></p>
                        <p><label for="email" class="display-none">Email</label><input required autocomplete="off" type="email" class="form-control" name="email" id="email" placeholder="Email"></p>
                        <p><label for="city" class="display-none">City</label><input required autocomplete="off" type="text" class="form-control" name="city" id="city" placeholder="City"></p>
                        <p><label for="country" class="display-none">Country</label><input required autocomplete="off" type="text" class="form-control" name="country" id="country" placeholder="Country"></p>
                        <p><label for="password2" class="display-none">Password</label><input autocomplete="off" required type="password" class="form-control" name="password2" id="password2" placeholder="Password"></p>
                        <p><label for="password3" class="display-none">Confirm Password</label><input autocomplete="off" required type="password" class="form-control" name="password3" id="password3" placeholder="Confirm Password"></p>
                        <p><input name="showpass2" class="text-left" id="showpass2" type="checkbox" onclick="showpassword2()"><label for="showpass2">&nbsp;Show Password</label></p>
                        <p><label for="submitsignup" class="display-none">Sign Up</label><input type="submit" class="btn btn-dark" name="submitsignup" id="submitsignup" Value="Sign Up"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>

<script>
function showpassword2() {
  var z = document.getElementById("password2");
  var y = document.getElementById("password3");
  if (z.type === "password") {
    z.type = "text";
  } else {
    z.type = "password";
  }
  if (y.type === "password") {
    y.type = "text";
  } else {
    y.type = "password";
  }
}
</script>
