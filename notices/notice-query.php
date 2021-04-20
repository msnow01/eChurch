<?php

$noticestoprint = array();

$query = "SELECT * from notices WHERE category != '' ORDER BY date DESC";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {

        //get list of notices to print for current user
        $add_to_list = FALSE;
        $cat = explode(", ", $row['category']);
        foreach ($cat as $val){
            if ($session_row[$val]){
                $add_to_list = TRUE;
            }
        }
        if ($add_to_list == TRUE){
            array_push($noticestoprint, $row['id']);
        }
    }
}

?>
