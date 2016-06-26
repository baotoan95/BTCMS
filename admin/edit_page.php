<?PHP 
    include("../includes/common_functions.php"); 
    include("../includes/header.php"); 
    include("../includes/mysqli_connection.php"); 
    include("../includes/sidebar-admin.php");
    admin_access();
?>
    <?php
        if(isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array("min-range" => 1))) {
            $pid = $_GET['pid'];
        } else {
            redirect_to('admin/view_pages.php');
        }
    
        if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri tri ton tai, xu ly form.
            $errors = array();
            if(empty($_POST['page_name'])) {
                $errors[] = 'page_name';
            } else {
                $page_name = mysqli_escape_string($connection, strip_tags($_POST['page_name']));
            }
            if(isset($_POST['category']) && filter_var($_POST['category'], FILTER_VALIDATE_INT, array("min-range" => 1))) {
                $category = $_POST['category'];
            } else {
                $errors[] = 'category';
            }
            if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array("min-range" => 1))) {
                $position = $_POST['position'];
            } else {
                $errors[] = 'position';
            }
            if(empty($_POST['content'])) {
                $errors[] = 'content';
            } else {
                $content = mysqli_escape_string($connection, $_POST['content']);
            }
            
            if(empty($errors)) {
                $q = "update pages set cate_id = {$category}, page_name = '{$page_name}', content = '{$content}', position = {$position} where page_id={$pid}"; 
                $r = mysqli_query($connection, $q);
                confirm_query($r, $q);
                if(mysqli_affected_rows($connection) == 1) {
                    $messages = "<p class='success'>The page was edited</p>";
                } else {
                    $messages = "<p class='warning'>Error: " . mysqli_error($connection) . "</p>";
                }
            } else {
                echo "<p class='warning'>Please fill all required fields</p>";
            }
        } // END main IF submit condition
    ?>
    <div id="content">
    <?PHP
        $query = "select * from pages where page_id = {$pid}";
        $rs = mysqli_query($connection, $query);
        $page = mysqli_fetch_array($rs, MYSQLI_ASSOC);
    ?>
    <h2>Edit page: <?PHP if(isset($page['page_name'])) echo $page['page_name'];  ?></h2>
    <?php if(!empty($messages)) echo $messages; ?>
        <form id="login" action="" method="post">
            <fieldset>
            	<legend>Edit page</legend>
                    <div>
                        <label for="page">Page Name: <span class="required">*</span>
                            <?PHP
                                if(isset($errors) && in_array('page_name', $errors)) { echo "<p class='warning'>Please enter page name</p>";}
                            ?>
                        </label>
                        <input type="text" name="page_name" id="page_name" value="<?php if(isset($page['page_name'])) echo $page['page_name']; ?>" size="20" maxlength="80" tabindex="1" />
                    </div>
                    
                    <div>
                        <label for="category">All categories: <span class="required">*</span>
                            <?PHP
                                if(isset($errors) && in_array('category', $errors)) { echo "<p class='warning'>Please select a category</p>";}
                            ?>
                        </label>
                        <select name="category">
                            <option>Select Category</option>
                            <?PHP
                                
                                $q = "select * from categories order by position asc";
                                $r = mysqli_query($connection, $q);
                                confirm_query($r, $q);
                                if(mysqli_num_rows($r) > 1) { // S? rows tr? v?
                                    while($cate = mysqli_fetch_array($r, MYSQL_ASSOC)) {
                                        echo "<option value='{$cate['cate_id']}'";
                                            if(isset($page['cate_id']) && ($page['cate_id'] == $cate['cate_id'])) {echo " selected='selected'";}
                                        echo ">" . $cate['cate_name'] . "</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label for="position">Position: <span class="required">*</span>
                            <?PHP
                                if(isset($errors) && in_array('position', $errors)) { echo "<p class='warning'>Please choose a position</p>";}
                            ?>
                        </label>
                        <select name="position">
                            <option>Select Position</option>
                            <?PHP
                                $q = "select count(page_id) as count from pages";
                                $r = mysqli_query($connection, $q) or confirm_query($r, $q);
                                if(mysqli_num_rows($r) == 1) { // S? rows tr? v?
                                    list($num) = mysqli_fetch_array($r, MYSQLI_NUM);
                                    for($i = 1; $i <= $num + 1; $i++) {
                                        echo "<option value='{$i}' ";
                                            if(isset($page['position']) && $page['position'] == $i) {echo "selected='selected'";}
                                        echo ">" . $i . "</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>                
                    <div>
                        <label for="page-content">Page Content: <span class="required">*</span>
                            <?PHP
                                if(isset($errors) && in_array('content', $errors)) { echo "<p class='warning'>Please enter the content</p>";}
                            ?>
                        </label>
                        <textarea name="content" cols="50" rows="20"><?php if(isset($page['content'])) echo htmlentities($page['content'], ENT_COMPAT, 'UTF-8'); ?></textarea>
                    </div>
            </fieldset>
            <p><input type="submit" name="submit" value="Update Page" /></p>
        </form>
        
</div><!--end content-->
<?php include('../includes/footer.php'); ?>