<?php 
    include('includes/mysqli_connection.php');
    include('includes/common_functions.php');
?>
<?PHP
    if(isset($_GET['email']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
        $q = "select user_id from users where email = '{$_GET['email']}' limit 1";
        $r = mysqli_query($connection, $q);
        confirm_query($r, $q);
        if(mysqli_num_rows($r) == 1) {
            echo "NO";
        } else {
            echo "YES";
        }
    }
?>