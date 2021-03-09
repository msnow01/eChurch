<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER") {
    $location = "location: ".$dir."home";
    header($location);
}

$title = "User Manager Details";
include $dir."inc/header.php";
include $dir."inc/connection.php";
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
            echo "error<br> with ".$query;
        }
    }

    //update account type
    $usertype = $_POST['usertype'];
    $query = "UPDATE users SET type='".$usertype."' WHERE id='".$user_id."'";
    if (!mysqli_query($link,$query)){
        echo "error<br> with ".$query;
    }
}

//get info for this user
$query = "SELECT * from users WHERE id='".$user_id."'";
$user_row = mysqli_fetch_assoc(mysqli_query($link,$query));
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
