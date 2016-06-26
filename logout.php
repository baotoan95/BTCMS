<?php 
    $title = 'Logout'; 
    include('includes/header.php');
    include('includes/mysqli_connection.php');
    include('includes/common_functions.php');
    include('includes/sidebar-a.php'); 
?>
<div id="content">
    <?PHP
        if(isset($_SESSION['user_id'])) {
            $_SESSION = array();
            session_destroy();
            setcookie(session_name(), '', time() - 3600);
        }
        redirect_to();
    ?>
</div><!--end content-->
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>