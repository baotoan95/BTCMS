<?PHP 
    include("../includes/common_functions.php"); 
    include("../includes/header.php"); 
    include("../includes/mysqli_connection.php"); 
    include("../includes/sidebar-admin.php");
    admin_access();
?>
<div id="content">
    <h2>Welcome To CMS admin panel</h2>
	<div>
		<p>
			Hello admin
		</p>
	</div>
</div><!--end content-->
<?PHP include("../includes/footer.php"); ?>