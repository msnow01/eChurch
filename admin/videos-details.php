<?php
$dir="../";
include $dir."inc/session-start.php";
if ($_SESSION['login_type'] != "SUPER") {
    $location = "location: ".$dir."home";
    header($location);
}
?>

<!DOCTYPE html>
<html lang="en">

<?php
$title = "Video Manager Details";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";

$video_id = $_GET['id'];

if (isset($_POST['submitchanges']) || isset($_POST['submitaddvideo'])){

    $videotitle = upperWords(addslashes($_POST['videotitle']));

    $date = $_POST['date'];
    $iframe = addslashes($_POST['iframe']);
    $category = $_POST['category'];

    if (isset($_POST['submitaddvideo'])){
        $query = "INSERT INTO videos (title, date, category, iframe) VALUES ('".$videotitle."', '".$date."', '".$category."', '".$iframe."')";
        if (!mysqli_query($link,$query)){
            $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
        } else {
            echo '<meta http-equiv="refresh" content="0; URL='.$dir.'admin/videos.php" />';
        }
    }

    if (isset($_POST['submitchanges'])){
        $query = "UPDATE videos SET title='".$videotitle."', date='".$date."', category='".$category."', iframe='".$iframe."' WHERE ID='".$video_id."'";
        if (!mysqli_query($link,$query)){
            $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
        }
    }
}

$query = "SELECT * from videos WHERE id='".$video_id."'";
$video_row = mysqli_fetch_assoc(mysqli_query($link,$query));

?>

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin/videos.php" class="view-more" title="Back"><i class="fas fa-angle-left"></i>&nbsp;See all videos</a></p>
        <?php echo $alert_message; ?>

        <p>&nbsp;</p>

        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <?php
                if ($video_id){
                    echo "<h2>".$video_row['title']."</h2>";
                } else {
                    echo "<h2>Add Video</h2>";
                }
                ?>
                <hr>
                <br>
                <form method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <h3><label for="videotitle">Title</label></h3>
                            <p><input type="text" required name="videotitle" autocomplete="off" class="form-control" value="<?php echo $video_row['title']; ?>"></p>

                            <p>&nbsp;</p>

                            <h3><label for="date">Date</label></h3>
                            <?php
                            if ($video_id){
                                $date_default = $video_row['date'];
                            } else {
                                $date_default = date("Y-m-d");
                            }
                            ?>
                            <p><input type="date" required name="date" placeholder="Date" value="<?php echo $date_default; ?>" class="form-control"></p>

                            <p>&nbsp;</p>

                            <h3><label for="category">Category</label></h3>
                            <select class="form-control" required name="category">
                            <?php
                            $query = "SELECT * from categories WHERE type='VIDEO'";
                            if ($result = $link->query($query)) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="'.$row['name'].'"';
                                    if ($row['name'] == $video_row['category']){
                                        echo " selected ";
                                    }
                                    echo '>'.$row['full_name'].'</option>';
                                }
                            }
                            ?>
                            </select>
                            <p>&nbsp;</p>

                            <h3 style="float:left"><label for="iframe">Embed Code</label></h3>
                            <?php
                            $tooltip_text="If needed, set width=\"100%\" and height=\"140\".";
                            tooltip($tooltip_text, FALSE);
                            ?>

                            <label for="iframe" class="display-none">Embed Code</label>
                            <textarea required class="form-control" name="iframe"><?php echo $video_row['iframe']; ?></textarea>

                            <p>&nbsp;</p>
                            <hr>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($video_id){
                                echo '<p><label for="submitchanges" class="display-none">Save Changes</label>
                                <input type="submit" name="submitchanges" value="Save Changes" class="btn btn-dark"></p>';
                            } else {
                                echo '<p><label for="submitaddvideo" class="display-none">Add Video</label>
                                <input type="submit" name="submitaddvideo" value="Add Video" class="btn btn-dark"></p>';
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
