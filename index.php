<?php

// Facebook Code
require ('utils.php');
require ('src/facebook/facebook.php');

$facebook = new Facebook(array(
  'appId'  => getenv('FACEBOOK_APP_ID'),
  'secret' => getenv('FACEBOOK_SECRET'),
  'sharedSession' => true,
  'trustForwarded' => true,
));

// Fetch the basic info of the app that they are using
$app_info = $facebook->api('/'. getenv('FACEBOOK_APP_ID') );
$app_name = idx($app_info, 'name', '');

$user_id = $facebook->getUser();

if ($user_id) {
  try {
    // Fetch the viewer's basic information
    $basic = $facebook->api('/me');

  } catch (FacebookApiException $e) {

    // If the call fails we check if we still have a user. The user will be
    // cleared if the error is because of an invalid accesstoken
    if (!$facebook->getUser()) {
		header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
      exit();
    }
  }
}

?>

<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />
	
	<title>Redistributive Library System</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script src="src/bootstrap/js/bootstrap.min.js"></script>
	<script src="js/init.js"></script>

	<link href="src/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="css/style.css" rel="stylesheet" />
</head>
<body>
	<div class="container">
		<div id="fb-root"></div>
	    <script type="text/javascript">
	      window.fbAsyncInit = function() {
	        FB.init({
	          appId      : "<?php echo getenv('FACEBOOK_APP_ID'); ?>", // App ID
	          channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/channel.html', // Channel File
	          status     : true, // check login status
	          cookie     : true, // enable cookies to allow the server to access the session
	          xfbml      : true // parse XFBML
	        });

	        // Listen to the auth.login which will be called when the user logs in
	        // using the Login button
	        FB.Event.subscribe('auth.login', function(response) {
	          // We want to reload the page now so PHP can read the cookie that the
	          // Javascript SDK sat. But we don't want to use
	          // window.location.reload() because if this is in a canvas there was a
	          // post made to this page and a reload will trigger a message to the
	          // user asking if they want to send data again.
	          window.location = window.location;
	        });

	        FB.Canvas.setAutoGrow();
	      };

	      // Load the SDK Asynchronously
	      (function(d, s, id) {
	        var js, fjs = d.getElementsByTagName(s)[0];
	        if (d.getElementById(id)) return;
	        js = d.createElement(s); js.id = id;
	        js.src = "//connect.facebook.net/en_US/all.js";
	        fjs.parentNode.insertBefore(js, fjs);
	      }(document, 'script', 'facebook-jssdk'));
	    </script>
		
		
	
	
		<div class="header">
			<h2>(re)Distributive Library System</h2>
	
			  <?php if (isset($basic)) { ?>
		      <p id="picture" style="background-image: url(https://graph.facebook.com/<?php echo he($user_id); ?>/picture?type=normal)"></p>

		      <div class="fb-toolbar">
		        <h1>Welcome, <strong><?php echo he(idx($basic, 'name')); ?></strong><small> to 
		          <a href="<?php echo he(idx($app_info, 'link'));?>" target="_top"><?php echo he($app_name); ?></a></small></h1>

		 	</div>
			  <?php } else { ?>
		      <div>
		        <h1>Welcome</h1>
		        <div class="fb-login-button" ></div>
		      </div>
		      <?php } ?>
		
	    
	</div>
		<div class="navigation">
			<div class="navbar navbar-inverse">
			  <div class="navbar-inner">
			    <a class="brand" href="#">Title</a>
			    <ul class="nav">
			      <li class="active"><a href="#">My Library</a></li>
			      <li><a href="#">Lends</a></li>
			      <li><a href="#">Notifications<span class="badge badge-important">6</span></a></li>
			    </ul>
			  </div>
			</div>
			
		
		</div>
	
		<div id="content">
			<!-- <div class="alert alert-success">
				<strong>Well done!</strong>You successfully read this important alert message.
			</div>
			<div class="alert alert-info">
			  <strong>Heads up!</strong>This alert needs your attention, but it's not super important.
			 
			</div> -->
			<form method="post" action="#" class="navbar-search pull-left">
				<input type="hidden" id="selected" value=""/>
				<input type="text" name="q" placeholder="search for books"  class="search-query" />
				<button type="submit" class="btn">Add book to library</button>  
			</form>	
			
			<div class="clear"></div>
			
			<div class="added-book">
			
			</div>
		</div>
		<div class="footer">
			<ul>
				<li><a href="">My Library</a></li>
				<li><a href="">Lends</a></li>
				<li><a href="">Notifications</a></li>
			</ul>
		</div>
		<div class="clear"></div>
	</div>	
</body>
</html>