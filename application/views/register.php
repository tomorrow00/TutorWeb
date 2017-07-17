<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>注册</title>
	<link rel="stylesheet" href="<?php echo $base_url;?>/static/css/login.css" type="text/css" />
	<script src="<?php echo $base_url; ?>/static/js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function () {
		$('#submit').click(function () {
			var username = $('#username').val();
			var password = $("input[name='pwd']").val();
			var verify_password = $("input[name='verify_pwd']").val();
			
			//判断用户名是否为空
			if(username == ""){
				$('#msg').html("请设置用户名！");
				$('#username').focus();
				return false;
			}
			else {
				$('#msg').html("");
			}
			
			//判断密码是否为空
			if(password == ""){
				$('#msg').html("请设置密码！");
				$("input[name='pwd']").focus();
				return false;
			}
			else {
				$('#msg').html("");
			}
			
			//判断确认密码是否为空
			if(verify_password == ""){
				$('#msg').html("请确认密码！");
				$("input[name='verify_pwd']").focus();
				return false;
			}
			else {
				$('#msg').html("");
			}
			
			if (password == verify_password){
				$.ajax({
					url:"<?php echo $base_url;?>/login/registerin",
					type:"POST",
					dataType:'json',
					data:{"usr":username, "pwd":password},
					success:function (data) {
						if (data == 1) {
							alert("注册成功!");
							location.href = "<?php echo $base_url; ?>/";
							return true;
						}
						else {
							$('#msg').html("注册失败！该用户已存在！");
						}
					}
				});
				return false;
			}
			else{
				$('#msg').html("两次密码不一致！");
				$("input[name='verify_pwd']").focus();
				return false;
			}
		})
		
		$('#cancel').click(function (){
			window.history.go(-1);
			location.reload();
		})
	})
	</script>
</head>
<body>
<div class="container">
	<section id="content">
		<form action="">
			<h1>注册</h1>
			<span id="msg" style="color:red"></span>
			<div>
				<input type="text" placeholder="设置用户名" required="" id="username" />
			</div>
			<div>
				<input type="password" placeholder="设置密码" required="" name="pwd" id="password" />
			</div>
			<div>
				<input type="password" placeholder="确认密码" required="" name="verify_pwd" id="password" />
			</div>
			<div>
				<input type="submit" id="submit" value="注册" />
			</div>
			<div>
				<input type="button" id="cancel" value="取消" />
			</div>
		</form>
	</section>
</div>
</body>
</html>
