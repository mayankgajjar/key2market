<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,800,400italic' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style>
		*{ margin:0; padding:0;}
		.page-404{ background:url(img/404-bg.jpg) no-repeat center /cover; 50%; width:100%; height:789px; font-family: 'Open Sans', sans-serif; position:relative;}
		.round-404{ background:blue; width:420px; height:420px; display:block; margin:-210px 0 0 -210px; position:absolute; top:50%; left:50%;
		-webkit-border-radius:100%; -moz-border-radius:100%; border-radius:100%; text-align:center; }
		.round-404 h3{ color:#fff; font-size:160px; line-height:100%; font-family: 'Open Sans', sans-serif; margin:80px 0 0; padding:0; display:inline-block;}
		.round-404 p{ color:#e4d6d7; font-size:17px; font-style:italic; font-family: 'Open Sans', sans-serif; margin:0; padding:0;}
		.round-404 p a{ color:#fff; font-weight:700; font-style:normal; font-family: 'Open Sans', sans-serif; margin:0; padding:0; text-decoration:none;}
		
		@media(max-width:767px){
			.page-404{ height:640px;}
			.round-404{ width:280px; height:280px; margin:-140px 0 0 -140px; }
			.round-404 h3{ font-size:100px; margin:60px 0 0;}
			.round-404 p{ font-size:14px;}
		}
		@media(max-width:320px){
			.page-404{ height:480px;}
		}
	</style>
  </head>
  <body>
    <div class="page-404">
		<div class="round-404">
			<h3>404</h3>
			<p>The page you’re looking for went fishing! <br/> Go back to <a href="http://app.key2market.com">app.key2market.com</a></p>
		</div>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
		var wind = $(window).height();
		$('.page-404').css('height',wind);
	</script>
  </body>
</html>