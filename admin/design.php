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
$title = "Design Manager";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

//save colors form functionality
if (isset($_POST['submitchanges'])) {

    $color1_input = $_POST['color1_input'];
    $color2_input = $_POST['color2_input'];
    $color3_input = $_POST['color3_input'];

    $query = "UPDATE content SET value='".$color1_input."' WHERE type='color1'";
    if (!mysqli_query($link,$query)){
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    }

    $query = "UPDATE content SET value='".$color2_input."' WHERE type='color2'";
    if (!mysqli_query($link,$query)){
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    }

    $query = "UPDATE content SET value='".$color3_input."' WHERE type='color3'";
    if (!mysqli_query($link,$query)){
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    }
}

//save title font
if (isset($_POST['font'])) {

    $fancy_font_input = $_POST['fancy_font_input'];

    $query = "UPDATE content SET value='".$fancy_font_input."' WHERE type='fancy_font'";
    if (!mysqli_query($link,$query)){
        $alert_message = printAlert('danger', 'Sorry, there was an error. Please try again.');
    }
}

//upload image form functionality
if (isset($_POST['upload'])) {
    $input_name = "background_image_input";
    $target_dir = $dir."img/";
    $file_type = strtolower(pathinfo(basename($_FILES[$input_name]["name"]),PATHINFO_EXTENSION));
    if ($file_type != "jpg") {
        $alert_message = printAlert('danger', 'Only jpg files allowed.');
    }
    $new_name = "bkgd.jpg";
    $target_file = $target_dir.$new_name;
    if (move_uploaded_file($_FILES[$input_name]["tmp_name"], $target_file)) {
        $alert_message = printAlert('success', 'Successfully updated image!');
    }
}

include $dir."inc/header.php";

?>

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin" class="view-more" title="Administration Dashboard"><i class="fas fa-angle-left"></i>&nbsp;Back to dashboard</a></p>
        <?php echo $alert_message; ?>

        <p>&nbsp;</p>

        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <h2>Design</h2>
                <hr>
                <br>
                <form method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <h3><label for="color1_input">Primary Color</label></h3>
                            <input type="color" name="color1_input" value="<?php echo $color1; ?>" class="form-control">

                            <p>&nbsp;</p>

                            <h3><label for="color2_input">Secondary Color</label></h3>
                            <input type="color" name="color2_input" value="<?php echo $color2; ?>" class="form-control">

                            <p>&nbsp;</p>

                            <h3><label for="color3_input">Alert Color</label></h3>
                            <input type="color" name="color3_input" value="<?php echo $color3; ?>" class="form-control">

                            <p>&nbsp;</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><label for="submitchanges" class="display-none">Save Colors</label>
                            <input type="submit" name="submitchanges" value="Save Colors" class="btn btn-dark"></p>
                        </div>
                    </div>
                </form>
                <hr>
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <h3 style="float:left"><label for="background_image_input">Background Image</label></h3>
                            <?php
                            $tooltip_text = "Only file type jpg. Maximum file size is 2MB.<br>For best results, use a photo with dimensions 1800 x 1000px.";
                            tooltip($tooltip_text, TRUE);
                            ?>
                            <input type="file" name="background_image_input" class="form-control">
                            <p>&nbsp;</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><label for="upload" class="display-none">Upload Image</label>
                            <input type="submit" name="upload" value="Upload Image" class="btn btn-dark"></p>
                        </div>
                    </div>
                </form>
                <hr>
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <h3><label for="fancy_font_input">Title Font</label></h3>
                            <select name="fancy_font_input" class="form-control">
                                <?php
                                foreach ($fancy_font_array as $val){
                                    echo "<optgroup style='font-family: ".$val.", cursive;'>
                                    <option value='".$val."'";
                                    if ($fancy_font == $val){
                                        echo ' selected ';
                                    }
                                    echo ">".$val."</option></optgroup>";
                                }
                                ?>
                            </select>
                            <p>&nbsp;</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><label for="font" class="display-none">Save Font</label>
                            <input type="submit" name="font" value="Save Font" class="btn btn-dark"></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
