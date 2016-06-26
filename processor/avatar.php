<?php 
    session_start();
    include('../includes/mysqli_connection.php');
    include('../includes/common_functions.php');
?>
<?PHP
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_FILES['image'])) {
            $errors = array();
            $allowed = array('image/jpeg', 'image/jpg', 'image/png', 'images/x-png');
            if(in_array(strtolower($_FILES['image']['type']), $allowed)) {
                $ext = end(explode('.', $_FILES['image']['name'])); // L?y ph?n t? cu?i cùng trong m?ng
                $renamed = uniqid(rand(), true) . '.' . $ext;
                
                if(!move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/images/' . $renamed)) {
                    $errors[] = "<p class='error'>System error</p>";
                } else {
                    echo "ok";
                }
            } else {
                $errors[] = "<p class='error'>Your format file is not permission</p>";
            }
        }
        
        if($_FILES['image']['error'] > 0) {
            $errors[] = "<p class='error'>The file could not be uploaded because: <strong>";
    
            // Print the message based on the error
            switch ($_FILES['image']['error']) {
                case 1:
                    $errors[] .= "The file exceeds the upload_max_filesize setting in php.ini";
                    break;
                    
                case 2:
                    $errors[] .= "The file exceeds the MAX_FILE_SIZE in HTML form";
                    break;
                 
                case 3:
                    $errors[] .= "The was partially uploaded";
                    break;
                
                case 4:
                    $errors[] .= "NO file was uploaded";
                    break;
    
                case 6:
                    $errors[] .= "No temporary folder was available";
                    break;
    
                case 7:
                    $errors[] .= "Unable to write to the disk";
                    break;
    
                case 8:
                    $errors[] .= "File upload stopped";
                    break;
                
                default:
                    $errors[] .= "a system error has occured.";
                    break;
            } // END of switch
    
            $errors[] .= "</strong></p>";
        } // END of error IF
        
        // Xóa file t?n t?i trong temp folder
        if(isset($_FILES['image']['tmp_name']) && is_file($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])) {
            unlink($_FILES['image']['tmp_name']);
        }
        
        if(empty($errors)) {
            $q = "update users set avatar = '{$renamed}' where user_id='{$_SESSION['user_id']}' limit 1";
            $r = mysqli_query($connection, $q);
            confirm_query($r, $q);
            if(mysqli_affected_rows($connection) == 1) {
                redirect_to('edit_profile.php');
            }
        }
        
        report_error($errors);
        if(!empty($message)) echo $message;
    }
?>