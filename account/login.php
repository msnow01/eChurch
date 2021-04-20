<?php
session_start();
if (isset($_SESSION['login_id'])) {
    $location = "location: ../home";
    header($location);
}

$dir="../";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

//login form functionality
if (isset($_POST['submitlogin'])) {

    $email = addslashes(strtolower($_POST['email']));
    $pass = addslashes($_POST['password']);
    $password = sha1($pass); //encrypt password

    //check that all fields are filled
    if (!$email || !$pass){
        $alert_message = printAlert('danger', 'Sorry, you must provide your email address and password.');
    } else {
        //check data base for account
        $query = "SELECT * from users WHERE email='".$email."' and password='".$password."' and type !='PENDING'";
        $result = mysqli_query($link,$query);
        $rowcount = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);

        // set session variables
        if ($rowcount == 1){
            $_SESSION['login_type'] = $row['type'];
            $_SESSION['login_id'] = $row['id'];
            $location = "location: ".$dir."home";
            header($location);
        } else {
            $alert_message = printAlert('danger', 'Incorrect email or password.<br>Please try again.');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = "Log In";
include $dir."inc/header.php";
?>

<body>
    <?php include $dir."inc/main-menu.php"; ?>
    <div data-aos="fade-in">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 white-box" data-aos="fade-in">
                    <h2>Log In</h2>
                    <p>If you do not have an existing account,<br>please <a href="<?php echo $dir;?>account/signup.php" class="view-more" title="Sign Up">sign up</a>.</p>
                    <?php echo $alert_message; ?>
                    <form method="post">
                        <p><label for="email" class="display-none">Email</label><input required autocomplete="off" type="email" class="form-control" name="email" id="email" placeholder="Email"></p>
                        <p><label for="password" class="display-none">Password</label><input required autocomplete="off" type="password" class="form-control" name="password" id="password" placeholder="Password"></p>
                        <p><input name="showpass" id="showpass" type="checkbox" onclick="showpassword()"><label for="showpass">&nbsp;Show Password</label></p>
                        <p><label for="submitlogin" class="display-none">Log In</label><input type="submit" class="btn btn-dark" name="submitlogin" id="submitlogin" value="Log In"></p>
                        <p><a href="<?php echo $dir; ?>account/reset-password.php" class="view-more" title="Click here to reset your password">Forgot Password?</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>

<script>
function showpassword() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
