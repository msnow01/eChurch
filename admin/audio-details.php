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
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

$audio_id = $_GET['id'];

if (!$audio_id){
    echo '<meta http-equiv="refresh" content="0; URL='.$dir.'admin/audio.php" />';
}

if(isset($_POST['submitchanges'])){

    $audiotitle = upperWords(addslashes($_POST['audiotitle']));
    $date = $_POST['date'];
    $category = $_POST['category'];

    $query = "UPDATE audio SET title='".$audiotitle."', date='".$date."', category='".$category."' WHERE ID='".$audio_id."'";
    if (!mysqli_query($link,$query)){
        $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
    }
}

$query = "SELECT * from audio WHERE id='".$audio_id."'";
$audio_row = mysqli_fetch_assoc(mysqli_query($link,$query));

?>

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin/audio.php" class="view-more" title="Back"><i class="fas fa-angle-left"></i>&nbsp;See all audio</a></p>
        <?php echo $error1; ?>
        <p>&nbsp;</p>
        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <?php echo "<h2>".$audio_row['title']."</h2>"; ?>
                <hr>
                <br>
                <form method="post">
                    <div class="row">
                        <div class="col-md-12">
                                <h3><label for="audiotitle">Title</label></h3>
                                <p><input type="text" required name="audiotitle" autocomplete="off" class="form-control" value="<?php echo $audio_row['title']; ?>"></p>

                                <p>&nbsp;</p>

                                <h3><label for="date">Date</label></h3>
                                <p><input type="date" required name="date" placeholder="Date" value="<?php echo $audio_row['date']; ?>" class="form-control"></p>

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
                                <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><label for="submitchanges" class="display-none">Save Changes</label>
                            <input type="submit" name="submitchanges" value="Save Changes" class="btn btn-dark"></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
