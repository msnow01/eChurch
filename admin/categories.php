<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER") {
    $location = "location: ".$dir."home";
    header($location);
}
$title = "Category Manager";
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

if (isset($_POST['submitdelete'])){
    $number = $_POST['number'];
    $name = $_POST['name'];

    //remove from list of categories
    $query = "DELETE FROM categories WHERE id='".$number."'";
    if (!mysqli_query($link,$query)){
        $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
    }

    //remove users column
    $query = "ALTER TABLE users DROP COLUMN ".$name;
    if (!mysqli_query($link,$query)){
        $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
    }
}

if (isset($_POST['submitedit'])){
    $number = $_POST['number'];
    $new_name = addslashes($_POST['new_name']);

    //remove from list of categories
    $query = "UPDATE categories SET full_name='".$new_name."' WHERE id='".$number."'";
    if (!mysqli_query($link,$query)){
        $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
    }
}

if (isset($_POST['submitpermissions'])){
    $name = $_POST['name'];

    //update all from list of users
    $query = "UPDATE users SET ".$name."='1'";
    if (!mysqli_query($link,$query)){
        $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
    } else {
        $error1 = "<div class='alert alert-success'>Success!</div>";
    }
}

if (isset($_POST['submitadd'])){
    $catname = toLowerNoSpace($_POST['catname']);
    $cattype = toLowerNoSpace($_POST['cattype']);
    $colname = $catname.$cattype;

    $catname1 = addslashes($_POST['catname']);
    $cattype1 = addslashes($_POST['cattype']);

    $query = "SELECT * from categories WHERE name='".$colname."'";
    $rowcount = mysqli_num_rows(mysqli_query($link,$query));
    if ($rowcount > 0){
        $error1 = "<div class='alert alert-danger'>Sorry, that category already exists.</div>";
    } else if ($colname == "id" || $colname == "name" || $colname == "email" || $colname == "country" || $colname == "type" || $colname == "city" || $colname == "password"){
        $error1 = "<div class='alert alert-danger'>Sorry, you cannot use the following reserved keywords for cateogory names: name, email, type, id, country, city, password.</div>";
    } else {

        //add to list of categories
        $query = "INSERT INTO categories (type, name, full_name) VALUES ('".$cattype1."', '".$colname."', '".$catname1."')";
        if (!mysqli_query($link,$query)){
            $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
        }

        //add as users column
        $query = "ALTER TABLE users ADD ".$colname." tinyint(1)";
        if (!mysqli_query($link,$query)){
            $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
        }

        //give all supers permission
        $query = "UPDATE users SET ".$colname."='1' WHERE type='SUPER'";
        if (!mysqli_query($link,$query)){
            $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
        }
    }
}

?>

<!-- Add category Modal -->
<div class="modal fade" id="addcategory" data-backdrop="static" tabindex="-1" aria-labelledby="add category" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
          <h2>Add Category</h2>
          <?php echo $error1; ?>
      </div>
      <form method="post">
          <div class="modal-body">
             <p><label for="catname" class="display-none">Name</label>
             <input type="text" class="form-control" autocomplete="off" name="catname" required placeholder="Name"></p>

              <p><label for="cattype" class="display-none">Type</label>
                  <select class="form-control" name="cattype" required placeholder="Category Type">
                      <?php
                      foreach ($category_types as $val){
                          echo '<option value="'.$val.'">'.$val.'</option>';
                      }
                      ?>
                  </select>
              </p>
          </div>
          <div class="modal-footer">
              <p><label for="submitadd" class="display-none">Add Category</label>
                  <input type="submit" class="btn btn-dark" value="Add Category" name="submitadd"></p>
              <a href="" class="btn btn-dark" data-dismiss="modal">Close</a>
          </div>
      </form>
    </div>
  </div>
</div>


<!-- delete Category modal -->
<?php
$query = "SELECT * from categories";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="modal fade" id="deletecategory'.$row['id'].'" data-backdrop="static" tabindex="-1" aria-labelledby="delete category" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                  <h2>Delete Category</h2>
                  <p>Are you sure you want to delete <strong>'.$row['full_name'].' ('.$row['type'].')</strong>?</p>
                  <p>This action cannot be undone. Everyone will loose permission.</p>
              </div>
              <div class="modal-footer">
                  <form method="post">
                      <label for="name" class="display-none">Name</label>
                      <input type="text" class="display-none" name="name" value="'.$row['name'].'">

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
?>

<!-- permissions Category modal -->
<?php
$query = "SELECT * from categories";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="modal fade" id="permissionscategory'.$row['id'].'" data-backdrop="static" tabindex="-1" aria-labelledby="permissions category" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                  <h2>Give everyone permissions</h2>
                  <p>Are you sure you want to give everyone permissions to<br><strong>'.$row['full_name'].' ('.$row['type'].')</strong>?</p>
                  <p>This action cannot be undone.</p>
              </div>
              <div class="modal-footer">
                  <form method="post">
                      <label for="name" class="display-none">Name</label>
                      <input type="text" class="display-none" name="name" value="'.$row['name'].'">

                      <label for="submitpermissions" class="display-none">Yes</label>
                      <input type="submit" class="btn btn-dark" value="Yes" name="submitpermissions">
                  </form>
                  <a href="" class="btn btn-dark" data-dismiss="modal">Close</a>
              </div>
            </div>
          </div>
        </div>';
    }
}
?>

<!-- edit Category modal -->
<?php
$query = "SELECT * from categories";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="modal fade" id="editcategory'.$row['id'].'" data-backdrop="static" tabindex="-1" aria-labelledby="add category" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                  <h2>Edit Category</h2>
                  <p>Please enter the new name of the category below.</p>
                  <form method="post">
                      <label for="new_name" class="display-none">Name</label>
                      <input type="text" class="form-control" autocomplete="off" name="new_name" value="'.$row['full_name'].'">
              </div>
              <div class="modal-footer">
                      <label for="number" class="display-none">ID</label>
                      <input type="number" class="display-none" name="number" value="'.$row['id'].'">

                      <label for="submitedit" class="display-none">Save</label>
                      <input type="submit" class="btn btn-dark" value="Save" name="submitedit">
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
            <div class="col-md-12">
                <p><button type="button" class="btn btn-dark" data-toggle="modal" data-target="#addcategory">Add Category</button></p>
            </div>
            <div class="col-md-12 overflow">
                <table class="table table-striped">
                  <thead class="thead-dark">
                    <tr>
                        <th scope="col" width="30%">Name</th>
                      <th scope="col" width="20%">Type</th>
                      <th scope="col" width="10%" class="text-middle">Manage</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $query = "SELECT * from categories ORDER BY type ASC";
                      if ($result = $link->query($query)) {
                          while ($row = $result->fetch_assoc()) {
                              echo "<tr>";
                            echo "<td>".$row['full_name']."</td>";
                              echo "<td>".$row['type']."</td>";
                              echo "<td class='text-middle'>";
                                echo "<a href='' title='Edit category name' data-toggle='modal' data-target='#editcategory".$row['id']."'><i class='admin-link fas fa-edit'></i></a>";
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo "<a title='Delete category' href='' data-toggle='modal' data-target='#deletecategory".$row['id']."'>";
                                echo "<i class='admin-link fas fa-trash-alt'></i></a>";
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo "<a href='' data-toggle='modal' data-target='#permissionscategory".$row['id']."' title='Give everyone permissions'>";
                                echo "<i class='admin-link fas fa-user-lock'></i></a>";
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
