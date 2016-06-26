<?PHP
    $title = 'Login';
    include("includes/common_functions.php");
    include("includes/header.php");
    include("includes/mysqli_connection.php");
    include("includes/sidebar-a.php"); 
?>
<div id="content">
    <?PHP
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = array();
            if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $e = mysqli_real_escape_string($connection, $_POST['email']);
                $q = "select user_id from users where email = '{$e}'";
                $r = mysqli_query($connection, $q);
                confirm_query($r, $q);
                if(mysqli_num_rows($r) == 1) {
                    list($uid) = mysqli_fetch_array($r, MYSQLI_NUM);
                }
            } else {
                $errors[] = "<p class='warning'>Please input email</p>";
            }
            
            if($uid) {
                $temp_pass = substr(md5(rand()), 3, 10);
                echo $temp_pass;
                $q = "update users set password = SHA1('$temp_pass') where email = '{$e}' limit 1";
                $r = mysqli_query($connection, $q);
                confirm_query($r, $q);
                if(mysqli_affected_rows($connection) > 0) {
                    // Update success
                    mail($e, "Change password", "New pass: {$temp_pass}");
                    $errors[] = "<p class='success'>Your password have been changed successfuly</p>";
                } else {
                    $errors[] = "<p class='warning'>Your password doesn't change due system error</p>";
                }
            } else {
                $errors[] = "<p class='warning'>The email doesn't exist in system</p>";
            }
        }
    ?>
    <form id="login" action="" method="post">
        <?PHP
        if(isset($errors)) {
            foreach($errors as $error) {
                echo $error;
            }   
        }
        ?>
        <fieldset>
        	<legend>Retrieve Password</legend>
        	<div>
                <label for="email">Email: </label> 
                
                <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email']);} ?>" size="40" maxlength="80" tabindex="1" />
            </div>
        </fieldset>
        <div><input type="submit" name="submit" value="Retrieve Password" /></div>
    </form>
</div><!--end content-->
<?PHP include("includes/sidebar-b.php"); ?>
<?PHP include("includes/footer.php"); ?>