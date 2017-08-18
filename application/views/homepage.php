<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>导师信息网</title>
		
		<script src="<?php echo $base_url; ?>/static/js/jquery-3.1.1.min.js"></script>
		<script src="<?php echo $base_url; ?>/static/js/bootstrap.min.js"></script>
		
		<link rel="stylesheet" href="<?php echo $base_url; ?>/static/css/bootstrap.min.css" type="text/css" />
	
		<script type="text/javascript">
		$(function() {
			$.ajax({
				url:"<?php echo $base_url;?>/teacher/search_major",
				type:"POST",
				dataType:'json',
				data:{"code":0},
				success:function (data) {
					for (var i = 0; i < data.majorList.length; i ++) {
						$('#modal_major1').append("<option value='" + data.majorList[i].Major_Code + "'>" + data.majorList[i].Major_Name + "</option>");
					}
				}
			});
			
			$('#modal_major1').change(function () {
				var relatedID = $('#modal_major1').find("option:selected").val();
				$('#modal_major2').empty();
				$('#modal_major3').empty();
				$('#modal_major3').append("<option value='*'>不限</option>");
				
				$.ajax({
					url:"<?php echo $base_url;?>/teacher/search_major",
					type:"POST",
					dataType:'json',
					data:{"code":relatedID},
					success:function (data) {
						$('#modal_major2').append("<option value='*'>不限</option>");
						for (var i = 0; i < data.majorList.length; i ++) {
							$('#modal_major2').append("<option value='" + data.majorList[i].Major_Code + "'>" + data.majorList[i].Major_Name + "</option>");
						}
					}
				});
			})
			
			$('#modal_major2').change(function () {
				var relatedID = $('#modal_major2').find("option:selected").val();
				$('#modal_major3').empty();
				
				$.ajax({
					url:"<?php echo $base_url;?>/teacher/search_major",
					type:"POST",
					dataType:'json',
					data:{"code":relatedID},
					success:function (data) {
						$('#modal_major3').append("<option value='*'>不限</option>");
						for (var i = 0; i < data.majorList.length; i ++) {
							$('#modal_major3').append("<option value='" + data.majorList[i].Major_Code + "'>" + data.majorList[i].Major_Name + "</option>");
						}
					}
				});
			})
			
			$("#regular_search").click(function () {
				var txt = $("#search_text").val();
				var selected = $('#show').text();
				
				document.location = "<?php echo $base_url; ?>/teacher/regular_search?txt=" + txt + "&page=" + 1;
			})
			
			$("#advanced_search").click(function () {
				var name = "";
				var sex = "";
				var unit = "";				
				var protitle = "";
				var duty = "";
				var direction = "";
				var title = "";
				var flag = 0;
				
				<?php
				if (isset($_SESSION['id'])) {
				?>
					var sql = "SELECT * FROM (Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code) left join Collection as c on a.Teacher_ID=c.C_Teacher_ID WHERE ";
				<?php
				}
				else {
				?>
					var sql = "SELECT * FROM Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code WHERE ";
				<?php
				}
				?>
				
				if ($("#modal_name").val()) {
					name = $("#modal_name").val();
					sql += "Teacher_Name LIKE N'%" + name + "%'";
					flag = 1;
				}
				
				if ($("input:radio[name='modal_sex']:checked").val()) {
					sex = $("input:radio[name='modal_sex']:checked").val();
					if (sex != "*") {
						if (flag == 1) {
							sql += " AND ";
						}
						sql += "Teacher_Sex='" + sex + "'";
						flag = 1;
					}
				}
				
				if ($("#modal_unit").val()) {
					unit = $("#modal_unit").val();
					if (flag == 1) {
						sql += " AND ";
					}
					sql += "Teacher_Unit LIKE N'%" + unit + "%'";
					flag = 1;
				}
						
				if ($("#modal_protitle").find('option:selected').val()) {
					protitle = $("#modal_protitle").find('option:selected').val();
					if (protitle != "*") {
						if (flag == 1) {
							sql += " AND ";
						}
						sql += "Teacher_ProTitle LIKE N'%" + protitle + "%'";
						flag = 1;
					}
				}
				
				if ($("#modal_duty").find('option:selected').val()) {
					duty = $("#modal_duty").find('option:selected').val();
					if (duty != "*") {
						if (flag == 1) {
							sql += " AND ";
						}
						sql += "Teacher_Duty LIKE N'%" + duty + "%'";
						flag = 1;
					}
				}
				
				if ($("#modal_major1").find('option:selected').val() || $("#modal_major2").find('option:selected').val() || $("#major3").find('option:selected').val()) {
					if ($("#modal_major1").find('option:selected').val() != "*" && $("#modal_major2").find('option:selected').val() != "*" && $("#modal_major3").find('option:selected').val() != "*") {
						if (flag == 1) {
							sql += " AND ";
						}
						major = $("#modal_major3").find('option:selected').text();
						sql += "Major_Name='" + major + "'";
						flag = 1;
					}
					else if ($("#modal_major1").find('option:selected').val() != "*" && $("#modal_major2").find('option:selected').val() != "*" && $("#modal_major3").find('option:selected').val() == "*") {
						if (flag == 1) {
							sql += " AND ";
						}
						
						var major = $("#modal_major2").find('option:selected').val();
						sql += "Major_Code LIKE '" + major + "__%'";
						flag = 1;
					}
					else if ($("#modal_major1").find('option:selected').val() != "*" && $("#modal_major2").find('option:selected').val() == "*" && $("#modal_major3").find('option:selected').val() == "*") {
						if (flag == 1) {
							sql += " AND ";
						}
						var major = $("#modal_major1").find('option:selected').val();
						
						sql += "Major_Code LIKE '" + major + "____%'";
						flag = 1;
					}
				}
				
				if ($("#modal_title").find('option:selected').val()) {
					title = $("#modal_title").find('option:selected').val();
					if (title != "*") {
						if (flag == 1) {
							sql += " AND ";
						}
						sql += "Teacher_Title LIKE N'%" + title + "%'";
						flag = 1;
					}
				}
				
				if (flag == 0) {
					<?php
					if (isset($_SESSION['id'])) {
					?>
						sql = "SELECT * FROM (Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code) left join Collection as c on a.Teacher_ID=c.C_Teacher_ID";
					<?php
					}
					else {
					?>
						sql = "SELECT * FROM Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code";
					<?php
					}
					?>
				}
				
				document.location = "<?php echo $base_url;?>/teacher/advanced_search?sql=" + sql + "&page=1";
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
							<input type="text" class="form-control input-lg" id="search_text" placeholder="请输入导师姓名/单位/专业等">
							<input type="image" src="<?php echo $base_url; ?>/static/images/search.jpg" id="regular_search" />
							
							<a style="color:gray;margin-bottom:30px" onmouseover="this.style.color='blue'" onmouseout="this.style.color='gray'" id="modal" href="#modal-container" role="button" class="btn" data-toggle="modal"><h4>高级搜索</h4></a>
							
							<div class="modal fade" id="modal-container" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title" id="myModalLabel">高级搜索</h4>
										</div>
										<div class="modal-body">
											<form>
												<label>姓名：</label>
												<input type="text" id="modal_name" placeholder="请输入姓名" /><br />
												<label>性别：</label>
												<input type="radio" name="modal_sex" id="modal_all" value="*" checked />全部
												<input type="radio" name="modal_sex" id="modal_male" value="男" />男
												<input type="radio" name="modal_sex" id="modal_female" value="女" />女<br />
												<label>单位：</label>
												<input type="text" id="modal_unit" placeholder="请输入单位" /><br />
												<label>职称：</label>
												<select id="modal_protitle">
													<option value="*">不限</option>
													<option value="教授">教授</option>
													<option value="副教授">副教授</option>
													<option value="讲师">讲师</option>
												</select>
												<br />
												<label>职务：</label>
												<select id="modal_duty">
													<option value="*">不限</option>
													<option value="校长">校长</option>
													<option value="副校长">副校长</option>
													<option value="院长">院长</option>
													<option value="副院长">副院长</option>
													<option value="系主任">系主任</option>
													<option value="系副主任">系副主任</option>
												</select>
												<br />
												<label>专业：</label>
												<select id="modal_major1">
													<option value='*'>不限</option>
												</select>
												<select id="modal_major2">
													<option value='*'>不限</option>
												</select>
												<select id="modal_major3">
													<option value='*'>不限</option>
												</select>
												<br />
												<label>头衔：</label>
												<select id="modal_title">
													<option value="*">不限</option>
													<option value="院士">院士</option>
													<option value="千人">千人</option>
													<option value="万人">万人</option>
													<option value="长江学者">长江学者</option>
													<option value="杰青">杰青</option>
													<option value="优青">优青</option>
												</select>
												<br />
											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button> 
											<button type="reset" class="btn btn-default" id="reset">重置</button>
											<button type="button" class="btn btn-primary" id="advanced_search" data-dismiss="modal" style="background:#0000aa">提交</button>
										</div>
									</div>
								</div>
							</div>
							
						</div></nobr>
						
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-7" id="teacher_hot">
					<ul style="list-style-type:none;">
						<li>
							<h4>教师热搜</h4>
						</li>
						
						<?php foreach ($teacherSearch as $item): ?>
						<li>
							<?php
							if(isset($item->Teacher_Photo)) {
							?>
								<img id='teacherImg' src='<?php echo $base_url; ?>/static/images/TeacherPhoto/<?php echo $item->Teacher_Photo; ?>' alt='图片加载失败' onerror="this.src='<?php echo $base_url; ?>/static/images/nothing1.png'" />
							<?php
							}
							else {
							?>
								<img id='teacherImg' src='<?php echo $base_url; ?>/static/images/nothing1.png' alt='图片加载失败'>
							<?php
							}
							?>
							
								<style type="text/css">
								#teacherImg {
									width: 40px;
									height: 50px;
									border-radius: 5px;
									margin-right: 15px;
								}
								</style>
							
							<?php
							if(isset($item->Teacher_HomePage)) {
							?>
								<a href="<?php echo $item->Teacher_HomePage; ?>" target="_blank"><?php echo $item->Teacher_Name; ?></a>
							<?php
							}
							else {
							?>
								<span><?php echo $item->Teacher_Name; ?></span>
							<?php 
							}
							?>
						</li>
						<?php endforeach; ?>
						
					</ul>
				</div>
				
				<div class="col-md-5" id="dir_hot">
					<ul style="list-style-type:none;">
						<li>
							<h4>专业热搜</h4>
						</li>
						
						<?php foreach ($majorSearch as $item): ?>
						<li id="major">
							<?php echo $item->Major_Name; ?>
						</li>
						
						<style type="text/css">
						#major {
							height: 50px;
							padding-top: 13px;
						}
						</style>
						
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
