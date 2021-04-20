<div class="row">
    <div class="col-md-12">
        <?php echo $homeicon; ?>
        <h2>&nbsp;<i class="fas fa-bell"></i>&nbsp;Notices</h2>
        <?php

        $pagenum = $_GET['pagenum'];

        if (!$pagenum){
            $pagenum = 0;
        }

        $minindex = $pagenum * $maxnotices;
        $maxindex = $minindex + $maxnotices;

        if (count($noticestoprint) == 0){
            echo '<div class="col-md-12"><p class="sm-font">No results.</p></div>';
        } else {

            for ($k=$minindex; $k<$maxindex; $k++){
                $idtoget = $noticestoprint[$k];

                if ($idtoget){

                    $query = "SELECT * from notices WHERE id='".$idtoget."'";
                    $row = mysqli_fetch_assoc(mysqli_query($link,$query));

                    echo "<div class='blurb'>";
                    echo '<h3>'.$row['title'].'</h3>';
                    echo '<p class="sm-font">'.dateFormat($row['date']).'</p>';
                    echo $row['text'];
                    echo "</div><br>";
                }
            }
        }
        ?>
    </div>
</div>
