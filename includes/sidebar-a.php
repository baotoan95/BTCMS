<div id="content-container">
    <div id="section-navigation">
        <h2>Site Navigation</h2>
		<ul class="navi">
            <?PHP
            
                // Xác d?nh link d? tô d?m link
                if(isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array("min-range" >= 1))) {
                    $cid = $_GET['cid'];
                } else {
                    $cid = NULL;
                }
                if(isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array("min-range" >= 1))) {
                    $pid = $_GET['pid'];
                } else {
                    $pid = NULL;
                }

                $q = "select cate_name, cate_id from categories order by position asc";
                $r = mysqli_query($connection, $q);
                confirm_query($r, $q);
                while($cate = mysqli_fetch_array($r, MYSQL_ASSOC)) {
                    echo "<li><a href='index.php?cid={$cate['cate_id']}'";
                        if($cate['cate_id'] == $cid) {
                            echo " class='selected'";
                        }
                    echo ">{$cate['cate_name']}</a>";
                    
                    $sql = "select page_name, page_id from pages where cate_id={$cate['cate_id']} order by position asc";
                    $rs = mysqli_query($connection, $sql);
                    confirm_query($rs, $sql);
                    if(mysqli_num_rows($rs) > 0) {
                        echo "<ul class='pages'>";
                        while($page = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
                            echo "<li><a href='single.php?pid={$page['page_id']}' ";
                                if($page['page_id'] == $pid) {
                                    echo "class='selected'";
                                }
                            echo ">{$page['page_name']}</a></li>";
                        }
                        echo "</ul>";
                    }
                    
                    echo "</li>";
                }
            ?>
			<li><a href="/cms/">Home</a></li>
		</ul>
</div><!--end section-navigation-->