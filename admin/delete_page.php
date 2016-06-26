<?PHP 
    include("../includes/common_functions.php"); 
    include("../includes/header.php"); 
    include("../includes/mysqli_connection.php"); 
    include("../includes/sidebar-admin.php");
    admin_access();
?>
<div id="content">
    <?PHP
        if(isset($_GET['pid'], $_GET['pname']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array("min-range" => 1))) {
            $pid = $_GET['pid'];
            $pname = $_GET['pname'];
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if(isset($_POST['delete']) && $_POST['delete'] == 'yes') {
                    $q = "delete from pages where page_id = {$pid} limit 1";
                    $r = mysqli_query($connection, $q);
                    confirm_query($r, $q);
                    if(mysqli_affected_rows($connection) == 1) {
                        $messages = "<p class='success'>Page was deleted</p>";
                    } else {
                        $messages = "<p class='warning'>The system error</p>";
                    }
                } else {
                    redirect_to('admin/view_pages.php');
                }
            }
        } else {
            redirect_to('admin/view_pages.php');
        }
    ?>
    <h2> Delete page: <?php if(isset($_GET['pname'])) echo htmlentities($pname, ENT_COMPAT, 'UTF-8') ?></h2> 
    <?php if(!empty($messages)) echo $messages; ?>
	   <form action="" method="post">
       <fieldset>
            <legend>Delete page</legend>
                <label for="delete">Are you sure?</label>
                <div>
                    <input type="radio" name="delete" value="no" checked="checked" /> No
                    <input type="radio" name="delete" value="yes" /> Yes
                </div>
                <div><input type="submit" name="submit" value="Delete" onclick="return confirm('Are you sure?');" /></div>
        </fieldset>
       </form>
</div><!--end content-->

<?php include('../includes/footer.php'); ?>