<?PHP session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8' />
	<title><?PHP echo isset($title) ? $title : 'izCMS'; ?></title>
	<link rel='stylesheet' href='css/style.css' />
    <script src="http://localhost/btcms/js/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="http://localhost/btcms/js/tinymce/tiny_mce.js" ></script >
        <script type="text/javascript" >
        tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins : "emotions,spellchecker,advhr,insertdatetime,preview", 
                
        // Theme options - button# indicated the row# only
        theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect",
        theme_advanced_buttons2 : "cut,copy,paste,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,|,code,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "insertdate,inserttime,|,spellchecker,advhr,,removeformat,|,sub,sup,|,charmap,emotions",      
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true
        });
    </script >
</head>

<body>
	<div id="container">
	<div id="header">
		<h1><a href="http://localhost/btcms/index.php">izCMS</a></h1>
        <p class="slogan">The iz Content Management System</p>
	</div>
	<div id="navigation">
		<ul>
	        <li><a href='http://localhost/btcms/index.php'>Home</a></li>
			<li><a href='#'>About</a></li>
			<li><a href='#'>Services</a></li>
			<li><a href='contact.php'>Contact us</a></li>
		</ul>
        
        <p class="greeting">
        Xin chào, 
                <?PHP 
                        if(isset($_SESSION['user_id'])) {
                            echo $_SESSION['first_name'] . " " . $_SESSION['last_name'];
                            echo "
                                | <a href='logout.php'>Logout</a>
                            ";
                        } else {
                            echo "bạn hiền!";
                            echo "
                                <a href='login.php'>Login</a> | <a href='register.php'>Registry</a></p>
                            ";
                        } 
                ?> 
	</div><!-- end navigation-->