<?php 
    $title = 'Contact Us';
    include('includes/header.php');
    include('includes/mysqli_connection.php');
    include('includes/common_functions.php');
    include('includes/sidebar-a.php'); 
?>
<div id="content">
    <?PHP
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = array();
            
            // Chống spam
            // gọi từng giá trị trong post truyền vào hàm clean_email
            $clean = array_map('clean_email', $_POST);
            
            if(empty($clean['name'])) {
                $errors[] = 'name';
            }
            if(!preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/', $clean['email'])) {
                $errors[] = 'email';
            }
            if(empty($clean['comment'])) {
                $errors[] = 'comment';
            }
            
            // honey pot
            if(isset($_POST['captcha']) && trim($_POST['captcha']) != $_SESSION['captcha']['answer']) {
                $errors[] = 'captcha';
            }
            if(!empty($_POST['question'])) {
                $errors[] = 'question';
            }
            if(!empty($_POST['url'])) {
                redirect_to('thankyou.html');
            }
            
            if(empty($errors)) {
                $body = "Name {$clean['name']},\n\n Comment:\n" . strip_tags($clean['comment']);
                if(mail('baotoan.95@gmail.com', 'CMS', $body, 'localhost@localhost')) {
                    echo "<p class='success'>Thank you for your feedback</p>";
                    $_POST = array();
                } else {
                    echo "<p class='warning'>Sorry, this feature has error</p>";
                }
            }
        }
    ?>
    <form id="contact" action="" method="post">
        <fieldset>
        	<legend>Contact</legend>
                <div>
                    <label for="Name">Your Name: <span class="required">*</span>
                        <?php if(isset($errors) && in_array('name',$errors)) { echo "<span class='warning'>Please enter your name.</span>";}?>
                    </label>
                    <input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) {echo htmlentities($_POST['name'], ENT_COMPAT, 'UTF-8');} ?>" size="20" maxlength="80" tabindex="1" />
                </div>
            	<div>
                    <label for="email">Email: <span class="required">*</span>
                    <?php if(isset($errors) && in_array('email',$errors)) {echo "<span class='warning'>Please enter your email.</span>";} ?>
                    </label>
                    <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8');} ?>" size="20" maxlength="80" tabindex="2" />
                </div>
                <div>
                    <label for="comment">Your Message: <span class="required">*</span>
                        <?php if(isset($errors) && in_array('comment',$errors)) {echo "<span class='warning'>Please enter your message.</span>";} ?>
                    </label>
                    <div id="comment"><textarea name="comment" rows="10" cols="45" tabindex="3"><?php if(isset($_POST['comment'])) {echo htmlentities($_POST['comment'], ENT_COMPAT, 'UTF-8');} ?></textarea></div>
                </div>
                
                <div>
                <label for="captcha">Phiền bạn điền vào giá trị số cho câu hỏi sau: <?php echo captcha(); ?><span class="required">*</span>
                    <?php if(isset($errors) && in_array('captcha',$errors)) {echo "<span class='warning'>Please give a correct answer.</span>";}?></label>
                    <input type="text" name="captcha" id="captcha" value="" size="20" maxlength="5" tabindex="4" />
                </div>
            
                <div class='website'>
                    <label for="website"> Nếu bạn nhìn thấy trường này, thì ĐỪNG điền gì vào hết</label>
                    <input type="text" name="url" id="url" value="" size="20" maxlength="20" />
                </div>
        </fieldset>
        <div><input type="submit" name="submit" value="Send Email" tabindex="3" /></div>
    </form>
</div><!--end content-->
<?php
    include('includes/sidebar-b.php');
    include('includes/footer.php'); 
?>