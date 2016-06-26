<?PHP
    // Xác d?nh d?a ch? tuy?n d?i
    define('BASE_URL', 'http://localhost/btcms/');
    
    function report_error($mgs) {
        if(isset($mgs)) {
            foreach ($mgs as $m) {
                echo $m;
            }
        }
    }

    // Comfirm query
    function confirm_query($result, $query) {
        global $connection;
        if(!$result) {
            die('Query ' . $query . ' error: ' . mysqli_error($connection));
        }
    }
    
    function is_admin() {
        return isset($_SESSION['user_id']) && $_SESSION['user_level'] == 2;
    }
    
    function admin_access() {
        if(!is_admin()) {
            redirect_to();
        }
    }
    
    function is_logged_in() {
        if(!isset($_SESSION['user_id'])) {
            redirect_to('login.php');
        }
    }
    
    function redirect_to($page = 'index.php') {
        header("Location: " . BASE_URL . $page);
        exit();
    }
    
    // C?t chu?i d? t?o readmore
    function the_excerpt($text) {
        $text = htmlentities($text);
        if(strlen($text) > 200) {
            $cutStr = substr($text, 0, 200);
            return substr($cutStr, 0, strrpos($cutStr, ' '));
        }
        return $text;
    }
    
    function validate_id($id) {
        if(isset($id) && filter_var($id, FILTER_VALIDATE_INT, array("min-range" => 1))) {
            return $id;
        } else {
            return NULL;
        }
    }
    
    function get_page_by_id($id) {
        global $connection;
        $q = "select p.page_name, p.page_id, p.content, DATE_FORMAT(p.post_on, '%b %d %Y') as date,";
        $q .= " CONCAT(' ', u.first_name, u.last_name) as name, u.user_id, count(c.page_id) as count from pages as p";
        $q .= " inner join users as u using(user_id) left join comments as c using(page_id) where p.page_id={$id}";
        $result = mysqli_query($connection, $q);
        confirm_query($result, $q);
        return $result;
    }
    
    function format_content($text) {
        $sanitized = htmlentities($text);
        return str_replace(array('\r\n', '\n'), array('<p>', '</p>'), $sanitized);
    }
    
    function captcha() {
        $qna = array(
            1 => array('question' => 'One plus one', 'answer' => 2),
            2 => array('question' => 'Four plus two', 'answer' => 6),
            3 => array('question' => 'Five plus one', 'answer' => 6),
            4 => array('question' => 'Seven plus Eight', 'answer' => 15)
        );
        $key = array_rand($qna);
        $_SESSION['captcha'] = $qna[$key];
        return $qna[$key]['question'];
    }
    
    function pagination($aid) {
        global $connection;
        global $start;
        global $display;
        
        if(isset($_GET['p']) && validate_id($_GET['p'])) {
            $page = $_GET['p'];
        } else {
            $q = "select count(p.page_id) as count from pages as p";
            $r = mysqli_query($connection, $q);
            confirm_query($r, $q);
            if(mysqli_num_rows($r) > 0) {
                list($numOfRecords) = mysqli_fetch_array($r, MYSQL_NUM);
                $page = $numOfRecords;
                
                if($numOfRecords > $display) {
                    $page = ceil($numOfRecords / $display);
                } else {
                    $page = 1;
                }
            }
        }
        $output = "<ul class='pagination'>";
                if($page > 1) {
                    $current_page = ($start / $display) + 1;
                    // N?u không ? trang 1 thì hi?n th? prevous
                    if($current_page > 1) {
                        $output .= "<li><a href='author.php?aid={$aid}&s=".($start - $display)."&p={$page}'>Prevous</a></li>";
                    }
                    // Hi?n th? nh?ng phân s? còn l?i c?a trang
                    for($i = 1; $i <= $page; $i++) {
                        if($i != $current_page) {
                            $output .= "<li><a href='author.php?aid={$aid}&s=".($display * ($i - 1))."&p={$page}'>{$i}</a></li>";
                        } else {
                            $output .= "<li class='current'>{$i}</li>";
                        }
                    }
                    // Hi?n th? nút next
                    if($current_page != $page) {
                        $output .= "<li><a href='author.php?aid={$aid}&s=".($start + $display)."&p={$page}'>Next</a></li>";
                    }
                }
                $output .= "</ul>";
        return $output;
    }
    
    function clean_email($value) {
        $suspects = array('to:', 'bcc:', 'cc:', 'content-type:', 'mime-version:', 'multipart-mixed:', 'content-transfer-encoding:');
        foreach($suspects as $s) {
            if(strpos($value, $s) !== false) {
                return '';
            }
            $value = str_replace(array('\n', '\r', '%0a', '%0d'), '', $value);
            return trim($value);
        }
    }
    
    function view_counter($page_id) {
        global $connection;
        $ip = $_SERVER['REMOTE_ADDR'];
        $q = "select numb_views, user_ip from page_views where page_id={$page_id}";
        $r = mysqli_query($connection, $q);
        if(mysqli_num_rows($r) > 0) {
            list($numb_views, $user_ip) = mysqli_fetch_array($r, MYSQLI_NUM);
            if($user_ip != $ip) {
                $q = "update page_views set numb_views = ({$numb_views} + 1) where page_id={$page_id}";
                $r = mysqli_query($connection, $q);
                confirm_query($r, $q);
            }
        } else {
            $q = "insert into page_views (page_id, numb_views, user_ip) values({$page_id}, 1, '{$ip}')";
            $r = mysqli_query($connection, $q);
            confirm_query($r, $q);
            $numb_views = 1;
        }
        return $numb_views;
    }
    
    function check_db_conn() {
        if(mysqli_connect_errno()) {
            echo "Connection failed: ". mysqli_connect_error();
            exit();
        }
    }
    
    function fetch_user($user_id) {
        global $connection;
        $q = "select * from users where user_id = {$user_id}";
        $r = mysqli_query($connection, $q); confirm_query($r, $q);

        if(mysqli_num_rows($r) > 0) {
            // Neu co ket qua tra ve
            return $result_set = mysqli_fetch_array($r, MYSQLI_ASSOC);
        } else {
            // Neu ko co ket qua tra ve
            return FALSE;
        }
    } // END fetch_user
    
    function fetch_users($order) {
        global $connection;
        $q = "select * from users order by {$order} ASC";
        $r = mysqli_query($connection, $q);
        if(mysqli_num_rows($r) > 1) {
            $users = array();
            while($user = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                $users[] = $user;
            }
        } else {
            return FALSE;
        }
        return $users;
    }
    
    function sort_table_users($order) {
        switch($order) {
            case 'fn':
            return 'first_name';
            case 'ln':
            return 'last_name';
            case 'e':
            return 'email';
            case 'ul':
            return 'user_level';
            default:
            return 'first_name';
        }
    }
?>