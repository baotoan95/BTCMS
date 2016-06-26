<?PHP
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = array();
        if(!empty($_POST['name'])) {
            $name = mysqli_real_escape_string($connection, strip_tags($_POST['name']));
        } else {
            $errors[] = 'name';
        }
        if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $email = mysqli_real_escape_string($connection, strip_tags($_POST['email']));
        } else {
            $errors[] = 'email';
        }
        if(!empty($_POST['comment'])) {
            $comment = mysqli_real_escape_string($connection, $_POST['comment']);
        } else {
            $errors[] = 'comment';
        }
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
            $q = "insert into comments (page_id, author, email, comment, comment_date) values ({$_GET['pid']}, '{$name}', '{$email}', '{$comment}', NOW())";
            $r = mysqli_query($connection, $q);
            confirm_query($r, $q);
            if(mysqli_affected_rows($connection) > 0) {
                $message = "<p class='success'>Thank you for your comment</p>";
            } else {
                $message = "<p class='warning'>System error: " . mysqli_error() . "</p>";
            }
        } else {
            $message = "<p class='warning'>Please input all required fields</p>";
        }
    }
    
    // Show comments if avaliable
    $q = "select author, comment_id, comment, DATE_FORMAT(comment_date, '%b %d %y') as date from comments where page_id = {$_GET['pid']}";
    $r = mysqli_query($connection, $q);
    confirm_query($r, $q);
    if(mysqli_num_rows($r) > 0) {
        echo "<ol id='disscuss'>";
        while(list($author, $comment_id, $comment, $date) = mysqli_fetch_array($r, MYSQLI_NUM)) {
            echo "<li class='comment-wrap'>
                    <p class='author'>{$author}</p>
                    <p class='comment-sec'>{$comment}</p>";
                    
                    if(is_admin()) {
                        echo "<a href='#' id='${comment_id}' class='remove'>Delete</a>";
                    }
                     
            echo    "<p class='date'>{$date}</p>  
                  </li>";
        }
        echo "</ol>";
    } else {
        echo "<h2>Be the first to leave a comment</h2>";
    }
?>
<script src="js/delete_comment.js"></script>
<form id="comment-form" action="" method="post">
    <?PHP if(!empty($message)) echo $message;?>
    <fieldset>
    	<legend>Leave a comment</legend>
            <div>
            <label for="name">Name: <span class="required">*</span>
                <?PHP if(isset($errors) && in_array('name', $errors)) {echo "<p class='warning'>Please input your name</p>";}?>
            </label>
            <input type="text" name="name" id="name" value="<?PHP if(isset($_POST['name'])) echo htmlentities($_POST['name']);?>" size="20" maxlength="80" tabindex="1" />
        </div>
        <div>
            <label for="email">Email: <span class="required">*</span>
                <?PHP if(isset($errors) && in_array('email', $errors)) {echo "<p class='warning'>Please input your email</p>";}?>
            </label>
            <input type="text" name="email" id="email" value="<?PHP if(isset($_POST['email'])) echo htmlentities($_POST['email']);?>" size="20" maxlength="80" tabindex="2" />
            </div>
        <div>
            <label for="comment">Your Comment: <span class="required">*</span>
                <?PHP if(isset($errors) && in_array('comment', $errors)) {echo "<p class='warning'>Please input the content</p>";}?>
            </label>
            <div id="comment"><textarea name="comment" rows="10" cols="50" tabindex="3"><?PHP if(isset($_POST['comment'])) echo htmlentities($_POST['comment']);?></textarea></div>
        </div>
        
        <div>
        <label for="captcha">Phiền bạn điền vào giá trị số cho câu hỏi sau: <?PHP echo captcha(); ?><span class="required">*</span>
            <?PHP if(isset($errors) && in_array('captcha', $errors)) {echo "<p class='warning'>Please input a correct answer</p>";}?>
        </label>
            <input type="text" name="captcha" id="captcha" value="<?PHP if(isset($_POST['captcha'])) echo htmlentities($_POST['captcha']);?>" size="20" maxlength="5" tabindex="4" />
        </div>
        
        <div>
        <label for="question"> Phiền bạn xóa giá trị ở trường dưới, trước khi submit form.
            <?PHP if(isset($errors) && in_array('question', $errors)) {echo "<p class='warning'>Please clear this field to empty</p>";}?>
        </label>
            <input type="text" name="question" id="question" value="Xóa đi giá trị này" size="20" maxlength="40" />
        </div>
        
        <div class='website'>
        <label for="website"> Nếu bạn nhìn thấy trường này, thì ĐỪNG điền gì vào hết</label>
            <input type="text" name="url" id="url" value="" size="20" maxlength="20" />
        </div>
        
        <div>
            <label>Điền vào ô reCaptcha
            </label>
        </div>
    </fieldset>
    <div><input type="submit" name="submit" value="Post Comment" /></div>
</form>
