<?php
$dir="../";
include $dir."inc/session-start.php";
$title = "Audio";
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

$query = "SELECT * from categories WHERE type='AUDIO'";
$firstrow = mysqli_fetch_assoc(mysqli_query($link,$query));

//get url variables
$categorynum = $_GET['categorynum'];
$pagenum = $_GET['pagenum'];

//set default category
if (!$categorynum){
    $categorynum = $firstrow['name'];
}

//set default page
if (!$pagenum){
    $pagenum = 0;
}

//next and previous page values
$nextpage = $pagenum + 1;
$prevpage = $pagenum - 1;

//print next button or not
$noNext = FALSE;
$query = "SELECT * from audio WHERE category='".$categorynum."'";
$counter = mysqli_num_rows(mysqli_query($link,$query));
if (($pagenum + 1) * $maxaudio >= $counter){
    $noNext = TRUE;
}

//get OFFSET
$offset = $pagenum * $maxaudio;

//get list of page numbers
$div = $counter/$maxaudio;
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
                        <h2>&nbsp;<i class="fas fa-volume-up"></i>&nbsp;Audio</h2>
                        <ul class="nav nav-tabs">
                            <?php
                            $query = "SELECT * from categories WHERE type='AUDIO'";
                            if ($result = $link->query($query)) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<li><a class="nav-link ';
                                    if ($categorynum == $row['name']){
                                        echo 'active ';
                                    }
                                    echo '" href="?categorynum='.$row['name'].'">'.$row['full_name'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <p>&nbsp;</p>

                <?php
                $noaccess = FALSE;
                if ($session_row[$categorynum]){
                    $query = "SELECT * from audio WHERE category='".$categorynum."' ORDER BY date DESC LIMIT $maxaudio OFFSET $offset";
                    $rowcount = mysqli_num_rows(mysqli_query($link,$query));
                    if ($rowcount > 0){
                        if ($result = $link->query($query)) {
                            while ($row = $result->fetch_assoc()) {
                                echo '
                                    <div class="audio-blurb">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h3>'.$row['title'].'</h3>
                                                <p class="sm-font">'.dateFormat($row['date']).'</p>
                                            </div>
                                            <div class="col-md-6 top3">
                                                <p class="text-right">
                                                    <audio controls><source src="'.$dir.'audio/audiofiles/'.$row['file'].'" type="audio/mpeg" /></audio>
                                                    <a href="'.$dir.'audio/audiofiles/'.$row['file'].'" download title="Download"><i class="fas fa-download audio-download"></i></a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <br>';
                                }
                            }
                        } else {
                            echo '<div class="col-md-12"><p class="sm-font">No results.</p></div>';
                        }
                    } else {
                        $noaccess = TRUE;
                        echo '<div class="col-md-12"><p class="sm-font">You do not have access to listen to these files.</p></div>';
                    }
                    ?>

                <p>&nbsp;</p>

                <div class="row justify-content-center">
                    <div class="col-md-3 page-arrow">
                        <?php
                        if ($prevpage >= 0 && $noaccess == FALSE){
                            echo '<p><a class="view-more" href="?pagenum='.$prevpage.'&categorynum='.$categorynum.'"><i class="fas fa-angle-left"></i>&nbsp;Previous Page</a></p>';
                        }
                        ?>
                    </div>
                    <div class="col-md-6 text-middle">
                        <?php
                        for($m=0; $m<$div; $m++){
                            $miriam = $m+1;
                            echo '<a href="?pagenum='.$m.'&categorynum='.$categorynum.'" class="view-more ';

                            if($m == $pagenum){
                                echo "highlight-num";
                            }

                            echo '">'.$miriam.'</a>&nbsp;&nbsp;';                        }
                        ?>
                    </div>
                    <div class="col-md-3 text-right page-arrow">
                        <?php
                        if ($noNext == FALSE && $noaccess == FALSE){
                            echo '<p><a class="view-more" href="?pagenum='.$nextpage.'&categorynum='.$categorynum.'">&nbsp;Next Page&nbsp;<i class="fas fa-angle-right"></i></a></p>';
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
