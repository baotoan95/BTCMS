<?PHP 
    include("../includes/common_functions.php"); 
    include("../includes/header.php"); 
    include("../includes/mysqli_connection.php"); 
    include("../includes/sidebar-admin.php");
    admin_access();
?>
<div id="content">
<h2>Manage Categories</h2>
<table>
	<thead>
		<tr>
			<th><a href="view_categories.php?sort=cat">Categories</a></th>
			<th><a href="view_categories.php?sort=pos">Position</th>
			<th><a href="view_categories.php?sort=by">Posted by</th>
            <th>Edit</th>
            <th>Delete</th>
		</tr>
	</thead>
	<tbody>
    <?php
        // Order by
        if(isset($_GET['sort'])) {
            switch($_GET['sort']) {
                case 'cat': $order_by = 'cate_id'; break;
                case 'pos': $order_by = 'position'; break;
                case 'by': $order_by = 'name'; break;
                default: $order_by = 'position';
            }
        } else {
            $order_by = "position";
        }
    
        $q = "select c.cate_id, c.cate_name, c.position, c.user_id, CONCAT_WS(' ', first_name, last_name) ";
        $q .= "as name from categories as c inner join users as u using(user_id) order by {$order_by} asc";
        
        $r = mysqli_query($connection, $q);
        confirm_query($r, $q);
        while($cate = mysqli_fetch_assoc($r)) {
            echo 
            "<tr>
                <td>{$cate['cate_name']}</td>
                <td>{$cate['position']}</td>
                <td>{$cate['name']}</td>
                <td><a class='edit' href='edit_category.php?cid={$cate['cate_id']}'>Edit</a></td>
                <td><a class='delete' href='delete_category.php?cid={$cate['cate_id']}&cname={$cate['cate_name']}'>Delete</a></td>
            </tr>";
        }
    ?>
        
	</tbody>
</table>
</div><!--end content-->

<?php include('../includes/footer.php'); ?>