<?php 
/*
 * this is the file to POST to when you want to delete a social media icon
 */

if (isset($_POST['file_path'])) {
    $was_deleted = unlink($_POST['file_path']);
    //echo (int)$was_deleted; //////////////////////////////////////
    if ($was_deleted) {
        exit();
    } else {
        exit("An error occured when attempting to delete your social media icon from the server. If this issue persists, please contact the plugin author.");
    }
}
?>