<?php
$dir="../";
include $dir."inc/session-start.php";
$title = "My Account";
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

//account form functionality
if (isset($_POST['submitaccount'])) {

    $name = addslashes(upperWords($_POST['name']));
    $email = addslashes(strtolower($_POST['email']));
    $city = addslashes(upperWords($_POST['city']));
    $country = addslashes(upperWords($_POST['country']));

    if (!$name || !$email || !$city || !$country){
        $error1 = "<div class='alert alert-danger'>Sorry, you must provide a name, email address, city and country.</div>";
    } else {
        $query = "UPDATE users SET name='".$name."', email='".$email."', city='".$city."', country='".$country."' WHERE ID='".$session_row['id']."'";
        if (!mysqli_query($link,$query)){
            $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
        }
    }
}

$query = "SELECT * from users WHERE id='".$session_row['id']."'";
$myrow = mysqli_fetch_assoc(mysqli_query($link,$query));

?>

<!DOCTYPE html>
<html lang="en">

<body>
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <div class="row justify-content-center notice shadow">
            <div class="col-md-12 ">
                <div class="row">
                    <div class="col-md-12">
                        <a class="home-icon" href="<?php echo $dir;?>home"><i class="fas fa-home"></i></a>
                        <h2><i class="fas fa-user-alt"></i>&nbsp;My Account</h2>
                        <?php echo $error1; ?>
                        <div class="blurb">
                            <p>Use the form below to edit your personal information.</p>
                            <form method="post">
                                <p><label for="name" class="display-none">Full Name</label><input required autocomplete="off" value="<?php echo $myrow['name']; ?>" type="text" class="form-control" name="name" placeholder="Full Name"></p>
                                <p><label for="email" class="display-none">Email</label><input required autocomplete="off" type="email" class="form-control" name="email" value="<?php echo $myrow['email']; ?>" placeholder="Email"></p>
                                <p><label for="city" class="display-none">City</label><input required autocomplete="off" type="text" class="form-control" name="city" value="<?php echo $myrow['city']; ?>" placeholder="City"></p>
                                <p><label for="country" class="display-none">Country</label><input required autocomplete="off" type="text" class="form-control" name="country" value="<?php echo $myrow['country']; ?>" placeholder="Country"></p>
                                <p><label for="submitaccount" class="display-none">Save Changes</label><input type="submit" class="btn btn-dark" name="submitaccount" value="Save Changes"></p>
                                <p><a href="<?php echo $dir;?>account/reset-password.php" title="Click here to reset your password" class="view-more">Reset Password</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
