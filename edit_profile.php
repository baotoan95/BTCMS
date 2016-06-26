<?PHP
    $title = 'Profile';
    include("includes/common_functions.php");
    include("includes/header.php");
    include("includes/mysqli_connection.php");
    include("includes/sidebar-a.php");
    is_logged_in();
?>
<?PHP
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = array();
        $trimmed = array_map('trim', $_POST);
        if(preg_match('/^[\w]{2,20}$/', $trimmed['first_name'])) {
            $fn = $trimmed['first_name'];
        } else {
            $errors[] = 'first_name';
        }
        if(preg_match('/^[\w]{2,20}$/', $trimmed['last_name'])) {
            $ln = $trimmed['last_name'];
        } else {
            $errors[] = 'last_name';
        }
        if(filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
            $e = $trimmed['email'];
        } else {
            $errors[] = 'email';
        }
        $web = (!empty($trimmed['website'])) ? $trimmed['website'] : NULL;
        $yahoo = (!empty($trimmed['yahoo'])) ? $trimmed['yahoo'] : NULL;
        $bio = (!empty($trimmed['bio'])) ? $trimmed['bio'] : NULL;
        
        if(empty($errors)) {
            $q = "update users set first_name=?, last_name=?, email=?, yahoo=?, website=?, bio=? where user_id=? limit 1";
            $stmt = mysqli_prepare($connection, $q);
            mysqli_stmt_bind_param($stmt, 'ssssssi', $fn, $ln, $e, $yahoo, $web, $bio, $_SESSION['user_id']);
            mysqli_stmt_execute($stmt) or die('MySQL error: ' . mysqli_stmt_error());
            if(mysqli_stmt_affected_rows($stmt) == 1) {
                $message = "<p class='success'>Your profile is updated success</p>";
            } else {
                $message = "<p class='warning'>System error</p>";
            }
        }
    }
?>
<div id="content">
<?PHP
    $q = "select * from users where user_id='{$_SESSION['user_id']}' limit 1";
    $r = mysqli_query($connection, $q);
    if(mysqli_num_rows($r) == 1) {
        $user = mysqli_fetch_array($r, MYSQLI_ASSOC);
    }
?>
<h2>User Profile</h2>
<?PHP 
    if(!empty($message)) echo $message;
?>
<form enctype="multipart/form-data" action="processor/avatar.php" method="post"> 
    <fieldset>
		<legend>Avatar</legend>
		<div>
            <img class="avatar" src="uploads/images/<?php echo (is_null($user['avatar']) ? "no_avatar.jpg" : $user['avatar']); ?>" alt="avatar" />
            <p>Please select a JPEG or PNG image of 512Kb or smaller to use as avatar<p>
            </label> 
            <input type="hidden" name="MAX_FILE_SIZE" value="624288" />
            <input type="file" name="image" />
            <p><input class="change" type="submit" name="upload" value="Save changes" /></p>
        </div>
  </fieldset> 
</form>

<form action="" method="post">        
    <fieldset>
        <legend>User Info</legend>
        <div>
            <label for="first-name">First Name
                <?PHP if(isset($errors) && in_array('first_name', $errors)) echo "<p class='warning'>Please input first name</p>";?>
            </label> 
            <input type="text" name="first_name" value="<?php echo (is_null($user['first_name']) ? "" : strip_tags($user['first_name'])); ?>" size="20" maxlength="40" tabindex='1' />
        </div>
        
        <div>
            <label for="last-name">Last Name
                <?PHP if(isset($errors) && in_array('last_name', $errors)) echo "<p class='warning'>Please input last name</p>";?>
            </label> 
            <input type="text" name="last_name" value="<?php echo (is_null($user['last_name']) ? "" : strip_tags($user['last_name'])); ?>" size="20" maxlength="40" tabindex='1' />
        </div>
  </fieldset>
  <fieldset>
        <legend>Contact Info</legend>
        <div>
            <label for="email">Email
                <?PHP if(isset($errors) && in_array('email', $errors)) echo "<p class='warning'>Please input email</p>";?>
            </label> 
            <input type="text" name="email" value="<?php echo (is_null($user['email']) ? "" : strip_tags($user['email'])); ?>" size="20" maxlength="40" tabindex='3' />
        </div>
        
        <div>
            <label for="website">Website</label> 
            <input type="text" name="website" value="<?php echo (is_null($user['website']) ? "" : strip_tags($user['website'])); ?>" size="20" maxlength="40" tabindex='4' />
        </div>
        
        <div>
            <label for="yahoo">Yahoo Messenger</label> 
            <input type="text" name="yahoo" value="<?php echo (is_null($user['yahoo']) ? "" : strip_tags($user['yahoo'])); ?>" size="20" maxlength="40" tabindex='5' />
        </div>
  </fieldset> 
  <fieldset>
        <legend>About Yourself</legend>
        <div>
            <textarea cols="50" rows="20" name="bio"><?php echo (is_null($user['bio']) ? "" : htmlentities($user['bio'], ENT_COMPAT, 'UTF-8')); ?></textarea>
        </div>
  </fieldset>   
 <div><input type="submit" name="submit" value="Save Changes" /></div>
</form>
</div><!--end content-->
<?PHP
    include("includes/sidebar-b.php");
    include("includes/footer.php"); 
?>