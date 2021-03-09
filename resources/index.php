<?php
$dir="../";
include $dir."inc/session-start.php";
$title = "Resources";
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";

?>

<!DOCTYPE html>
<html lang="en">

<body>
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <div class="row justify-content-center notice shadow">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 overflow">
                        <a class="home-icon" href="<?php echo $dir;?>home"><i class="fas fa-home"></i></a>
                        <h2><i class="fas fa-link"></i>&nbsp;Resources</h2>
                        <?php
                        $query = "SELECT * from resources ORDER BY rank ASC";
                        if ($result = $link->query($query)) {
                            while ($row = $result->fetch_assoc()) {
                                $categories_ok = explode(", ", $row['category']);
                                $printit = FALSE;
                                foreach ($categories_ok as $val){
                                    if ($session_row[$val]){
                                        $printit = TRUE;
                                    }
                                }

                                if ($printit){
                                    echo "<div class='blurb'>";
                                    echo "<a href='".$dir."resources/details.php?id=".$row['id']."' class='view-more'>".$row['rank'].". ".$row['title']."</a>";
                                    echo "</div><br>";
                                }
                            }
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
