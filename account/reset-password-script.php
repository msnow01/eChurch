<?php
$dir="../";
$title = "Reset Password";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";


$idval = $_GET['id'];
$flag = FALSE;
$query = "SELECT * from users";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {
        if (sha1($row['id']) == $idval){
            $flag = TRUE;
            $idvalue = $row['id'];
        }
    }
}

if (!$flag){
    $alert_message = printAlert('danger', 'Sorry, you do not have access to this page.<br>Please contact your system administrator for help.');
}

if (isset($_POST['submitpass'])){

    $pass1 = addslashes($_POST['password1']);
    $pass2 = addslashes($_POST['password2']);

    if (!$pass1 || !$pass2){
        $alert_message = printAlert('danger', 'Sorry, you must fill in all password fields');
    } else {
        if ($pass1 != $pass2){
            $alert_message = printAlert('danger', 'Sorry, passwords do not match.');
        }  else {
            $newpass = sha1($pass1);
            $query = "UPDATE users SET password='".$newpass."' WHERE ID='".$idvalue."'";
            if (mysqli_query($link,$query)){
                $alert_message_text = "Successfully changed password!<br>Please <a class='view-more' href='".$dir."account/logout.php'>log in</a> here.";
                $alert_message = printAlert('success', $alert_message_text);
            } else {
                $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
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
                    <?php
                    if (!$flag){
                        echo $alert_message;
                    } else {
                    ?>
                    <p>Type your new password below.</p>
                    <?php echo $alert_message; ?>
                    <form method="post">
                        <p><label for="password1" class="display-none">Password*</label><input autocomplete="off" required type="password" class="form-control" id="password1" name="password1" placeholder="Password"></p>
                        <p><label for="password2" class="display-none">Confirm Password*</label><input autocomplete="off" required type="password" class="form-control" id="password2" name="password2" placeholder="Confirm Password"></p>
                        <p><input name="showpass" class="text-left" id="showpass" type="checkbox" onclick="showpassword()"><label for="showpass2">&nbsp;Show Password</label></p>
                        <p><label for="submitpass" class="display-none">Save Changes</label><input type="submit" class="btn btn-dark" name="submitpass" id="submitpass" Value="Save Changes"></p>
                    </form>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>

<script>
function showpassword() {
  var z = document.getElementById("password1");
  var y = document.getElementById("password2");
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
