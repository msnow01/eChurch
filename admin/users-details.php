<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER") {
    $location = "location: ".$dir."home";
    header($location);
}

$title = "User Manager Details";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";
$user_id = $_GET['id'];

$columns = array();
$query = "SELECT * from categories";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {
        array_push($columns, $row['name']);
    }
}

//save changes form functionality
if (isset($_POST['submitchanges'])) {

    //category permissions
    foreach($columns as $val){
        $checked = $_POST[$val];
        if ($checked){
            $query = "UPDATE users SET $val='1' WHERE id='".$user_id."'";
        } else {
            $query = "UPDATE users SET $val='0' WHERE id='".$user_id."'";
        }
        if (!mysqli_query($link,$query)){
            $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
        } else {
            $alert_message = printAlert('success', 'Success!');
        }
    }

    //update account type
    $usertype = $_POST['usertype'];
    $query = "UPDATE users SET type='".$usertype."' WHERE id='".$user_id."'";
    if (!mysqli_query($link,$query)){
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    } else {
        $alert_message = printAlert('success', 'Success!');
    }

}

//get info for this user
$query = "SELECT * from users WHERE id='".$user_id."'";
$user_row = mysqli_fetch_assoc(mysqli_query($link,$query));

//send confirmation email if this is the first time editing
if (isset($_POST['submitchanges'])) {
    if ($user_row['confirmed'] == 0 && $user_row['usertype'] != "PENDING"){

        $to = $user_row['email'];
        $subject = "Account Approved";
        $message = "<p>Your request for access to ".$site_title." has been approved.</p>";
        $message .= "<p><a href='".$base_url."'>Click here</a> to visit the site.</p>";
        $header = "From: ".$site_title." <".$noreply_email_address."> \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";
        $retval = mail ($to,$subject,$message,$header);
        if ($retval == TRUE) {
            $alert_message = printAlert('success', 'Confirmation email sent!');
            $query = "UPDATE users SET confirmed='1' WHERE id='".$user_id."'";
            if (!mysqli_query($link,$query)){
                $alert_message = printAlert('danger', 'The confirmation email was sent, but there was an error updating the user.');
            }
        } else {
            $alert_message = printAlert('danger', 'Sorry, there was an error.<br>Please try again later.');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin/users.php" class="view-more" title="Back"><i class="fas fa-angle-left"></i>&nbsp;See all users</a></p>
        <p>&nbsp;</p>
        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <h2><?php echo $user_row['name']; ?></h2>
                <?php echo $alert_message; ?>
                <hr>
                <?php
                //can't manage your own permissions
                if ($user_id != $_SESSION['login_id']){
                ?>
                <form method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                                echo '<br><h3>TYPE</h3>';
                                foreach ($user_types as $val){
                                    echo '<p><label for="'.$val.'" class="display-none">'.$val.'</label>';
                                    echo '<input name="usertype" type="radio" value="'.$val.'"';
                                    if ($user_row['type'] == $val){
                                        echo ' checked ';
                                    }
                                    echo '>&nbsp;'.$val.'</p>';
                                }

                                foreach ($category_types as $val){
                                    printCheck($val, $user_row);
                                }

                                ?>
                        </div>
                    </div>
                    <hr>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <p><label for="submitchanges" class="display-none">Save Changes</label>
                                <input type="submit" name="submitchanges" value="Save Changes" class="btn btn-dark"></p>
                        </div>
                    </div>
                </form>
                <?php
            } else {
                echo "<p>You cannot manage your own permissions. Please contact your System Administrator to make updates.</p>";
            }
            ?>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
