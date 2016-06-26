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
            } else {
                $errors[] = 'email';
            }
            
            if(isset($_POST['password']) && preg_match('/^[\w\'.-]{4,20}$/', $_POST['password'])) {
                $p = mysqli_real_escape_string($connection, $_POST['password']);
            } else {
                $errors[] = 'password';
            }
            
            if(empty($errors)) {
                $q = "select user_id, first_name, last_name, user_level from users where (email='{$e}' and password='" . SHA1($p) . "') and active is null limit 1";
                $r = mysqli_query($connection, $q);
                if(mysqli_num_rows($r) == 1) {
                    list($user_id, $first_name, $last_name, $user_level) = mysqli_fetch_array($r, MYSQLI_NUM);
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['first_name'] = $first_name;
                    $_SESSION['last_name'] = $last_name;
                    $_SESSION['user_level'] = $user_level;
                    redirect_to();
                } else {
                    $message = "<p class='warning'>Username or Password is not match. Or you have not actived your account.</p>";
                }
            } else {
                $message = "<p class='warning'>Please fill in all required fields.</p>";
            }
        }
    ?>
	<h2>Login</h2>
    <form id="login" action="" method="post">
        <?PHP if(isset($message)) {echo $message;}?>
        <fieldset>
        	<legend>Login</legend>
            	<div>
                    <label for="email">Email:
                        <?PHP if(isset($errors) && in_array('email',$errors)) { echo "<p class='warning'>Please input email</p>";} else {echo "not";}?>
                    </label>
                    <input type="text" name="email" id="email" value="<?PHP if(isset($_POST['email'])) { echo htmlentities($_POST['email']);}?>" size="20" maxlength="80" tabindex="1" />
                </div>
                <div>
                    <label for="pass">Password:
                        <?PHP if(isset($errros) && in_array('password',$errors)) { echo "<p class='warning'>Please input password</p>";}?>
                    </label>
             <input type="password" name="password" id="pass" value="<?PHP if(isset($_POST['password'])) { echo $_POST['password'];}?>" size="20" maxlength="20" tabindex="2" />
                </div>
        </fieldset>
        <div><input type="submit" name="submit" value="Login" /></div>
    </form>
    <p><a href="retrieve_password.php">Forgot password?</a></p>
</div><!--end content-->
<?PHP include("includes/sidebar-b.php"); ?>
<?PHP include("includes/footer.php"); ?>