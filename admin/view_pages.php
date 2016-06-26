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
			<th><a href="view_pages.php?sort=page">Pages</a></th>
			<th><a href="view_pages.php?sort=on">Posted on</th>
			<th><a href="view_pages.php?sort=by">Posted by</th>
            <th>Content</th>
            <th>Edit</th>
            <th>Delete</th>
		</tr>
	</thead>
	<tbody>
    <?php
        // Order by
        if(isset($_GET['sort'])) {
            switch($_GET['sort']) {
                case 'page': $order_by = 'page_name'; break;
                case 'on': $order_by = 'post_on'; break;
                case 'by': $order_by = 'name'; break;
                default: $order_by = 'post_on';
            }
        } else {
            $order_by = "post_on";
        }
    
        $q = "select p.page_id, p.page_name, DATE_FORMAT(p.post_on, '%b %d %Y') as post_on, p.content, CONCAT_WS(' ', first_name, last_name) ";
        $q .= "as name from pages as p inner join users as u using(user_id) order by {$order_by} asc";
        $r = mysqli_query($connection, $q);
        confirm_query($r, $q);
        if(mysqli_num_rows($r) > 0) {
        while($page = mysqli_fetch_assoc($r)) {
            echo
            "<tr>
                <td>{$page['page_name']}</td>
                <td>{$page['post_on']}</td>
                <td>{$page['name']}</td>
                <td>" . the_excerpt($page['content']) . "</td>
                <td><a class='edit' href='edit_page.php?pid={$page['page_id']}'>Edit</a></td>
                <td><a class='delete' href='delete_page.php?pid={$page['page_id']}&pname={$page['page_name']}'>Delete</a></td>
            </tr>";
        }
        } else {
            $messages = "<p class='warning'>Please a category first</p>";
        }
    ?>
        
	</tbody>
</table>
</div><!--end content-->

<?php include('../includes/footer.php'); ?>