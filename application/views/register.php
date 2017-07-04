<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Register</title>
	<link rel="stylesheet" href="<?php echo $base_url;?>/static/css/login.css" type="text/css" />
	<script src="<?php echo $base_url; ?>/static/js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function () {
		$('#submit').click(function () {
			var username = $('#username').val();
			var username = $('#username').val();
			var username = $('#username').val();
		})
	})
	
	function cancel() {
		window.location.href = "http://127.0.0.1";
	}
	</script>
</head>
<body>
<div class="container">
	<section id="content">
		<form action="post" action="">
			<h1>Register</h1>
			<div>
				<input type="text" placeholder="Username" required="" id="username" />
			</div>
			<div>
				<input type="password" placeholder="Password" required="" id="password" />
			</div>
			<div>
				<input type="password" placeholder="Password" required="" id="password" />
			</div>
			<div>
				<input type="submit" id="submit" value="Register" />
			</div>
			<div>
				<input type="button" value="Cancel" onclick="cancel()" />
			</div>
		</form>
	</section>
</div>
</body>
</html>