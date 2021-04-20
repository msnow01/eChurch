<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER" && $_SESSION['login_type'] != "ADMIN") {
    $location = "location: ".$dir."home";
    header($location);
}
?>

<!DOCTYPE html>
<html lang="en">

<?php
$title = "Notice Manager Details";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";
include $dir."inc/tiny.php";
$notice_id = $_GET['id'];

$query = "SELECT * from notices WHERE id='".$notice_id."'";
$notice_row = mysqli_fetch_assoc(mysqli_query($link,$query));


//delete image form functionality
if (isset($_POST['submitdeleteimage'])){
    $number = $_POST['number'];
    $val = "image".$number;
    $query = "UPDATE notices SET $val='' WHERE ID='".$notice_id."'";
    if (!mysqli_query($link,$query)){
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    } else {
        $path = $_SERVER['DOCUMENT_ROOT']."/notices/noticeimages/".$notice_row[$val];
        $check = unlink($path);
    }
}

//save changes form functionality
if (isset($_POST['submitchanges']) || isset($_POST['submitaddnotice'])) {

    //get values from form
    $noticetitle = upperWords(addslashes($_POST['noticetitle']));
    $date = $_POST['date'];
    $text = addslashes($_POST['text']);
    $category = "";

    //get Category values
    $query = "SELECT * from categories WHERE type='NOTICE'";
    if ($result = $link->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $val = $row['name'];
            if ($_POST[$val]){
                if ($category == ""){
                    $category .= $val;
                } else {
                    $category .= ", ".$val;
                }
            }
        }
    }

    //update existing values
    if (isset($_POST['submitchanges'])){
        $query = "UPDATE notices SET title='".$noticetitle."', date='".$date."', category='".$category."', text='".$text."' WHERE ID='".$notice_id."'";
        if (!mysqli_query($link,$query)){
            $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
        }
    }

    // add new notice
    if (isset($_POST['submitaddnotice'])){
        $query = "INSERT INTO notices (title, date, category, text) VALUES ('".$noticetitle."', '".$date."', '".$category."', '".$text."')";
        if (!mysqli_query($link,$query)){
            $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
        } else {
            echo '<meta http-equiv="refresh" content="0; URL='.$dir.'admin/notices.php" />';
        }
    }
}

$query = "SELECT * from notices WHERE id='".$notice_id."'";
$notice_row = mysqli_fetch_assoc(mysqli_query($link,$query));

?>

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin/notices.php" class="view-more" title="Back"><i class="fas fa-angle-left"></i>&nbsp;See all notices</a></p>
        <?php echo $alert_message; ?>

        <p>&nbsp;</p>

        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <?php
                if ($notice_id){
                    echo "<h2>".$notice_row['title']."</h2>";
                } else {
                    echo "<h2>Add Notice</h2>";
                }
                ?>
                <hr>
                <br>
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                                <h3><label for="noticetitle">Title</label></h3>
                                <p><input type="text" required name="noticetitle" autocomplete="off" class="form-control" value="<?php echo $notice_row['title']; ?>"></p>

                                <p>&nbsp;</p>

                                <h3><label for="date">Date</label></h3>
                                <?php
                                if ($notice_id){
                                    $date_default = $notice_row['date'];
                                } else {
                                    $date_default = date("Y-m-d");
                                }
                                ?>
                                <p><input type="date" required name="date" placeholder="Date" value="<?php echo $date_default; ?>" class="form-control"></p>

                                <p>&nbsp;</p>

                                <h3 style="float:left">Category</h3>

                                <?php
                                $tooltip_text = "If you do not select a category, the notice will not be visible on the site.";
                                tooltip($tooltip_text, TRUE);

                                $query = "SELECT * from categories WHERE type='NOTICE'";
                                if ($result = $link->query($query)) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<p>';
                                        echo '<input type="checkbox" ';

                                        //select existing ones
                                        $notice_categories = explode(", ", $notice_row['category']);
                                        foreach ($notice_categories as $val){
                                            if ($val == $row['name']){
                                                echo ' checked ';
                                            }
                                        }
                                        echo ' name="'.$row['name'].'">';
                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                                        echo '<label for="'.$row['name'].'">'.$row['full_name'].'</label>';
                                        echo '</p>';
                                    }
                                }
                                ?>

                                <p>&nbsp;</p>

                                <h3><label for="text">Text</label></h3>
                                <textarea name="text"><?php echo $notice_row['text']; ?></textarea>


                                <p>&nbsp;</p>
                                <hr>
                                <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($notice_id){
                                echo '<p><label for="submitchanges" class="display-none">Save Changes</label>
                                <input type="submit" name="submitchanges" value="Save Changes" class="btn btn-dark"></p>';
                            } else {
                                echo '<p><label for="submitaddnotice" class="display-none">Add Notice</label>
                                <input type="submit" name="submitaddnotice" value="Add Notice" class="btn btn-dark"></p>';
                            }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
