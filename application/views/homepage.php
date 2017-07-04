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
				var sv = '*';
				
				/*switch(selected) {
					case '搜索全部':
						var sv = '*';
						break;
					case '姓名':
						var sv = 'Teacher_Name';
						break;
					case '性别':
						var sv = 'Teacher_Sex';
						break;
					case '单位':
						var sv = 'Teacher_Unit';
						break;
					case '职称':
						var sv = 'Teacher_ProTitle';
						break;
					case '职务':
						var sv = 'Teacher_Duty';
						break;
					case '专业':
						var sv = 'Teacher_Major';
						break;
					case '方向':
						var sv = 'Teacher_Dir';
						break;
					case '头衔':
						var sv = 'Teacher_Title';
						break;
					case '个人简介':
						var sv = 'Teacher_Resume';
						break;
					case '个人主页链接':
						var sv = 'Teacher_HomePage';
						break;
					case '联系方式':
						var sv = 'Teacher_Tel';
						break;
				}*/
				
				document.location = "<?php echo $base_url;?>teacher/regular_search?txt=" + txt + "&sv=" + sv + "&page=" + 1;
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
							<a href="<?php echo $base_url;?>/register">注册</a>
						</li>
						<li>
							<a href="<?php echo $base_url;?>/login">登录</a>
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
						
						<?php foreach ($teacherScroll as $item): ?>
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
						
						<?php foreach ($teacherScroll as $item): ?>
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
