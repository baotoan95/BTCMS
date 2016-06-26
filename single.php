<?PHP 
    $title = "Single";
    include("includes/common_functions.php");
    include("includes/mysqli_connection.php");
    
    if($pid = validate_id($_GET['pid'])) {
            $r = get_page_by_id($pid);
            $numb_views = view_counter($pid);
            $posts = NULL;
            if(mysqli_num_rows($r) > 0) {
                $page = mysqli_fetch_array($r, MYSQLI_ASSOC);
                $title = $page['page_name'];
                $post = array('page_name' => $page['page_name'],
                                 'content' => $page['content'],
                                 'author' => $page['name'],
                                 'post-on' => $page['date'],
                                 'user_id' => $page['user_id'],
                                 'comments' => $page['count']);
            } else {
                echo "First of all, write a page";
            }
        } else {
            redirect_to();
        }
    
    include("includes/header.php");
    include("includes/sidebar-a.php"); 
?>

<div id="content">
    <?PHP
        //foreach($posts as $post) { // Ý d? c?a vi?c s? d?ng 1 m?ng các post là d? tránh n?u post không có 
                //print_r($posts);
                echo "
                    <div class='post'>
                        <h2>{$post['page_name']}</h2>
                        <p class='comments'><a href='single.php?pid={$pid}#disscuss'>{$post['comments']}</a></p>
                        <p>" . format_content($post['content']) . "</p>
                        <p class='meta'><strong>Posted by: </strong>
                            <a href='author.php?aid={$post['user_id']}'>{$post['author']} </a>
                            <strong>On: </strong>{$post['post-on']} | Views: {$numb_views}
                        </p>
                    </div>
                ";
       //}
       include("includes/comment_form.php");
    ?>
</div><!--end content-->
<?PHP
    include("includes/sidebar-b.php");
    include("includes/footer.php"); 
?>