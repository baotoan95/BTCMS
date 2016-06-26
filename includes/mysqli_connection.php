<?php

/**
 * @author GallerySoft.info
 * @copyright 2016
 */

$connection = mysqli_connect("localhost", "root", "", "izcms") or die("Can't connect to database!!!" . " " . mysqli_connect_error());

// Set utf-8
if($connection) {
    mysqli_set_charset($connection, "utf-8");   
}

?>