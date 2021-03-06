<?php
$dir="../";
include $dir."inc/session-start.php";
$title = "Notices";
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
        <div class="row justify-content-center notice shadow">
            <div class="col-md-12">

                <?php
                $homeicon = '<a class="home-icon" href="'.$dir.'home"><i class="fas fa-home"></i></a>';
                include "notice-list.php";

                $div = count($noticestoprint)/$maxnotices;

                $nextpage = $pagenum + 1;
                $prevpage = $pagenum - 1;

                $noNext = FALSE;

                if ($maxindex >= count($noticestoprint)){
                    $noNext = TRUE;
                }

                ?>
                <div class="row justify-content-center">
                    <div class="col-md-3 page-arrow">
                        <?php
                        if ($prevpage >= 0){
                            echo '<p><a class="view-more" href="?pagenum='.$prevpage.'"><i class="fas fa-angle-left"></i>&nbsp;Previous Page</a></p>';
                        }
                        ?>
                    </div>
                    <div class="col-md-6 text-middle">
                        <?php
                        for($m=0; $m<$div; $m++){
                            $miriam = $m+1;
                            echo '<a href="?pagenum='.$m.'" class="view-more ';
                            if($m == $pagenum){
                                echo "highlight-num";
                            }
                            echo '">'.$miriam.'</a>&nbsp;&nbsp;';
                        }
                        ?>
                    </div>
                    <div class="col-md-3 text-right page-arrow">
                        <?php
                        if ($noNext == FALSE){
                            echo '<p><a class="view-more" href="?pagenum='.$nextpage.'">&nbsp;Next Page&nbsp;<i class="fas fa-angle-right"></i></a></p>';
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
