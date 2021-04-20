<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER") {
    $location = "location: ".$dir."home";
    header($location);
}
$title = "User Manager";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";

//delete user form functionality
if (isset($_POST['submitdelete'])){
    $number = $_POST['number'];
    $query = "DELETE FROM users WHERE id='".$number."'";
    if (!mysqli_query($link,$query)){
        echo "error<br> with ".$query;
    }
}

//Delete User Modal
$query = "SELECT * from users";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="modal fade" id="deleteuser'.$row['id'].'" data-backdrop="static" tabindex="-1" aria-labelledby="delete user" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                  <h2>Delete User</h2>
                  <p>Are you sure you want to delete <strong>'.$row['name'].'</strong>?</p>
                  <p>This action cannot be undone.</p>
              </div>
              <div class="modal-footer">
                  <form method="post">
                        <label for="number" class="display-none">ID</label>
                      <input type="number" class="display-none"" value="'.$row['id'].'" name="number">

                      <label for="submitdelete" class="display-none">Yes</label>
                      <input type="submit" class="btn btn-dark" value="Yes" name="submitdelete">
                  </form>
                  <a href="" class="btn btn-dark" data-dismiss="modal">Close</a>
              </div>
            </div>
          </div>
        </div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin" class="view-more" title="Administration Dashboard"><i class="fas fa-angle-left"></i>&nbsp;Back to dashboard</a></p>
        <p>&nbsp;</p>
        <div class="row justify-content-around notice shadow">
            <div class="col-md-12 overflow">
                <?php
                userTable("PENDING");
                userTable("USER");
                userTable("ADMIN");
                userTable("SUPER");
                ?>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
