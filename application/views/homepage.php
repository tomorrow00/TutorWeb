<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>导师信息网</title>
		<script src="<?php echo $base_url; ?>/static/js/jquery-3.1.1.min.js"></script>
		<link rel="stylesheet" href="<?php echo $base_url; ?>/static/css/bootstrap.min.css" type="text/css" />
		<script src="<?php echo $base_url; ?>/static/js/bootstrap.min.js"></script>
	
		<script type="text/javascript">
		$(function() {
			$("#regular_search").click(function () {
				var txt = $("#search_text").val();
				var selected = $('#show').text();
				
				document.location = "<?php echo $base_url; ?>/teacher/regular_search?txt=" + txt + "&page=" + 1;
			})
			
			$("#logout").click(function () {
				$.ajax({
					url:"<?php echo $base_url;?>/login/logout"
				});
			})
		})
		</script>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li>
						<?php
							if (isset($_SESSION['usr'])) {
								$usr = $_SESSION['usr'];
						?>
								<a id="username" href="<?php echo $base_url; ?>/user"><?php echo $usr; ?></a>
							</li>
							<li>
								<a id="logout" href="">注销</a>
						<?php
							}
							else {
						?>
								<a href="<?php echo $base_url; ?>/register" id="register">注册</a>
							</li>
							<li>
								<a href="<?php echo $base_url; ?>/login" id="login">登录</a>
						<?php
							}
						?>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="row">
				<img class="img-responsive center-block" src="<?php echo $base_url; ?>/static/images/logo.jpg" id="logo">
				<style type="text/css">
					#logo {
						margin-top: 50px;
						width: 100px;
					}
				</style>
			</div>
			
			<div class="row">
				<div class="col-md-12">
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<style type="text/css">
								#search {
									width: 40%;
									margin-top: 40px;
									margin-left: 30%;
									margin-right: 30%;
								}
								
								#regular_search {
									height: 45px;
									display: inline-block;
								}
								
								#search_text {
									height: 45px;
								}
							</style>
							
							<nobr><div class="input-group" id="search">
								<input type="text" class="form-control input-lg" id="search_text" placeholder="请输入搜索内容">
								<input type="image" src="<?php echo $base_url; ?>/static/images/search.jpg" id="regular_search" />
							</div></nobr>
							
						</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6" id="teacher_hot">
					<ul style="list-style-type:none;">
						<li>
							<h4>教师热搜</h4>
						</li>
						
						<?php foreach ($teacherSearch as $item): ?>
						<li>
							<?php
								if(isset($item->Teacher_HomePage)) {
									echo "<a href=".$item->Teacher_HomePage.">".$item->Teacher_Name."</a>";
								}
								else {
									echo "<p>".$item->Teacher_Name."</p>";
								}
							?>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				
				<div class="col-md-6" id="dir_hot">
					<ul style="list-style-type:none;">
						<li>
							<h4>专业热搜</h4>
						</li>
						
						<?php foreach ($majorSearch as $item): ?>
						<li>
							<?php echo $item->Major_Name; ?>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				
				<style type="text/css">
					#teacher_hot {
						padding-left: 30%;
						margin-top: 40px;
					}
					#teacher_hot li{
						margin-top: 10px;
					}
					
					#dir_hot {
						margin-top: 40px;
					}
					#dir_hot li{
						margin-top: 10px;
					}
				</style>
			</div>
			
		</div>
	</body>
</html>
