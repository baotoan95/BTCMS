<?php 
    $title = 'Author Page';
    include('includes/header.php');
    include('includes/mysqli_connection.php');
    include('includes/common_functions.php');
    include('includes/sidebar-a.php'); 
?>
<div id="content">
    <?php
        // Phân trang
        $display = 4;
        $start = (isset($_GET['s']) && validate_id($_GET['s'])) ? $_GET['s'] : 0;
        
        
    
        //===== End phân trang
        if($aid = validate_id($_GET['aid'])) {
            $q = "select p.page_id, p.page_name, p.content, DATE_FORMAT(p.post_on, '%b %d %y') as date,";
            $q .= " CONCAT_WS(' ', u.first_name, u.last_name) as author";
            $q .= " from pages as p inner join users as u using(user_id)";
            $q .= " where p.user_id = {$aid} order by date asc limit {$start}, {$display}";
            $r = mysqli_query($connection, $q);
            if(mysqli_num_rows($r) > 0) {
                while($post = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                    echo "
                        <div class='post'>
                            <h2><a href='single.php?pid={$post['page_id']}'>{$post['page_name']}</a></h2>
                            <p>" . the_excerpt($post['content']) . "...<a href='single.php?pid={$post['page_id']}'>Read more</a></p>
                            <p class='meta'><strong>Posted by: </strong>{$post['author']} <strong>On: </strong>{$post['date']}</p>
                        </div>
                    ";
                }
                
                echo pagination($aid);
            } else {
                echo "<p class='warning'>The author is not avaliable or die</p>";
            }
        } else {
            redirect_to();
        }
    ?>
</div><!--end content-->
<?php
    include('includes/sidebar-b.php');
    include('includes/footer.php'); 
?>