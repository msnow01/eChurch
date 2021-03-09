<?php

//used to change full_name category to name
function toLowerNoSpace($val){
    $string = str_replace(" ", "", $val);
    $string2 = strtolower($string);
    $string3 = preg_replace("/[^a-zA-Z]/", "", $string2);
    return $string3;
}

function upperWords($val){
    $string1 = str_replace("(","( ",$val);
    $string2 = ucwords($string1);
    $string3 = str_replace("( ","(",$string2);
    return $string3;
}

//used to print out category options in users-details.php
function printCheck($type, $user_row){
    include "connection.php";
    $list_name = array();
    $list_full_name= array();
    $query = "SELECT * from categories WHERE type='".$type."'";
    if ($result = $link->query($query)) {
        while ($row = $result->fetch_assoc()) {
            array_push($list_name, $row['name']);
            array_push($list_full_name, $row['full_name']);
        }
    }
    echo '<br><h3>'.$type.'</h3>';
    for ($i=0; $i<count($list_name); $i++){
        echo '<p><label for="'.$list_name[$i].'" class="display-none">'.$list_full_name[$i].'</label>';
        echo '<input name="'.$list_name[$i].'"  type="checkbox" value="1"';
        if ($user_row[$list_name[$i]] == 1){
            echo ' checked ';
        }
        echo '>&nbsp;'.$list_full_name[$i].'</p>';
    }
}

//used to print out user info in admin dashboard (users.php)
function userTable($type){
    include "connection.php";
    echo "<h3>".$type."</h3>";
    $query = "SELECT * from users WHERE type='".$type."' ORDER BY name";
    $rowcount = mysqli_num_rows(mysqli_query($link,$query));
    if ($rowcount > 0){
        echo '<table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" width="20%">Name</th>
                    <th scope="col" width="30%">Email</th>
                    <th scope="col" width="20%">City</th>
                    <th scope="col" width="20%">Country</th>
                    <th scope="col" width="10%" class="text-middle">Manage</th>
                </tr>
            </thead>
        <tbody>';
        if ($result = $link->query($query)) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td class="align-middle">'.$row['name'].'</td>';
                echo '<td class="align-middle">'.$row['email'].'</td>';
                echo '<td class="align-middle">'.$row['city'].'</td>';
                echo '<td class="align-middle">'.$row['country'].'</td>';
                echo '<td class="text-middle align-middle">';
                echo '<a href="users-details.php?id='.$row['id'].'" title="Edit user"><i class="admin-link fas fa-edit"></i></a>';
                if ($type != "SUPER"){
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                    echo '<a title="Delete user" href="" data-toggle="modal" data-target="#deleteuser'.$row['id'].'">';
                    echo '<i class="admin-link fas fa-trash-alt"></i></a>';
                }
                echo '</td></tr>';
            }
        }
    } else {
        echo "<p>No results</p>";
    }
    echo '</tbody></table>';
    echo '<p>&nbsp;</p>';
}

//used for the admin dashboard
function printModule($name, $icon){
    echo '
    <a href="'.toLowerNoSpace($name).'.php" class="col-md-3 shadow info-box">
    <h3>'.$name.'</h3>
    <i class="'.$icon.' admin-icon"></i>
    </a>';
}

function dateFormat($date){
    return date('l, M j, Y', strtotime($date));
}

function tooltip($text, $break){
    echo '<div class="tooltipdiv"><i class="fas fa-question-circle"></i></div>
    <div class="tooltiptext">'.$text.'</div>
    <p>&nbsp;</p>';

    if ($break == TRUE){
        echo '<br>';
    }
}

?>
