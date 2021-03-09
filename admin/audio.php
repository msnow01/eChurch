<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER") {
    $location = "location: ".$dir."home";
    header($location);
}

$title = "Audio Manager";
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

$directory = "audio/audiofiles/";


//delete audio form functionality
if(isset($_POST['submitdelete'])){
    //remove file from the server
    $filetodelete = $_POST['filetodelete'];
    $path = $_SERVER['DOCUMENT_ROOT']."/".$directory.$filetodelete;
    $check = unlink($path);

    //remove it from the database too
    $number = $_POST['number'];
    $query = "DELETE FROM audio WHERE id='".$number."'";
    if (!mysqli_query($link,$query)){
        $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
    }

}

//add new audio form functionality
if(isset($_POST['submitaudio'])){
    $filename = $_FILES["audiofile"]["name"];

    if (!$filename){
        $error1 = "<div class='alert alert-danger'>Sorry, you must choose an audio file.</div>";
    } else {
        $fileType = strtolower(pathinfo(basename($filename),PATHINFO_EXTENSION));
        if ($fileType != "mp3"){
            $error1 = "<div class='alert alert-danger'>Sorry, wrong file type. Only mp3 allowed.</div>";
        } else {
            $filename2 = str_replace("#", "", $filename);
            $target_file = $dir.$directory.$filename2;
            if (move_uploaded_file($_FILES["audiofile"]["tmp_name"], $target_file)) {

                $category = $_POST['category'];

                $year = substr($filename, 0, 4);
                $month = substr($filename, 5, 2);
                $day = substr($filename, 7, 2);
                $date_string = $year."-".$month."-".$day;
                $date = date("Y-m-d", strtotime($date_string));

                $name = upperWords(substr($filename, 10, -4));

                $query = "INSERT INTO audio (title, date, category, file) VALUES ('".$name."', '".$date."', '".$category."', '".$filename2."')";
                if (!mysqli_query($link,$query)){
                    $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
                }
            } else {
                $error1 = "<div class='alert alert-danger'>Sorry, there was an error uploading your file. Please try again.</div>";
            }
        }
    }
}


$query = "SELECT * from audio";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {
        //delete audio file modals
        echo '<div class="modal fade" id="deleteaudio'.$row['id'].'" data-backdrop="static" tabindex="-1" aria-labelledby="delete audio" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                  <h2>Delete Audio</h2>
                  <p>Are you sure you want to delete <strong>'.$row['title'].'</strong>?</p>
                  <p>This action cannot be undone.</p>
              </div>
              <div class="modal-footer">
                  <form method="post">
                      <label for="filetodelete" class="display-none">File Name</label>
                      <input type="text" class="display-none" name="filetodelete" value="'.$row['file'].'">

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

        //preview audio file modals
        $player_name = strtolower($row['file']);
        $player_name2 = str_replace(" ", "", $player_name);
        $player_name3 = explode(".", $player_name2);

        echo '<div class="modal fade" id="previewaudio'.$row['id'].'" data-backdrop="static" tabindex="-1" aria-labelledby="preview audio" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                  <h2>Preview Audio</h2>
                  <audio id="audio'.$player_name3[0].'" controls>
                    <source src="'.$dir.$directory.$row['file'].'" type="audio/mpeg" />
                  </audio>
              </div>
              <div class="modal-footer">
                  <a href="" class="btn btn-dark" onclick="pause(\'audio'.$player_name3[0].'\')" data-dismiss="modal">Close</a>
              </div>
            </div>
          </div>
        </div>';
    }
}

?>

<!-- Add audio file Modal -->
<div class="modal fade" id="addaudio" data-backdrop="static" tabindex="-1" aria-labelledby="add audio" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
          <h2>Add Audio</h2>
          <p>Your file name MUST be in the following format:<br><strong>YYYY MMDD NAME OF FILE.mp3</strong></p>
      </div>
      <form method="post" enctype="multipart/form-data">
          <div class="modal-body">
             <p><label for="audiofile" class="display-none">Choose File</label>
                 <input type="file" required class="form-control" name="audiofile"></p>
              <p>
                  <select class="form-control" name="category" required placeholder="Category">
                      <?php
                      $query = "SELECT * from categories WHERE type='AUDIO'";
                      if ($result = $link->query($query)) {
                          while ($row = $result->fetch_assoc()) {
                              echo '<option value="'.$row['name'].'">'.$row['full_name'].'</option>';
                          }
                      }
                      ?>
                  </select>
              </p>
          </div>
          <div class="modal-footer">
              <input type="submit" name="submitaudio" class="btn btn-dark" value="Add Audio">
              <a href="" class="btn btn-dark" data-dismiss="modal">Close</a>
          </div>
      </form>
    </div>
  </div>
</div>

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
                <p><a href='' data-target='#addaudio' data-toggle='modal' class='btn btn-dark'>Add Audio</a></p>
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
                        $query = "SELECT * from audio ORDER BY date DESC";
                        if ($result = $link->query($query)) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='align-middle'>".$row['title']."</td>";
                                echo "<td class='align-middle'>".dateFormat($row['date'])."</td>";
                                echo "<td class='align-middle'>";

                                //get current categories
                                $query2 = "SELECT * from categories WHERE type='AUDIO'";
                                if ($result2 = $link->query($query2)) {
                                    while ($cat_row = $result2->fetch_assoc()) {
                                        if ($row['category'] == $cat_row['name']){
                                            echo $cat_row['full_name']."<br>";
                                        }
                                    }
                                }

                                echo "</td><td class='text-middle align-middle'>";
                                echo "<a href='".$dir."admin/audio-details.php?id=".$row['id']."' title='Edit audio details'><i class='admin-link fas fa-edit'></i></a>";
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo "<a href='' data-toggle='modal' data-target='#deleteaudio".$row['id']."' title='Delete audio'>";
                                echo "<i class='fas fa-trash-alt admin-link'></i></a>";
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo "<a href='' data-toggle='modal' data-target='#previewaudio".$row['id']."' title='Preview audio'>";
                                echo "<i class='fas fa-play admin-link'></i></a>";
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

<script>
    function pause(identify) {
        var x = document.getElementById(identify);
        x.pause();
    }
</script>
