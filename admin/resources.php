<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER") {
    $location = "location: ".$dir."home";
    header($location);
}

$title = "Resources Manager";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";

//delete resource form functionality
if (isset($_POST['submitdelete'])){
    $number = $_POST['number'];
    $rank = $_POST['rank'];

    //delete data from db
    $query = "DELETE FROM resources WHERE id='".$number."'";
    if (!mysqli_query($link,$query)){
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    }

    $query3 = "SELECT * from resources WHERE rank > $rank";
    if ($result = $link->query($query3)) {
        while ($row = $result->fetch_assoc()) {
            $currRank = $row['rank'] - 1;
            $query2 = "UPDATE resources SET rank='".$currRank."' WHERE ID='".$row['id']."'";
            if (!mysqli_query($link,$query2)){
                $alert_message = printAlert('danger', 'Sorry, there was an error updating the rank. Please try again.');
            }
        }
    }
}

//delete resource modal
$query = "SELECT * from resources";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="modal fade" id="deleteresource'.$row['id'].'" data-backdrop="static" tabindex="-1" aria-labelledby="delete resource" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                  <h2>Delete Resource</h2>
                  <p>Are you sure you want to delete <strong>'.$row['title'].'</strong>?</p>
                  <p>This action cannot be undone.</p>
              </div>
              <div class="modal-footer">
                  <form method="post">
                      <label for="number" class="display-none">ID</label>
                      <input type="number" class="display-none" name="number" value="'.$row['id'].'">

                      <label for="rank" class="display-none">Rank</label>
                      <input type="number" class="display-none" name="rank" value="'.$row['rank'].'">

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
        <?php echo $alert_message; ?>
        <p>&nbsp;</p>
        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <p><a href="<?php echo $dir;?>admin/resources-details.php" class="btn btn-dark">Add Resource</a></p>
            </div>
            <div class="col-md-12 overflow">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" width="30%">Title</th>
                            <th scope="col" width="20%">Category</th>
                            <th scope="col" width="10%" class="text-middle">Rank</th>
                            <th scope="col" width="10%" class="text-middle">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * from resources ORDER BY rank ASC";
                        if ($result = $link->query($query)) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='align-middle'>".$row['title']."</td>";
                                echo "<td class='align-middle'>";

                                //get current categories
                                $query2 = "SELECT * from categories WHERE type='RESOURCE'";
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

                                echo "</td>";
                                echo "<td class='text-middle align-middle'>".$row['rank']."</td>";
                                echo "<td class='text-middle align-middle'>";
                                echo "<a href='".$dir."admin/resources-details.php?id=".$row['id']."' title='Edit resource details'><i class='admin-link fas fa-edit'></i></a>";
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo "<a title='Delete resource' href='' data-toggle='modal' data-target='#deleteresource".$row['id']."'>";
                                echo "<i class='fas fa-trash-alt admin-link'></i></a>";
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
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
