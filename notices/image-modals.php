<?php

$noticestoprint = array();

$query = "SELECT * from notices WHERE category != '' ORDER BY date DESC";
if ($result = $link->query($query)) {
    while ($row = $result->fetch_assoc()) {

        //get image preview
        for ($i=0; $i<=$maximages; $i++){
            $val = "image".$i;
            if ($row[$val]){
                echo '<div class="modal fade" id="notice'.$row['id'].'preview'.$val.'" data-backdrop="static" tabindex="-1" aria-labelledby="preview image" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                                <img title="Image preview" src="'.$dir.'notices/noticeimages/'.$row[$val].'" width="100%">
                            </div>
                            <div class="modal-footer">
                                <a class="mr-auto" title="Download this image" href="'.$dir.'notices/noticeimages/'.$row[$val].'" download><i class="fas fa-download image-download"></i></a>
                                <a href="" class="btn btn-dark" data-dismiss="modal">Close</a>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }

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
