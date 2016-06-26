<?PHP
    include("includes/common_functions.php");
    include("includes/header.php");
    include("includes/mysqli_connection.php");
    include("includes/sidebar-a.php"); 
?>

<div id="content">
    <?PHP
        if(isset($_GET['cid']) && $cid = validate_id($_GET['cid'])) {
            $q = "select p.page_name, p.page_id, p.content, DATE_FORMAT(p.post_on, '%b %d %Y') as date,";
            $q .= " CONCAT(' ', u.first_name, u.last_name) as name, u.user_id, count(c.comment_id) as count from pages as p";
            $q .= " inner join users as u using(user_id) left join comments as c using(page_id) where p.cate_id = {$cid} group by p.page_id order by date limit 0, 10";
            $r = mysqli_query($connection, $q);
            confirm_query($r, $q);
            if(mysqli_num_rows($r) > 0) {
                while($page = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                    echo "
                        <div class='post'>
                            <h2><a href='single.php?pid={$page['page_id']}'>{$page['page_name']}</a></h2>
                            <p class='comments'><a href='single.php?pid={$page['page_id']}#disscuss'>{$page['count']}</a></p>
                            <p>" . the_excerpt($page['content']) . "...<a href='single.php?pid={$page['page_id']}'>Read more</a></p>
                            <p class='meta'><strong>Posted by: </strong><a href='author.php?aid={$page['user_id']}'>{$page['name']}</a> <strong>On: </strong>{$page['date']}</p>
                        </div>
                    ";
                }
            } else {
                $messages = "First of all, write a page before";
            }
        } else {
    ?>
    <h2>Welcome To izCMS</h2>
	<div>
		<p>
			Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
		</p>
                
        <p>
			Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
		</p>

		<p>
			Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
		</p>
		
		<p>
			Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
		</p>
		
		<p>
			Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
		</p>
		
		<p>
			Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus
		</p>
                
		</div>
    <?PHP
        }
        if(isset($messages) && !empty($messages)) echo $messages;
    ?>
</div><!--end content-->
<?PHP include("includes/sidebar-b.php"); ?>
<?PHP include("includes/footer.php"); ?>