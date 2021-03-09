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
include $dir."inc/header.php";
include $dir."inc/connection.php";
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
        $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
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

    //get image values
    $files = array();
    for ($i=1; $i<=$maximages; $i++){
        $val = "image".$i;
        if ($_FILES[$val]["name"]){
            $imageFileType = strtolower(pathinfo(basename($_FILES[$val]["name"]),PATHINFO_EXTENSION));
            if ($imageFileType == "jpg" || $imageFileType == "png"){
                $current_date = date("m-d-y G-i-s");
                $brandnewfile = $current_date." ".$val.".".$imageFileType;
                $target_file = $dir."notices/noticeimages/".$brandnewfile;

                if (move_uploaded_file($_FILES[$val]["tmp_name"], $target_file)){
                    if (isset($_POST['submitchanges'])){
                        $query = "UPDATE notices SET $val='".$brandnewfile."' WHERE ID='".$notice_id."'";
                        if (!mysqli_query($link,$query)){
                            $error1 = "<div class='alert alert-danger'>Sorry, there was an error inserting image ".$i." into the database. Please try again.</div>";
                        }
                    } else {
                        array_push($files, $brandnewfile);
                    }
                } else {
                    $error1 = "<div class='alert alert-danger'>Sorry, there was an error uploading image ".$i.". Please try again.</div>";
                }
            } else {
                $error1 = "<div class='alert alert-danger'>Sorry, wrong file type for image ".$i.". Please try again.</div>";
            }
        }
    }



    //update existing values
    if (isset($_POST['submitchanges'])){
        $query = "UPDATE notices SET title='".$noticetitle."', date='".$date."', category='".$category."', text='".$text."' WHERE ID='".$notice_id."'";
        if (!mysqli_query($link,$query)){
            $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
        }
    }

    // add new notice
    if (isset($_POST['submitaddnotice'])){
        $query = "INSERT INTO notices (title, date, category, text, image1, image2, image3) VALUES ('".$noticetitle."', '".$date."', '".$category."', '".$text."', '".$files[0]."', '".$files[1]."', '".$files[2]."')";
        if (!mysqli_query($link,$query)){
            $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
        } else {
            echo '<meta http-equiv="refresh" content="0; URL='.$dir.'admin/notices.php" />';
        }
    }
}

$query = "SELECT * from notices WHERE id='".$notice_id."'";
$notice_row = mysqli_fetch_assoc(mysqli_query($link,$query));

//delete image modal
for ($i=0; $i<=$maximages; $i++){
    $val = "image".$i;
    echo '<div class="modal fade" id="deleteimage'.$i.'" data-backdrop="static" tabindex="-1" aria-labelledby="delete image" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2>Delete Image</h2>
                    <p>Are you sure you want to delete the following image?</p>
                    <p>This action cannot be undone. All other unsaved changes will be lost.</p>
                    <img alt="Image preview" src="'.$dir.'notices/noticeimages/'.$notice_row[$val].'" width="100%">
                </div>
                <div class="modal-footer">
                    <form method="post">
                        <label for="number" class="display-none">ID</label>
                        <input type="number" class="display-none" name="number" value="'.$i.'">

                        <label for="submitdeleteimage" class="display-none">Yes</label>
                        <input type="submit" class="btn btn-dark" value="Yes" name="submitdeleteimage">
                    </form>
                    <a href="" class="btn btn-dark" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>';

//preview image modal
    echo '<div class="modal fade" id="previewimage'.$i.'" data-backdrop="static" tabindex="-1" aria-labelledby="preview image" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2>Image '.$i.'</h2>
                    <hr>
                    <img "Image preview" src="'.$dir.'notices/noticeimages/'.$notice_row[$val].'" width="100%">
                </div>
                <div class="modal-footer">
                    <a href="" class="btn btn-dark" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>';
}

?>

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin/notices.php" class="view-more" title="Back"><i class="fas fa-angle-left"></i>&nbsp;See all notices</a></p>
        <?php echo $error1; ?>

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
                                <p><input type="date" required name="date" placeholder="Date" value="<?php echo $notice_row['date']; ?>" class="form-control"></p>

                                <p>&nbsp;</p>

                                <h3><label for="text">Text</label></h3>
                                <textarea name="text"><?php echo $notice_row['text']; ?></textarea>

                                <p>&nbsp;</p>

                                <h3 style="float:left">Category</h3>

                                <?php
                                $tooltip_text = "Choose who will have permissions to see your notice. If you don't select a category, the notice will not be available on the site.";
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

                                <br>
                                <h3 style="float:left">Images</h3>
                                <?php
                                $tooltip_text="Choose up to three images to include in your notice. Only file type JPG or PNG allowed.";
                                tooltip($tooltip_text, TRUE);

                                for ($i=1; $i<=$maximages; $i++){
                                    $val = "image".$i;
                                    echo "<p><strong>Image ".$i."</strong></p>";
                                    if ($notice_row[$val]){
                                        echo "<p><a class='view-more' href='' title='Preview Image' data-toggle='modal' data-target='#previewimage".$i."'>Preview Image ".$i."&nbsp;<i class='fas fa-angle-right'></i></a></p>";
                                        echo "<p><a class='view-more' href='' title='Delete Image' data-toggle='modal' data-target='#deleteimage".$i."'>Delete Image ".$i."&nbsp;<i class='fas fa-times'></i></a></p>";
                                    } else {
                                        echo "<p>No existing image.</p>";
                                    }

                                    //choose image buttons
                                    echo '<p><label class="display-none" for="'.$val.'">Image '.$i.'</label>';
                                    echo '<input type="file" name="'.$val.'"></p>';
                                    echo '<br>';
                                }
                                ?>
                                <hr>
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
