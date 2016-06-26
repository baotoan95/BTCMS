<?php include('../includes/header.php');?>
<?php include('../includes/mysqli_connection.php');?>
<?php include('../includes/common_functions.php');?>
<?php include('../includes/sidebar-admin.php'); ?>
<div id="content">
    <?php 
        if(isset($_GET['e'], $_GET['k']) && filter_var($_GET['e'], FILTER_VALIDATE_EMAIL) && strlen($_GET['k']) == 32) {
            $e = mysqli_escape_string($connection, $_GET['e']);
            $k = mysqli_escape_string($connection, $_GET['k']);
            $q = "update users as u set active = NULL where u.email = '{$e}' and active = '{$k}'";
            $r = mysqli_query($connection, $q);
            confirm_query($r, $q);
            if(mysqli_affected_rows($connection) == 1) {
                echo "<p class='success'>Kích hoạt tài khoản thành công. Hãy <a href='" . BASE_URL . "login.php'>Đăng Nhập</a></p>";
            } else {
                echo "<p class='warning'>Kích hoạt chưa thành công, vui lòng thử lại sau.</p>";
            }
        } else {
            redirect_to();
        }
    ?>
</div><!--end content-->
<?php include('../includes/footer.php'); ?>
