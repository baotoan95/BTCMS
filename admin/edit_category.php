<?PHP 
    include("../includes/common_functions.php"); 
    include("../includes/header.php"); 
    include("../includes/mysqli_connection.php"); 
    include("../includes/sidebar-admin.php");
    admin_access();
?>
    <?PHP
        // Ki?m tra cate id ngu?i dùng nh?p vào có dúng nhu mình mu?n không
        if(isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array("min-range" => 1))) {
            $cid = $_GET['cid'];
        } else {
            redirect_to('admin/admin.php');
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai, xu ly form
            $errors = array();
            if(empty($_POST['category'])) {
                $errors[] = "category";
            } else {
                // Chuy?n mã sql qua d?ng string d? tránh sql injection hacking
                // Lo?i b? các tag nhu html <script>  <strong>
                $cate_name = mysqli_real_escape_string($connection, strip_tags($_POST['category']));
            }
            // filter (d?i tu?ng mu?n ki?m tra, check có ph?i ki?u int hay ko, 1 là nh? nh?t)
            if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array("min-range" => 1))) {
                $cate_position = $_POST['position'];
            } else {
                $errors[] = "position";
            }
            
            if(empty($errors)) {
                $query = "update categories set cate_name = '{$cate_name}', position = {$cate_position} where cate_id = {$cid} limit 1";
                $rs = mysqli_query($connection, $query);
                confirm_query($rs, $query);
                if(mysqli_affected_rows($connection) == 1) {
                    $messages = "<p class='success'>A category was edited</p>";
                } else {
                    $messages = "<p class='warning'>Edit category error</p>";
                }
            } else {
                $messages = "<p class='warning'>Please fill all required fields</p>";
            }
        }
    ?>
<div id="content">
    <?PHP
        $q = "select cate_name, position from categories where cate_id = {$cid}";
        $r = mysqli_query($connection, $q);
        if(mysqli_num_rows($r) == 1) {
            list($cateName, $catePosition) = mysqli_fetch_array($r, MYSQLI_NUM);
        } else {
            $messages = "<p class='warning'>The category does not exist</p>";
        }
        
    ?>
    
    <h2>Edit category: <?PHP if(isset($cateName)) echo $cateName ?></h2>
    <?PHP
        if(!empty($messages)) echo $messages;
    ?>
	<form id="add_cat" action="" method="post">
        <fieldset>
        	<legend>Edit category</legend>
                <div>
                    <label for="category">Category Name: <span class="required">*</span>
                        <?PHP
                            if(isset($errors) && in_array('category', $errors)) {
                                echo "<p class='warning'>Please fill the category name</p>";
                            }
                        ?>
                    </label>
                    <input type="text" name="category" id="category" value="<?PHP if(isset($cateName)) echo $cateName ?>" size="20" maxlength="150" tabindex="1" />
                </div>
                <div>
                    <label for="position">Position: <span class="required">*</span>
                        <?PHP
                            if(isset($errors) && in_array('position', $errors)) {
                                echo "<p class='warning'>Please choose a position</p>";
                            }
                        ?>
                    </label>
                    <select name="position" tabindex='2'>
                        <?PHP
                            $q = "select count(cate_id) as count from categories";
                            $r = mysqli_query($connection, $q);
                            confirm_query($r, $q);
                            if(mysqli_num_rows($r) == 1) { // S? rows tr? v?
                                list($num) = mysqli_fetch_array($r, MYSQLI_NUM);
                                for($i = 1; $i <= $num + 1; $i++) {
                                    echo "<option value='{$i}'";
                                        if(isset($catePosition) && $catePosition == $i) {echo "selected='selected'";}
                                    echo ">" . $i . "</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
        </fieldset>
        <p><input type="submit" name="submit" value="Edit Category" /></p>
    </form>
</div><!--end content-->
<?php include('../includes/footer.php'); ?>