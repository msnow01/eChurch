<?php
$dir="../";
include $dir."inc/session-start.php";
$title = "Home";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";
include $dir."notices/notice-query.php";
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <div class="row justify-content-around">
            <a href="<?php echo $dir; ?>video" class="col-md-3 shadow info-box">
                <h2><i class="fas fa-video"></i>&nbsp;Video</h2>
                <p class="sm-font">Watch past services.</p>
                <div class="btn btn-dark">Watch</div>
            </a>
            <a href="<?php echo $dir; ?>audio" class="col-md-3 shadow info-box">
                <h2><i class="fas fa-volume-up"></i>&nbsp;Audio</h2>
                <p class="sm-font">Listen to past services.</p>
                <div class="btn btn-dark">Listen</div>
            </a>
            <?php if ($session_row['livestreamlive']){
                echo '<a href="'.$live_stream_link.'" target="_blank" class="shadow col-md-3 info-box">
                    <h2><i class="fas fa-play"></i>&nbsp;Live</h2>
                    <p class="sm-font">Join live services.</p>
                    <div class="btn btn-dark">Join</div>
                </a>';
            } else {
                echo '<div class="shadow col-md-3 info-box">
                    <h2><i class="fas fa-play"></i>&nbsp;Live</h2>
                    <p class="sm-font">You do not have access to join live.</p>
                </div>';
            }

            ?>
        </div>
        <p>&nbsp;</p>
        <div class="row justify-content-around">
            <div class="col-md-11 notice shadow">
                <?php include $dir."notices/notice-list.php"; ?>
                <p class="text-right"><a href="<?php echo $dir;?>notices" class="view-more">All Notices&nbsp;<i class="fas fa-angle-right"></i></a></p>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
