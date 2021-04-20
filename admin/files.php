<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER") {
    $location = "location: ".$dir."home";
    header($location);
}

$title = "File Manager";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";

$directory = "files";

//form functionality for add file
if(isset($_POST['submitadd'])){
    $input_name = "file";
    $filename = $_FILES[$input_name]["name"];
    if (!$filename){
        $alert_message = printAlert('danger', 'Sorry, you must choose a file.');
    } else {

        $target_dir = $dir.$directory."/";

        //check file type to avoid bad or large files
        $file_type = strtolower(pathinfo(basename($filename),PATHINFO_EXTENSION));
        $flag = FALSE;
        $to_avoid = array("exe", "mp4", "wmv", "avi", "download", "bat", "cmd", "com", "dll", "hta", "msi", "paf", "rar", "scr", "vb", "ws", "wsf");
        foreach($to_avoid as $temp){
            if ($file_type == $temp){
                $flag = TRUE;
            }
        }

        //file type is ok
        if ($flag == FALSE){
            $current_date = date("mdyGis");
            $brandnewfile = $current_date."_".$filename;
            $target_file = $target_dir.$brandnewfile;
            if (!move_uploaded_file($_FILES[$input_name]["tmp_name"], $target_file)) {
                $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
            }
        } else {
            $alert_message = printAlert('danger', 'Sorry, wrong file type. Please try again.');
        }
    }

}

//delete the file
if(isset($_POST['submitdelete'])){
    $filetodelete = $_POST['filetodelete'];
    $path = $_SERVER['DOCUMENT_ROOT']."/".$directory."/".$filetodelete;
    $check = unlink($path);
}

$files = scandir($dir.$directory, 1);

foreach($files as $val){
    if ($val != "." && $val != ".."){
        $file_id = explode("_", $val);

        echo '<div class="modal fade" id="deletefile'.$file_id[0].'" data-backdrop="static" tabindex="-1" aria-labelledby="delete file" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                  <h2>Delete File</h2>
                  <p>Are you sure you want to delete <br><strong>'.$val.'</strong>?</p>
                  <p>This action cannot be undone.</p>
              </div>
              <div class="modal-footer">
                  <form method="post">
                      <label for="filetodelete" class="display-none">File Name</label>
                      <input type="text" class="display-none" name="filetodelete" value="'.$val.'">

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
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-2">
                            <p>
                                <label for="submitadd" class="display-none">Add File</label>
                                <input type="submit" name="submitadd" class="btn btn-dark" value="Add File">
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <label for="file" class="display-none">Choose File</label>
                                <input type="file" name="file" class="form-control" required>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <?php
                            $tooltip_text="Maximum file size is 2MB.";
                            tooltip($tooltip_text, TRUE);
                            ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12 overflow">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" width="50%">Title</th>
                            <th scope="col" width="50%" class="text-middle">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($files as $val){
                            if ($val != "." && $val != ".."){
                                $file_id = explode("_", $val);

                                echo "<tr>";
                                    echo "<td><input class='form-control' type='text' value='/files/".$val."' id='".$file_id[0]."'></td>";
                                    echo "<td class='text-middle'>";
                                        echo "<a id='".$file_id[0]."copy' onclick='copyText(\"".$file_id[0]."\")' title='Copy link'><i class='fas fa-copy admin-link'></i></a>";
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        echo "<a href='".$dir.$directory."/".$val."' target='_blank' title='Preview file'><i class='fas fa-eye admin-link'></i></a>";
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        echo "<a href='".$dir.$directory."/".$val."' download title='Download this file'><i class='fas fa-download admin-link'></i></a>";
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        echo "<a href='' data-toggle='modal' data-target='#deletefile".$file_id[0]."' title='Delete file'><i class='fas fa-trash-alt admin-link'></i></a>";
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
function copyText(k) {
    var copyText = document.getElementById(k);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
 }
 </script>
