<?php
$menu_array = array("Home","Video","Audio","Notices", "Resources", "Contact");

//only print background if not in admin section
$is_admin = explode("/", $_SERVER[REQUEST_URI]);
if ($is_admin[1] != "admin"){
    echo '<div class="bible"></div>';
}

include "header-bar.php";
?>

<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark shadow justify-content-between">
    <?php
    if ($_SESSION['login_type']){
    ?>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <?php
            foreach ($menu_array as $val){
                echo '<li><a href="'.$dir.$val.'" class="nav-link" title="'.$val.'">'.$val.'</a></li>';
            }
            ?>
        </ul>
        <div>
            <?php
            if ($_SESSION['login_type'] == "SUPER" || $_SESSION['login_type'] == "ADMIN") {
                echo '<a title="Administration Dashboard" href="'.$dir.'admin"><i class="fas fa-cog"></i></a>';
            }
            ?>
            <a title="My Account" href="<?php echo $dir;?>account/my-account.php"><i class="fas fa-user"></i></a>
            <a title="Log Out" href="<?php echo $dir;?>account/logout.php"><i class="fas fa-sign-out-alt"></i></a>
        </div>
      </div>

      <?php
      }
      ?>
    </nav>



    <?php

    if ($_SESSION['login_type']){

        $query_alert_row = "SELECT * from alerts WHERE category !='' ORDER BY id DESC";
        $alert_row_menu = mysqli_fetch_assoc(mysqli_query($link,$query_alert_row));
        $alert_row_menu_count = mysqli_num_rows(mysqli_query($link,$query_alert_row));

        if ($alert_row_menu_count != 0){

            $alert_row_categories = explode(", ", $alert_row_menu['category']);
            $to_print_alert = FALSE;

            foreach ($alert_row_categories as $val){
                if ($session_row[$val]){
                    $to_print_alert = TRUE;
                }
            }

            if ($to_print_alert == TRUE){

                echo '<div class="alert-bar">
                    <p><i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;'.$alert_row_menu['text'].'</p>
                </div>';
            }
        }
    }
