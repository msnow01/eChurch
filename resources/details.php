<?php
$dir="../";
include $dir."inc/session-start.php";
$title = "Resources Details";
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

$resource_id = $_GET['id'];
$query = "SELECT * from resources WHERE id='".$resource_id."'";
$row = mysqli_fetch_assoc(mysqli_query($link,$query));

$category_ok = explode(", ", $row['category']);
$print_ok = FALSE;

foreach($category_ok as $val){
    if ($session_row[$val]){
        $print_ok = TRUE;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<body>
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <div class="row justify-content-center notice shadow">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <a class="home-icon" href="<?php echo $dir;?>home"><i class="fas fa-home"></i></a>
                        <?php
                        if (!$resource_id || $print_ok == FALSE){
                            echo '<p class="sm-font">You do not have access to see this resource.</p>';
                        } else {
                            echo '
                            <h2><i class="fas fa-link"></i>&nbsp;'.$row['title'].'</h2>
                            <div class="blurb">'.$row['text'].'</div>
                            <p>&nbsp;</p>
                            <p><a href="'.$dir.'resources" class="view-more"><i class="fas fa-angle-left"></i>&nbsp;All Resources</a></p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
