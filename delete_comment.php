<?php 
    include('includes/mysqli_connection.php');
    include('includes/common_functions.php');
?>
<?PHP
    if(isset($_POST['cid']) && filter_var($_POST['cid'], FILTER_VALIDATE_INT)) {
        $q = "delete from comments where comment_id = {$_POST['cid']} limit 1";
        $r = mysqli_query($connection, $q);
        confirm_query($r, $q);
        if(mysqli_affected_rows($connection) == 1) {
            echo "YES";
        } else {
            echo "NO";
        }
    }
?>