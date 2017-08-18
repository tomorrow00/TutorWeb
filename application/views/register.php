<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>注册</title>
	
	<link rel="stylesheet" href="<?php echo $base_url; ?>/static/css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $base_url; ?>/static/css/login.css" type="text/css" />
	
	<script src="<?php echo $base_url; ?>/static/js/jquery-3.1.1.min.js"></script>
	<script src="<?php echo $base_url; ?>/static/js/bootstrap.min.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function () {
		$('#submit').click(function () {
			var email = $("input[name='email']").val();
			var username = $("input[name='username']").val();
			var password = $("input[name='pwd']").val();
			var verify_password = $("input[name='verify_pwd']").val();
			var email_pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
			
			//判断邮箱格式是否错误
			if(email != ""){
				if(email_pattern.test(email)){
					$('#msg').html("");
				}
				else{
					$('#msg').html("邮箱格式不正确！");
					$("input[name='email']").focus();
					return false;
				}
			}
			
			//判断用户名是否为空
			if(username == ""){
				$('#msg').html("请设置用户名！");
				$("input[name='username']").focus();
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
			
			//判断两次密码是否一致
			if (password == verify_password){
				$.ajax({
					url:"<?php echo $base_url;?>/login/registerin",
					type:"POST",
					dataType:'json',
					data:{"usr":username, "email":email, "pwd":password},
					success:function (data) {
						if (data.flag == 0) {
							$('#msg').html("注册失败！该用户已存在！");
							return false;
						}
						else {
							if (data.email == 1) {
								$('#modal-container').modal();
								$('#tips').html("");
								return true;
							}
							else {
								$('#msg').html("注册成功，3秒后跳转到首页。");
								var i = 2;
								setInterval(function(){
									$('#msg').html("注册成功，" + i + "秒后跳转到首页。");
									i --;
									if(i == -1) {
										location.href = "<?php echo $base_url; ?>/";
									}
								}, 1000);
								return true;
							}
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
		
		$('#check_code').click(function (){
			var code = $('#code').val();
			var email = $("input[name='email']").val();
			var username = $("input[name='username']").val();
			var password = $("input[name='pwd']").val();
			
			$.ajax({
				url:"<?php echo $base_url;?>/login/check_code",
				type:"POST",
				dataType:'json',
				data:{"usr":username, "email":email, "pwd":password, "code":code},
				success:function (data) {
					if (data.flag == 1) {
						$('#check').slideDown(200);
						$('#tips').html("注册成功，3秒后跳转到首页。");
						var i = 2;
						setInterval(function(){
							$('#tips').html("注册成功，" + i + "秒后跳转到首页。");
							i --;
							if(i == -1) {
								location.href = "<?php echo $base_url; ?>/";
							}
						}, 1000);
						return true;
					}
					else if (data.flag == 2) {
						document.getElementById('tips').innerHTML = "验证码输入错误，请重新输入。"
						return false;
					}
					else if (data.flag == 0) {
						document.getElementById('tips').innerHTML = "请输入验证码。"
						return false;
					}
					else if (data.flag == 3) {
						document.getElementById('tips').innerHTML = "验证码已过期，请重新验证。"
						return false;
					}
				}
			});
			
			return false;
		})
		
		$('#cancel').click(function (){
			window.history.go(-1);
			location.reload();
		})
	})
	</script>
</head>
<body>
	<div class="container" id="register">
		<section id="content">
			<form action="">
				<h1>注册</h1>
				<span id="msg" style="color:red"></span>
				
				<div>
					<input type="text" placeholder="设置邮箱" required="" name="email" id="username" />
					<span style="color:red">&nbsp;</span>
				</div>
				<div>
					<input type="text" placeholder="设置用户名" required="" name="username" id="username" />
					<span style="color:red">*</span>
				</div>
				<div>
					<input type="password" placeholder="设置密码" required="" name="pwd" id="password" />
					<span style="color:red">*</span>
				</div>
				<div>
					<input type="password" placeholder="确认密码" required="" name="verify_pwd" id="password" />
					<span style="color:red">*</span>
				</div>
				<div>
					<input type="submit" id="submit" value="注册" />
				</div>
				<div>
					<input type="button" id="cancel" value="取消" />
				</div>
				
				<div class="modal fade" id="modal-container" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 class="modal-title" id="myModalLabel">账户验证</h4>
							</div>
							
							<div class="modal-body">
								<form>
									<input id="code" placeholder="请输入验证码" />
									<span><img id="check" src="<?php echo $base_url; ?>/static/images/check.jpg"></span><br />
									<span id="tips" style="color:red"></span>
								</form>
							</div>
							
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button> 
								<button type="reset" class="btn btn-default" id="reset">重置</button>
								<button type="button" class="btn btn-primary" id="check_code" data-dismiss="modal" style="background:#0000aa">提交</button>
							</div>
							
						</div>
					</div>
				</div>
				<style type="text/css">
				#code {
					height: 40px;
					text-align: center;
					margin-right: 10px;
				}
				#check {
					height: 20px;
					display: none;
				}
				</style>
				
			</form>
		</section>
	</div>
</body>
</html>
