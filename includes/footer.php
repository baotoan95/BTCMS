<div id="footer">
    <ul class="footer-links">
        <?PHP
            if(isset($_SESSION['user_level'])) {
                switch($_SESSION['user_level']) {
                    case 0: echo "
                        <li><a href='edit_profile.php'>User Profile</a></li>
                		<li><a href='change_password.php'>Change Password</a></li>
                		<li><a href='#'>Person Message</a></li>
                    ";
                    break;
                    case 2: echo "
                        <li><a href='edit_profile.php'>User Profile</a></li>
                		<li><a href='change_password.php'>Change Password</a></li>
                		<li><a href='#'>Person Message</a></li>
                        <li><a href='admin/admin.php'>Admin CP</a></li>
                    ";
                    break;
                    default: echo "
                        <li><a href='login.php'>Login</a></li>
                		<li><a href='registry.php'>Registry</a></li>
                    ";
                }
            } else {
                echo "
                        <li><a href='login.php'>Login</a></li>
                		<li><a href='registry.php'>Registry</a></li>
                    ";
            }
        ?>
		
    </ul>
</div><!--end footer-->
</div> <!-- end content-container-->
</div> <!--end container-->
</body>
</html>