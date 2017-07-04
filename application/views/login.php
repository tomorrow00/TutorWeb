<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>登录</title>
	<link rel="stylesheet" href="<?php echo $base_url;?>/static/css/login.css" type="text/css" />
	<script src="<?php echo $base_url; ?>/static/js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function () {
		$('#submit').click(function () {
			var username = $("#username").val();
			var password = $("#password").val();
			var usertype = $("input:radio[name='usertype']:checked").val();
			
			//判断用户名是否为空
			if(username == ""){
				$('#msg').html("用户名不能为空！");
				$('#username').focus();
				return false;
			}
			else {
				$('#msg').html("");
			}
			
			//判断密码是否为空
			if(password == ""){
				$('#msg').html("密码不能为空！");
				$('#password').focus();
				return false;
			}
			else {
				$('#msg').html("");
			}
			
			//判断是否选择用户类型
			if(usertype == null){
				$('#msg').html("请选择用户类型！");
				return false;
			}
			else {
				$('#msg').html("");
			}
			
			$.ajax({
				url:"<?php echo $base_url;?>/login/check",
				type:"POST",
				dataType:'json',
				data:{"usr":username, "pwd":password, "type":usertype},
				success:function (data) {
					if (data.flag == 0) {
						window.location.href="<?php echo $base_url;?>/";
						return true;
					}
					else {
						if (data.flag == 1) {
							$('#msg').html(data.msg);
							$('#username').focus();
							return false;
						}
						else {
							$('#msg').html(data.msg);
							$('#password').focus();
							return false;
						}
					}
				}
			});
			return false;
		})
	})
	
	/*function checklogin() {
		if($("#username").val() == ""){
			//alert("用户名不能为空！");
			$('#msg').html("用户名不能为空！");
			$("#username").focus();
			return false;
		}
		else {
			$('#msg').html("");
		}
			
		if($("#password").val() == ""){
			//alert("密码不能为空！");
			$('#msg').html("密码不能为空！");
			$("#password").focus();
			return false;
		}
		else {
			$('#msg').html("");
		}
			
		var username = $("#username").val();
		var password = $("#password").val();
			
		$('.text').attr('disabled', true);
		$('.password').attr('disabled', true);
		$('.loading').show();
			
		$.ajax({
			url:"http://127.0.0.1/index.php/login/check",
			type:"POST",
			data:{"usr":username, "pwd":password},
			success:function (data) {
				if (data == "1") {
					//alert("log in successfully");
					$('#msg').html("success");
					return true;
				}
				else {
					//alert("log in failed");
					$('#msg').html("fail");
					return false;
				}
				//alert(data);
			}
		});
	}*/
	</script>
</head>
<body>
<div class="container" id="register">
	<section id="content">
		<form action="">
			<h1>登录</h1>
			<div>
				<input type="text" placeholder="用户名" id="username" autocomplete="off" />
			</div>
			<div>
				<input type="password" placeholder="密码" id="password" />
			</div>
			<div>
				<input name="usertype" type="radio" id="manager" value="manager" />管理员
				<input name="usertype" type="radio" id="user" value="user" />用户
			</div>
			<div>
				<input type="submit" id="submit" value="登录" />
				<a href="#">忘记密码？</a>
				<a href="<?php echo $base_url;?>/register">注册</a>
			</div>
		</form>
		<span id="msg"></span>
	</section>
</div>
<div id="register"></div>
</body>
</html>