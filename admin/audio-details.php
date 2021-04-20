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
$title = "Audio Manager Details";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";

$audio_id = $_GET['id'];

if(isset($_POST['submitchanges'])){

    $audiotitle = upperWords(addslashes($_POST['audiotitle']));
    $date = $_POST['date'];
    $category = $_POST['category'];

    $query = "UPDATE audio SET title='".$audiotitle."', date='".$date."', category='".$category."' WHERE ID='".$audio_id."'";
    if (!mysqli_query($link,$query)){
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    }
}

if(isset($_POST['submitaudio'])){
    $audiotitle = upperWords(addslashes($_POST['audiotitle']));
    $filename = $_FILES["audiofile"]["name"];
    $date = $_POST['date'];
    $category = $_POST['category'];

    if (!$filename){
        $alert_message = printAlert('danger', 'Sorry, you must choose an audio file.');
    } else {
        $fileType = strtolower(pathinfo(basename($filename),PATHINFO_EXTENSION));
        if ($fileType != "mp3"){
            $alert_message = printAlert('danger', 'Sorry, wrong file type. Only mp3 allowed.');
        } else {
            $target_dir = $dir."audio/audiofiles/";
            $current_date = date("mdyGis");
            $title_no_specs = preg_replace('/[^A-Za-z0-9\-]/', '', $audiotitle);
            $new_name = $current_date." ".$title_no_specs.".".$fileType;
            $target_file = $target_dir.$new_name;

            if (move_uploaded_file($_FILES['audiofile']["tmp_name"], $target_file)) {
                $query = "INSERT INTO audio (title, date, category, file) VALUES ('".$audiotitle."', '".$date."', '".$category."', '".$new_name."')";
                if (!mysqli_query($link,$query)){
                    $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
                } else {
                    echo '<meta http-equiv="refresh" content="0; URL='.$dir.'admin/audio.php" />';
                }
            } else {
                $alert_message = printAlert('danger', 'Sorry, there was an error uploading your file. Please try again.');
            }
        }
    }
}

if ($audio_id){
    $query = "SELECT * from audio WHERE id='".$audio_id."'";
    $audio_row = mysqli_fetch_assoc(mysqli_query($link,$query));
}

?>

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin/audio.php" class="view-more" title="Back"><i class="fas fa-angle-left"></i>&nbsp;See all audio</a></p>
        <?php echo $alert_message; ?>
        <p>&nbsp;</p>
        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <?php
                if (!$audio_id){
                    echo "<h2>Add Audio</h2>";
                } else {
                    echo "<h2>".$audio_row['title']."</h2>";
                }
                ?>

                <hr>
                <br>
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <h3><label for="audiotitle">Title</label></h3>
                            <p><input type="text" required name="audiotitle" autocomplete="off" class="form-control" value="<?php echo $audio_row['title']; ?>"></p>

                            <p>&nbsp;</p>

                            <h3><label for="date">Date</label></h3>

                            <?php
                            if ($audio_id){
                                $date_default = $audio_row['date'];
                            } else {
                                $date_default = date("Y-m-d");
                            }
                            ?>
                            <p><input type="date" required name="date" value="<?php echo $date_default; ?>" class="form-control"></p>

                            <p>&nbsp;</p>

                            <h3><label for="category">Category</label></h3>
                            <select class="form-control" required name="category">
                            <?php
                            $query = "SELECT * from categories WHERE type='AUDIO'";
                            if ($result = $link->query($query)) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="'.$row['name'].'"';
                                    if ($row['name'] == $audio_row['category']){
                                        echo " selected ";
                                    }
                                    echo '>'.$row['full_name'].'</option>';
                                }
                            }
                            ?>
                            </select>

                            <p>&nbsp;</p>

                            <?php
                            if (!$audio_id){
                                echo '<h3 style="float: left"><label for="audiofile">Audio File</label></h3>';
                                $tooltip_text="Only file type MP3 allowed. Max file size is 2MB.";
                                tooltip($tooltip_text, TRUE);
                                echo '<p><input type="file" required name="audiofile" class="form-control"></p>';
                            }
                            ?>

                            <p>&nbsp;</p>
                            <hr>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (!$audio_id){
                                echo '<p><label for="submitaudio" class="display-none">Add Audio</label>
                                <input type="submit" name="submitaudio" value="Add Audio" class="btn btn-dark"></p>';
                            } else {
                                echo '<p><label for="submitchanges" class="display-none">Save Changes</label>
                                <input type="submit" name="submitchanges" value="Save Changes" class="btn btn-dark"></p>';
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
