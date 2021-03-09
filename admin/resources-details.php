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
$title = "Resource Manager Details";
include $dir."inc/header.php";
include $dir."inc/connection.php";
include $dir."inc/functions.php";
include $dir."inc/tiny.php";
$resource_id = $_GET['id'];

//save changes form functionality
if (isset($_POST['submitchanges']) || isset($_POST['submitaddresource'])) {

    //get values from form
    $resourcetitle = upperWords(addslashes($_POST['resourcetitle']));
    $text = addslashes($_POST['text']);
    $category = "";
    $newRank = $_POST['newRank'];
    $oldRank = $_POST['oldRank'];

    //set max and min values for rank
    $query = "SELECT * from resources";
    $resources_count = mysqli_num_rows(mysqli_query($link,$query));
    if($oldRank){
        if ($newRank > $resources_count){
            $newRank = $resources_count;
        }
    }

    if (!$oldRank){
        if ($newRank > $resources_count){
            $newRank = $resources_count + 1;
        }
    }

    if ($newRank < 1){
        $newRank = 1;
    }

    //get Category values
    $query = "SELECT * from categories WHERE type='RESOURCE'";
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

    //edit rankings of all other rows
        if ($newRank != $oldRank){
            if (!$oldRank){
                $query = "SELECT * from resources WHERE rank >= $newRank";
            } else if ($oldRank > $newRank){
                $query = "SELECT * from resources WHERE rank >= $newRank AND rank <= $oldRank";
            } else if ($oldRank < $newRank){
                $query = "SELECT * from resources WHERE rank <= $newRank AND rank >= $oldRank";
            }
            if ($result = $link->query($query)) {
                while ($row = $result->fetch_assoc()) {
                    if ($oldRank > $newRank || !$oldRank){
                        $currRank = $row['rank'] +  1;
                    } else if ($oldRank < $newRank){
                        $currRank = $row['rank'] - 1;
                    }
                    $query2 = "UPDATE resources SET rank='".$currRank."' WHERE ID='".$row['id']."'";
                    if (!mysqli_query($link,$query2)){
                        $error1 = "<div class='alert alert-danger'>Sorry, there was an error updating the rank. Please try again.</div>";
                    }
                }
            }
        }

    //update existing values
    if (isset($_POST['submitchanges'])){
        $query = "UPDATE resources SET title='".$resourcetitle."', category='".$category."', text='".$text."', rank='".$newRank."' WHERE ID='".$resource_id."'";
        if (!mysqli_query($link,$query)){
            $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
        }
    }

    // add new resource
    if (isset($_POST['submitaddresource'])){
        $query = "INSERT INTO resources (title, category, text, rank) VALUES ('".$resourcetitle."', '".$category."', '".$text."', '".$newRank."')";
        if (!mysqli_query($link,$query)){
            $error1 = "<div class='alert alert-danger'>Sorry, there was an error. Please try again.</div>";
        } else {
            echo '<meta http-equiv="refresh" content="0; URL='.$dir.'admin/resources.php" />';
        }
    }

}

$query = "SELECT * from resources WHERE id='".$resource_id."'";
$resource_row = mysqli_fetch_assoc(mysqli_query($link,$query));

?>

<body class="admin">
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <h2><?php echo $title; ?></h2>
        <p><a href="<?php echo $dir;?>admin/resources.php" class="view-more" title="Back"><i class="fas fa-angle-left"></i>&nbsp;See all resources</a></p>
        <?php echo $error1; ?>

        <p>&nbsp;</p>

        <div class="row justify-content-around notice shadow">
            <div class="col-md-12">
                <?php
                if ($resource_id){
                    echo "<h2>".$resource_row['title']."</h2>";
                } else {
                    echo "<h2>Add Resource</h2>";
                }
                ?>
                <hr>
                <br>
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                                <h3><label for="resourcetitle">Title</label></h3>
                                <p><input type="text" required name="resourcetitle" autocomplete="off" class="form-control" value="<?php echo $resource_row['title']; ?>"></p>

                                <p>&nbsp;</p>

                                <h3><label for="text">Text</label></h3>
                                <textarea name="text"><?php echo $resource_row['text']; ?></textarea>

                                <p>&nbsp;</p>
                                <h3 style="float:left">Rank</h3>

                                <?php
                                $tooltip_text = "Choose the order in which your resource will appear. Default is 1 at the top of the list.";
                                tooltip($tooltip_text, TRUE);
                                ?>

                                <label for="oldRank" class="display-none">Old Rank</label>
                                <input type="number" class="display-none" name="oldRank" value="<?php echo $resource_row['rank'];?>">


                                <label for="newRank" class="display-none">Rank</label>
                                <input type="number" class="form-control" min="1" required name="newRank" value="<?php echo $resource_row['rank'];?>">


                                <p>&nbsp;</p>

                                <h3 style="float:left">Category</h3>

                                <?php
                                $tooltip_text = "Choose who will have permissions to see your resource. If you don't select a category, the resource will not be available on the site.";
                                tooltip($tooltip_text, TRUE);

                                $query = "SELECT * from categories WHERE type='RESOURCE'";
                                if ($result = $link->query($query)) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<p>';
                                        echo '<input type="checkbox" ';

                                        //select existing ones
                                        $resource_categories = explode(", ", $resource_row['category']);
                                        foreach ($resource_categories as $val){
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

                                <br>
                                <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if ($resource_id){
                                echo '<p><label for="submitchanges" class="display-none">Save Changes</label>
                                <input type="submit" name="submitchanges" value="Save Changes" class="btn btn-dark"></p>';
                            } else {
                                echo '<p><label for="submitaddresource" class="display-none">Add Resource</label>
                                <input type="submit" name="submitaddresource" value="Add Resource" class="btn btn-dark"></p>';
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
