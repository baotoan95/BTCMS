<?php 
    $title = 'Change password';
    include('includes/header.php');
    include('includes/mysqli_connection.php');
    include('includes/common_functions.php');
    include('includes/sidebar-a.php'); 
?>
<div id="content">
    <?PHP
        is_logged_in();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = array();
            $cur_pass = $pass1 = $pass2 = FALSE;
            if(isset($_POST['cur_password']) && preg_match('/^[\w\'.-]{4,20}$/', trim($_POST['cur_password']))) {
                $cur_pass = mysqli_real_escape_string($connection, $_POST['cur_password']);
            } else {
                $errors[] = 'cur_password';
            }
            
            if(isset($_POST['password1']) && preg_match('/^[\w\'.-]{4,20}$/', $_POST['password1'])) {
                $pass1 = mysqli_real_escape_string($connection, $_POST['password1']);
            } else {
                $errors[] = 'password1';
            }
            
            if(isset($_POST['password2']) && preg_match('/^[\w\'.-]{4,20}$/', $_POST['password2'])) {
                $pass2 = mysqli_real_escape_string($connection, $_POST['password2']);
            } else {
                $errors[] = 'password2';
            }
            
            if($cur_pass && $pass1 && $pass2) {
                if($pass1 == $pass2) {
                    $pass = SHA1($cur_pass);
                    $q = "select first_name from users where password = '{$pass}' and user_id = '{$_SESSION['user_id']}' limit 1";
                    $r = mysqli_query($connection, $q);
                    confirm_query($r, $q);
                    if(mysqli_num_rows($r) == 1) {
                        $q = "update users set password = SHA1('$pass1') where user_id = '{$_SESSION['user_id']}' limit 1";
                        $r = mysqli_query($connection, $q);
                        confirm_query($r, $q);
                        if(mysqli_affected_rows($connection) == 1) {
                            $message = "<p class='success'>Password have been changed successful</p>";
                        } else {
                            $message = "<p class='warning'>System error</p>";
                        }
                    } else {
                        $message = "<p class='warning'>Current password incorrect</p>";
                    }
                } else {
                    $message = "<p class='warning'>Password confirm is not match</p>";
                }
            } else {
                $message = "<p class='warning'>Please input all in required fields</p>";
            }
        }
    ?>
    <h2>Change Password</h2>
    <?PHP
        if(isset($message)) {echo $message;}
    ?>
    <form action="" method="post">
        <fieldset>
    		<legend>Change Password</legend>
            <div>
                <label for="Current Password">Current Password
                <?PHP if(isset($errors) && in_array('cur_password', $errors)) {echo "<p class='warning'>Password too short</p>";}?>
                </label> 
                <input type="password" name="cur_password" value="<?PHP isset($_POST['cur_password']) ? $_POST['cur_password'] : ''?>" size="20" maxlength="40" tabindex='1' />
            </div>
    
    		<div>
                <label for="New Password">New Password
                <?PHP if(isset($errors) && in_array('password1', $errors)) {echo "<p class='warning'>Password too short</p>";}?>
                </label> 
                <input type="password" name="password1" value="" size="20" maxlength="40" tabindex='2' />
            </div>
            
            <div>
                <label for="Confirm Password">Confirm Password
                <?PHP if(isset($errors) && in_array('password2', $errors)) {echo "<p class='warning'>Password too short</p>";}?>
                </label> 
                <input type="password" name="password2" value="" size="20" maxlength="40" tabindex='3' />
            </div>
    	</fieldset>
     <div><input type="submit" name="submit" value="Update Password" tabindex='4' /></div>
    </form>
</div><!--end content-->
<?php
    include('includes/sidebar-b.php');
    include('includes/footer.php'); 
?>

