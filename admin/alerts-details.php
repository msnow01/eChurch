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
$title = "Alerts Manager Details";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";
$alert_id = $_GET['id'];

//save changes form functionality
if (isset($_POST['submitchanges']) || isset($_POST['submitadd'])) {

    //get values from form
    $alerttitle = upperWords(addslashes($_POST['alerttitle']));
    $text = substr(addslashes($_POST['text']), 0, 295);
    $category = "";

    //get Category values
    $query = "SELECT * from categories WHERE type='ALERT'";
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
        $query = "UPDATE alerts SET title='".$alerttitle."', category='".$category."', text='".$text."' WHERE ID='".$alert_id."'";
        if (!mysqli_query($link,$query)){
            $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
        }
    }

    // add new resource
    if (isset($_POST['submitadd'])){
        $query = "INSERT INTO alerts (title, category, text) VALUES ('".$alerttitle."', '".$category."', '".$text."')";
        if (!mysqli_query($link,$query)){
            $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
        } else {
            echo '<meta http-equiv="refresh" content="0; URL='.$dir.'admin/alerts.php" />';
        }
    }

}

$query = "SELECT * from alerts WHERE id='".$alert_id."'";
$alert_row = mysqli_fetch_assoc(mysqli_query($link,$query));

?>

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin/alerts.php" class="view-more" title="Back"><i class="fas fa-angle-left"></i>&nbsp;See all alerts</a></p>
        <?php echo $alert_message; ?>

        <p>&nbsp;</p>

        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <?php
                if ($alert_id){
                    echo "<h2>".$alert_row['title']."</h2>";
                } else {
                    echo "<h2>Add Alert</h2>";
                }
                ?>
                <hr>
                <br>
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 style="float:left"><label for="alerttitle">Title</label></h3>
                            <?php
                            $tooltip_text = "Your title won't be visible on the alert, only the text you provide below.";
                            tooltip($tooltip_text, TRUE);
                            ?>
                            <p><input type="text" required name="alerttitle" autocomplete="off" class="form-control" value="<?php echo $alert_row['title']; ?>"></p>

                            <p>&nbsp;</p>

                            <?php
                            echo '<h3 style="float:left">Category</h3>';
                            $tooltip_text = "Choose who will have permissions to see your alert. If you don't select a category, the alert will not be visible on the site.";
                            tooltip($tooltip_text, TRUE);

                            $query = "SELECT * from categories WHERE type='ALERT'";
                            if ($result = $link->query($query)) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<p>';
                                    echo '<input type="checkbox" ';

                                    //select existing ones
                                    $alert_categories = explode(", ", $alert_row['category']);
                                    foreach ($alert_categories as $val){
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

                            <h3 style="float:left"><label for="text">Text</label></h3>
                            <?php
                            $tooltip_text = "Your alert must have a maximum of 295 characters.";
                            tooltip($tooltip_text, TRUE);
                            ?>
                            <textarea name="text" rows="5" class="form-control"><?php echo $alert_row['text']; ?></textarea>

                            <p>&nbsp;</p>
                            <hr>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($alert_id){
                                echo '<p><label for="submitchanges" class="display-none">Save Changes</label>
                                <input type="submit" name="submitchanges" value="Save Changes" class="btn btn-dark"></p>';
                            } else {
                                echo '<p><label for="submitadd" class="display-none">Add Alert</label>
                                <input type="submit" name="submitadd" value="Add Alert" class="btn btn-dark"></p>';
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
