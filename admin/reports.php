<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER" && $_SESSION['login_type'] != "ADMIN") {
    $location = "location: ".$dir."home";
    header($location);
}

$title = "Reports Manager";
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";
$directory = "admin/reports";

//get output for generating a new report
$query = "SELECT * FROM users";
$data = $link->query($query);
$output = "";
    foreach($data as $key => $var) {
        foreach($var as $k => $v) {
            if ($k != "id" && $k != "password"){
                if ($key === 0) {
                    $query = "SELECT * from categories WHERE name='".$k."'";
                    $row = mysqli_fetch_assoc(mysqli_query($link,$query));
                    if ($row){
                        $header = $row['full_name']." (".$row['type'].")";
                    } else {
                        $header = $k;
                    }
                    $output .= strtoupper($header).",";
                }
            }
        }
    }

    $output .= "\r\n";

    foreach($data as $key => $var) {
        foreach($var as $k => $v) {
            if ($k != "id" && $k != "password"){
                if ($v == "1"){
                    $v = "YES";
                }

                if ($v == "0"){
                    $v = "NO";
                }

                $output .= $v . ',';
            }
        }
        $output .= "\r\n";
    }

//create the file
if (isset($_POST['newreport'])){
    $filename = $dir.$directory."/".date("mdyGis")." - User Report ".date("M d Y").".csv";
    echo $filename;
    $myfile = fopen($filename, "w") or die("Unable to open file!");
    fwrite($myfile, $output);
    fclose($myfile);
}

//delete the file
if(isset($_POST['submitdelete'])){
    $filetodelete = $_POST['filetodelete'];
    $path = $_SERVER['DOCUMENT_ROOT']."/".$directory."/".$filetodelete;
    $check = unlink($path);
}

//get current files in the reports directory
$reports = scandir($dir.$directory, 1);

//delete report modal
foreach ($reports as $val){
    $report_id = explode(" ", $val);

    echo '<div class="modal fade" id="deletereport'.$report_id[0].'" data-backdrop="static" tabindex="-1" aria-labelledby="delete report" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                 <div class="modal-body">
                    <h2>Delete Report</h2>
                    <p>Are you sure you want to delete <strong>'.$val.'</strong>?</p>
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
                <form method="post">
                    <p>
                        <label for="newreport" class="display-none">New Report</label>
                        <input type="submit" name="newreport" value="New Report" class="btn btn-dark"></p>
                </form>
            </div>
            <div class="col-md-12 overflow">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col" class="text-middle">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($reports as $val){
                            if ($val != "." && $val != ".."){
                                $report_id = explode(" ", $val);

                                echo "<tr>
                                    <td>".$val."</td>
                                    <td class='text-middle'>
                                        <a href='".$dir.$directory."/".$val."' download title='Download this report'>
                                            <i class='fas fa-download admin-link'></i>
                                        </a>";
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        echo "<a href='' data-toggle='modal' data-target='#deletereport".$report_id[0]."' title='Delete Report'><i class='fas fa-trash-alt admin-link'></i></a>
                                    </td>
                                </tr>";
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
