<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER" && $_SESSION['login_type'] != "ADMIN") {
    $location = "location: ".$dir."home";
    header($location);
}

$title = "Notices Manager";
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

//delete notice form functionality
if (isset($_POST['submitdelete'])){
    $number = $_POST['number'];

    //delete images
    $query = "SELECT * from notices WHERE id='".$number."'";
    $row = mysqli_fetch_assoc(mysqli_query($link,$query));
    for ($i=0;$i<=$maximages;$i++){
        $val = "image".$i;
        $path = $_SERVER['DOCUMENT_ROOT']."/notices/noticeimages/".$row[$val];
        $check = unlink($path);
    }

    //delete data from db
    $query = "DELETE FROM notices WHERE id='".$number."'";
    if (!mysqli_query($link,$query)){
        $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
    }
}

//mail notice form functionality
if (isset($_POST['submitmail'])){

    //get checkbox values
    $checkboxes = array();
    $query = "SELECT * from categories WHERE type='NOTICE'";
    if ($result = $link->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $val = $row['name'];
            if ($_POST[$val]){
                array_push($checkboxes, $val);
            }
        }
    }

    //create list of  addresses
    $addresses = array();
    $query = "SELECT * from users";
    if ($result = $link->query($query)) {
        while ($row = $result->fetch_assoc()) {
            if ($row['type'] != "PENDING"){
                foreach ($checkboxes as $val){
                    if ($row[$val]){
                        if(!in_array($row['email'], $addresses)){
                            array_push($addresses, $row['email']);
                        }
                    }
                }
            }
        }
    }

    //create email
    $number = $_POST['number'];
    $query = "SELECT * from notices WHERE id='".$number."'";
    $row = mysqli_fetch_assoc(mysqli_query($link,$query));

    $subject = $row['title'];
    $message = $row['text'];
    $header = "From: ".$site_title." <".$admin_email_address."> \r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";

    //send mail
    $error1 = "<div class='alert alert-success'>";
    foreach ($addresses as $user){
        $check = FALSE;
        $check = mail($user,$subject,$message,$header);
        if ($check == TRUE) {
            $error1 .= "Successfully sent to ".$user."<br>";
        } else {
            $error1 .= "Could not send to ".$user."<br>";
        }
    }
    $error1 .= "</div>";
}


//delete notice
$query = "SELECT * from notices";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="modal fade" id="deletenotice'.$row['id'].'" data-backdrop="static" tabindex="-1" aria-labelledby="delete notice" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                  <h2>Delete Notice</h2>
                  <p>Are you sure you want to delete <strong>'.$row['title'].'</strong>?</p>
                  <p>This action cannot be undone.</p>
              </div>
              <div class="modal-footer">
                  <form method="post">
                      <label for="number" class="display-none">ID</label>
                      <input type="number" class="display-none" name="number" value="'.$row['id'].'">

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

// e-mail notice
$query = "SELECT * from notices";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="modal fade" id="mailnotice'.$row['id'].'" data-backdrop="static" tabindex="-1" aria-labelledby="email notice" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                  <h2>E-Mail Notice</h2>
                  <p>Who do you want to send <strong>'.$row['title'].'</strong> to?</p>
                  <form method="post">
                      <input type="number" class="display-none" name="number" value="'.$row['id'].'">';
                      $query2 = "SELECT * from categories WHERE type='NOTICE'";
                      if ($result2 = $link->query($query2)) {
                          while ($row2 = $result2->fetch_assoc()) {
                              echo '<p><input type="checkbox" name="'.$row2['name'].'">';
                              echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                              echo '<label for="'.$row2['name'].'">'.$row2['full_name'].'</label></p>';
                          }
                      }
                      echo '</div><div class="modal-footer">';
                      echo '<label for="submitmail" class="display-none">Send</label>
                      <input type="submit" class="btn btn-dark" value="Send" name="submitmail">
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
        <?php echo $error1; ?>
        <p>&nbsp;</p>
        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <p><a href="<?php echo $dir;?>admin/notices-details.php" class="btn btn-dark">Add Notice</a></p>
            </div>
            <div class="col-md-12 overflow">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" width="30%">Title</th>
                            <th scope="col" width="20%">Date</th>
                            <th scope="col" width="20%">Category</th>
                            <th scope="col" width="10%" class="text-middle">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * from notices ORDER BY date DESC";
                        if ($result = $link->query($query)) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='align-middle'>".$row['title']."</td>";
                                echo "<td class='align-middle'>".dateFormat($row['date'])."</td>";
                                echo "<td class='align-middle'>";

                                //get current categories
                                $query2 = "SELECT * from categories WHERE type='NOTICE'";
                                if ($result2 = $link->query($query2)) {
                                    while ($cat_row = $result2->fetch_assoc()) {
                                        $notice_categories = explode(", ", $row['category']);
                                        foreach ($notice_categories as $val){
                                            if ($val == $cat_row['name']){
                                                echo $cat_row['full_name']."<br>";
                                            }
                                        }
                                    }
                                }

                                echo "</td><td class='text-middle align-middle'>";
                                echo "<a href='".$dir."admin/notices-details.php?id=".$row['id']."' title='Edit notice details'><i class='admin-link fas fa-edit'></i></a>";
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo "<a title='Delete notice' href='' data-toggle='modal' data-target='#deletenotice".$row['id']."'>";
                                echo "<i class='fas fa-trash-alt admin-link'></i></a>";
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo "<a title='Email notice' href='' data-toggle='modal' data-target='#mailnotice".$row['id']."'>";
                                echo '<i class="far fa-envelope admin-link"></i></a>';
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
