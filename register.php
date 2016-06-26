<?php 
    $title = 'Register'; 
    include('includes/header.php');
    include('includes/mysqli_connection.php');
    include('includes/common_functions.php');
    include('includes/sidebar-a.php'); 
?>
<script src="js/check_ajax.js"></script>
<div id="content">
    <?php 
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = array();
            $fn = $ln = $e = $p = FALSE;
            
            if(preg_match('/^[\w\'.-]{2,20}$/', trim($_POST['first_name']))) {
                $fn = mysqli_escape_string($connection, trim($_POST['first_name']));
            } else {
                $errors[] = 'first_name';
            }
            
            if(preg_match('/^[\w\'.-]{2,20}$/', trim($_POST['last_name']))) {
                $ln = mysqli_escape_string($connection, trim($_POST['last_name']));
            } else {
                $errors[] = 'last_name';
            }
            
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $e = mysqli_escape_string($connection, trim($_POST['email']));
            } else {
                $errors[] = 'email';
            }
            
            if(preg_match('/^[\w\'.-]{4,20}$/', $_POST['password1'])) {
                if($_POST['password1'] != $_POST['password2']) {
                    $errors[] = "not_match";
                } else {
                    $p = mysqli_escape_string($connection, trim($_POST['password1']));
                }
            } else {
                $errors[] = 'password';
            }
            
            if($fn && $ln && $e && $p) {
                $code = md5(uniqid(rand(), true));
                $q = "insert into users(first_name, last_name, email, password, active, registration_date) 
                values ('{$fn}', '{$ln}', '{$e}', '" . SHA1($p) . "', '{$code}', NOW())";
                $r = mysqli_query($connection, $q);
                if(mysqli_affected_rows($connection) > 0) {
                    $body = "Cảm ơn bạn đã đăng ký thành viên " . BASE_URL . "admin/activate.php?e=" . urlencode($e) . "&k=" . $code;
                    echo $body;
                    if(mail($e, "iZ", $body, "From: iz@localhost")) {
                        $message = "<p class='success'>Đăng kí thành công!!!</p>";
                        $_POST = array();
                    } else {
                        $message = "<p class='warning'>Không thể gửi email, xin lỗi vì sự bất tiện này</p>";
                    }
                } else {
                    $message = "<p class='warning'>Email đã tồn tại trong hệ thống</p>";
                }
            } else {
                $message = "<p class='warning'>Please input all required fields</p>";
            }
        }
    ?>
    
    <h2>Register</h2>
    <form action="register.php" method="post">
        <?PHP if(isset($message)) {echo $message;}?>
        <fieldset>
       	    <legend>Register</legend>
                <div>
                    <label for="First Name">First Name <span class="required">*</span>
                        <?PHP if(isset($errors) && in_array('first_name',$errors)) { echo "<p class='warning'>Please enter first name</p>";} ?>
                    </label> 
    	           <input type="text" name="first_name" size="20" maxlength="20" value="<?PHP if(isset($_POST['first_name'])) { echo $_POST['first_name']; } ?>" tabindex='1' />
                </div>
                
                <div>
                    <label for="Last Name">Last Name <span class="required">*</span>
                        <?PHP if(isset($errors) && in_array('last_name', $errors)) { echo "<p class='warning'>Please enter last name</p>";} ?>
                    </label> 
    	           <input type="text" name="last_name" size="20" maxlength="40" value="<?PHP if(isset($_POST['last_name'])) { echo $_POST['last_name']; } ?>" tabindex='2' />
                </div>
                
                <div>
                    <label for="email">Email <span class="required">*</span>
                        <?PHP if(isset($errors) && in_array('email', $errors)) { echo "<p class='warning'>Please enter email</p>";} ?>
                    </label> 
    	           <input type="text" name="email" id="email" size="20" maxlength="80" value="<?PHP if(isset($_POST['email'])) { echo $_POST['email']; } ?>" tabindex='3' />
                    <span id="available"></span>
                </div>
                
                <div>
                    <label for="password1">Password <span class="required">*</span>
                        <?PHP if(isset($errors) && in_array('password', $errors)) { echo "<p class='warning'>Please enter password</p>";} ?>
                    </label> 
    	           <input type="password" name="password1" size="20" maxlength="20" value="<?PHP if(isset($_POST['password1'])) { echo $_POST['password1']; } ?>" tabindex='4' />
                </div>
                
                <div>
                    <label for="password2">Confirm Password <span class="required">*</span> 
                        <?PHP if(isset($errors) && in_array('not_match', $errors)) { echo "<p class='warning'>Password not match</p>";} ?>
                    </label>
    	           <input type="password" name="password2" size="20" maxlength="20" value="" tabindex='5' />
                </div>
        </fieldset>
        <p><input type="submit" name="submit" value="Register" /></p>
    </form>
</div><!--end content-->
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>