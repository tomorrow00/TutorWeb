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
					url:"<?php echo $base_url;?>search_major",
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
					url:"<?php echo $base_url;?>search_major",
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
				var sv = '*';
				
				document.location = "<?php echo $base_url;?>/teacher/regular_search?txt=" + txt + "&sv=" + sv + "&page=1";
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
					url:"<?php echo $base_url;?>/login/logout",
					success:function() {
						location.href = "<?php echo $base_url; ?>/";
					}
				});
			})
			
			$('#edit').click(function (){
				$('#input_unit').html("<input type='text' value='asdasd' />");
			})
		})
		
		function showdirshowdir(i) {
			var show = document.getElementById("showdir" + i);
			var hidden = document.getElementById("hiddendir" + i);
			var fold = document.getElementById("fold" + i)
			var unfold = document.getElementById("unfold" + i)
			show.style.display = 'none';
			hidden.style.display = 'inline';
			fold.style.display = 'inline';
			unfold.style.display = 'none';
		}
		
		function hidedir(i) {
			var show = document.getElementById("showdir" + i);
			var hidden = document.getElementById("hiddendir" + i);
			var fold = document.getElementById("fold" + i)
			var unfold = document.getElementById("unfold" + i)
			show.style.display = 'inline';
			hidden.style.display = 'none';
			fold.style.display = 'none';
			unfold.style.display = 'inline';
		}
		</script>
	</head>
	
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<nav class="navbar navbar-default navbar-inverse" role="navigation">
						<div class="navbar-header">
							<a style="color:white" class="navbar-brand" href="<?php echo $base_url;?>/">导师信息网</a>
						</div>
				
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<li>
									<a href="#">Link</a>
								</li>
								<li>
									<a href="#">Link</a>
								</li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="show">搜索全部</a>
									<style type="text/css">
										#show {width:120px;}
									</style>
									
									<ul class="dropdown-menu" role="menu">
										<li><a href="#" id="searchall" onclick="replace_search('搜索全部')">搜索全部</a></li>
										<li><a href="#" id="name" onclick="replace_search('姓名')">姓名</a></li>
										<li><a href="#" id="sex" onclick="replace_search('性别')">性别</a></li>
										<li><a href="#" id="unit" onclick="replace_search('单位')">单位</a></li>
										<li><a href="#" id="protitle" onclick="replace_search('职称')">职称</a></li>
										<li><a href="#" id="duty" onclick="replace_search('职务')">职务</a></li>
										<li><a href="#" id="semajor" onclick="replace_search('专业')">专业</a></li>
										<li><a href="#" id="dir" onclick="replace_search('方向')">方向</a></li>
										<li><a href="#" id="title" onclick="replace_search('头衔')">头衔</a></li>
										<li><a href="#" id="resume" onclick="replace_search('个人简介')">个人简介</a></li>
										<li><a href="#" id="homepage" onclick="replace_search('个人主页链接')">个人主页链接</a></li>
										<li><a href="#" id="tel" onclick="replace_search('联系方式')">联系方式</a></li>
									</ul>
								</li>
							</ul>
							
							<form class="navbar-form navbar-left" role="search">
								<div class="form-group">
									<input type="text" class="form-control" id="search_text" placeholder="请输入搜索内容">
								</div>
								
								<button type="button" class="btn btn-default" id="regular_search">搜索</button>
								<a style="color:gray;" onmouseover="this.style.color='white'" onmouseout="this.style.color='gray'" id="modal" href="#modal-container" role="button" class="btn" data-toggle="modal">高级搜索</a>
								
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
							</form>
							
							<ul class="nav navbar-nav navbar-right">
								<li>
									<a id="username" href="<?php echo $base_url; ?>/user"><?php echo $_SESSION['usr']; ?></a>
								</li>
								<li>
									<a id="logout" href="">注销</a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</div>
			<br />
			
			<div class="row">
				<div class="col-md-2">
					<div class="span">
						<ul class="nav nav-list">
							<li class="nav-header">
								<h4>个人中心</h4>
							</li>
							<li class="active">
								<a href="<?php echo $base_url;?>/user/information" style="color:white;background:#0000aa">我的资料</a>
							</li>
							<li>
								<a href="<?php echo $base_url;?>/user/collection?page=1">我的收藏</a>
							</li>
							<li>
								<a href="<?php echo $base_url;?>/user/app">我的应用</a>
							</li>
						</ul>
					</div>
				</div>
				
				<div class="col-md-10">
					<div class="span">
						<div class="panel-group">
							<div class="panel panel-default">
								<div class="panel-heading">
									 <a class="panel-title" data-toggle="collapse" href="#user_info">
										用户信息
									</a>
								</div>
								<div id="user_info" class="panel-collapse collapse in">
									<div class="panel-body">
										
										<div id="user">
											<style type="text/css">
												user_table {
													table-layout: fixed; 
													word-wrap: break-word; 
													width: 100%;
												}
											</style>
										
											<?php 
												$i = 0;
												foreach ($teacherList as $item):
											?>
											
											<?php
											if(isset($item->Teacher_Photo)) {
											?>
												<img id='userImg' src='<?php echo $base_url; ?>/static/images/TeacherPhoto/<?php echo $item->Teacher_Photo; ?>' alt='图片加载失败' onerror="this.src='<?php echo $base_url; ?>/static/images/logo.jpg'" />
											<?php
											}
											else {
											?>
												<img id='userImg' src='<?php echo $base_url; ?>/static/images/logo.jpg' alt='图片加载失败'>
											<?php } ?>
											
												<style type="text/css">
												#userImg {
													width: 60px;
													display: block;
													margin: 0 auto;
													margin-bottom: 30px;
													margin-top: 20px;
													border-radius: 20px;
												}
												
												#collectionImg<?php echo $item->Teacher_ID; ?> 
												{
													width: 20px;
													margin-right: 5px;
													margin-bottom: 5px;
													filter: alpha(opacity=50);
													opacity: 0.9;
												}
												</style>
												
												<ul style="list-style-type:none;" class="list-inline">
													<li>
														<h3>&nbsp;<?php echo $item->Teacher_Name; ?></h3>
													</li>
													<li>
														<input type="button" id="edit" value="编辑" onclick="edit()" />
														<!-- <a href="" id="edit">编辑</a> -->
													</li>
												</ul>
												
												<ul style="list-style-type:none;" class="list-inline">
													<?php
													if(isset($item->Teacher_Unit)) {
													?>
														<li style='color:#737373'>单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位：</li>
														<li id="input_unit"><?php echo $item->Teacher_Unit; ?></li>
													<?php
													}
													?>
												</ul>
												
												<ul style="list-style-type:none;" class="list-inline">
													<?php
													if(isset($item->Teacher_Duty) && $item->Teacher_Duty != "无") {
													?>
														<li style='color:#737373'>职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;务：</li>
														<li><?php echo $item->Teacher_Duty; ?></li>
													<?php } ?>
												</ul>
												
												<ul style="list-style-type:none;" class="list-inline">
													<?php
													if(isset($item->Teacher_ProTitle)) {
													?>
														<li style='color:#737373'>职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称：</li>
														<li><?php echo $item->Teacher_ProTitle; ?>
													<?php
														if($item->Teacher_MT == 1 && $item->Teacher_DT == 1) {
													?>
															/博士生导师</li>
													<?php 
														}
														elseif($item->Teacher_MT == 1 && $item->Teacher_DT == 0) {
													?>
															/硕士生导师</li>
													<?php
														}
														else {
													?>
															</li>
													<?php
														}
													}
													?>
												</ul>
												
												<ul style="list-style-type:none;" class="list-inline">
													<?php
													if(isset($item->Teacher_Title)) {
													?>
														<li style='color:#737373'>头&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;衔：</li>
														<li><?php echo $item->Teacher_Title; ?></li>
													<?php } ?>
												</ul>
												
												<ul style="list-style-type:none;" class="list-inline">
													<?php
													if(isset($item->Major_Name)) {
														if(isset($item->Major2)){
													?>
															<li style='color:#737373'>专&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;业：</li>
															<li>一级学科：<?php echo $item->Major1; ?></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															<li>二级学科：<?php echo $item->Major2; ?></li>
														<?php
														}
														else{
														?>
															<li style='color:#737373'>专&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;业：</li>
															<li>一级学科：<?php echo $item->Major1; ?></li>
													<?php
														}
													}
													?>
												</ul>
												
												<ul style="list-style-type:none;" class="list-inline">
													<?php
													if(isset($item->Teacher_HomePage)) {
													?>
														<li style='color:#737373'>主&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;页：</li>
														<li><a href=<?php echo $item->Teacher_HomePage; ?> target="_blank">个人主页</a></li>
													<?php } ?>
												</ul>
												
												<ul style="list-style-type:none;" class="list-inline">
													<?php
													if(isset($item->Teacher_Dir)) {
													?>
														<li style='color:#737373'>方&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;向：</li>
														<li><?php echo $item->Teacher_Dir; ?></li>
													<?php 
													}
													?>
												</ul>
												
												<ul style="list-style-type:none;" class="list-inline">
													<?php
													if(isset($item->Teacher_Tel)) {
													?>
														<li style='color:#737373'>联系方式：</li>
														<li><?php echo $item->Teacher_Tel; ?></li>
													<?php } ?>
												</ul>
												
											</tr>
											<?php endforeach; ?>
										</div>
										
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									 <a class="panel-title collapsed" data-toggle="collapse" data-parent="#panel-736360" href="#teacher_info">
										导师信息
									 </a>
								</div>
								<div id="teacher_info" class="panel-collapse collapse">
									<div class="panel-body">
										
										<div id="table">
											<table class="table table-striped table-bordered">
												<style type="text/css">
													table {
														table-layout: fixed; 
														word-wrap: break-word; 
														width: 100%;
													}
												</style>
											
												<?php 
													$i = 0;
													foreach ($teacherList as $item):
												?>
												<tr>
													<td style="text-align:center">
													<?php
													if(isset($item->Teacher_Photo)) {
													?>
														<img id='teacherImg' src='<?php echo $base_url; ?>/static/images/TeacherPhoto/<?php echo $item->Teacher_Photo; ?>' alt='图片加载失败' onerror="this.src='<?php echo $base_url; ?>/static/images/logo.jpg'" />
													<?php
													}
													else {
													?>
														<img id='teacherImg' src='<?php echo $base_url; ?>/static/images/logo.jpg' alt='图片加载失败'>
													<?php } ?>
													
														<style type="text/css">
														#teacherImg {
															width: 60%;
															display: block;
															margin: 0 auto;
															margin-bottom: 30px;
															margin-top: 20px;
															border-radius: 20px;
														}
														
														#collectionImg<?php echo $item->Teacher_ID; ?> 
														{
															width: 20px;
															margin-right: 5px;
															margin-bottom: 5px;
															filter: alpha(opacity=50);
															opacity: 0.9;
														}
														</style>
														
													</td>
													
													<td style="width:80%">
														<ul style="list-style-type:none;" class="list-inline">
															<li>
																<h3>&nbsp;<?php echo $item->Teacher_Name; ?></h3>
															</li>
															<li>
																<input type="button" id="edit" value="编辑" onclick="edit()" />
																<!-- <a href="" id="edit">编辑</a> -->
															</li>
														</ul>
														
														<ul style="list-style-type:none;" class="list-inline">
															<?php
															if(isset($item->Teacher_Unit)) {
															?>
																<li style='color:#737373'>单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位：</li>
																<li id="input_unit"><?php echo $item->Teacher_Unit; ?></li>
															<?php
															}
															?>
														</ul>
														
														<ul style="list-style-type:none;" class="list-inline">
															<?php
															if(isset($item->Teacher_Duty) && $item->Teacher_Duty != "无") {
															?>
																<li style='color:#737373'>职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;务：</li>
																<li><?php echo $item->Teacher_Duty; ?></li>
															<?php } ?>
														</ul>
														
														<ul style="list-style-type:none;" class="list-inline">
															<?php
															if(isset($item->Teacher_ProTitle)) {
															?>
																<li style='color:#737373'>职&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称：</li>
																<li><?php echo $item->Teacher_ProTitle; ?>
															<?php
																if($item->Teacher_MT == 1 && $item->Teacher_DT == 1) {
															?>
																	/博士生导师</li>
															<?php 
																}
																elseif($item->Teacher_MT == 1 && $item->Teacher_DT == 0) {
															?>
																	/硕士生导师</li>
															<?php
																}
																else {
															?>
																	</li>
															<?php
																}
															}
															?>
														</ul>
														
														<ul style="list-style-type:none;" class="list-inline">
															<?php
															if(isset($item->Teacher_Title)) {
															?>
																<li style='color:#737373'>头&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;衔：</li>
																<li><?php echo $item->Teacher_Title; ?></li>
															<?php } ?>
														</ul>
														
														<ul style="list-style-type:none;" class="list-inline">
															<?php
															if(isset($item->Major_Name)) {
																if(isset($item->Major2)){
															?>
																	<li style='color:#737373'>专&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;业：</li>
																	<li>一级学科：<?php echo $item->Major1; ?></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																	<li>二级学科：<?php echo $item->Major2; ?></li>
																<?php
																}
																else{
																?>
																	<li style='color:#737373'>专&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;业：</li>
																	<li>一级学科：<?php echo $item->Major1; ?></li>
															<?php
																}
															}
															?>
														</ul>
														
														<ul style="list-style-type:none;" class="list-inline">
															<?php
															if(isset($item->Teacher_HomePage)) {
															?>
																<li style='color:#737373'>主&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;页：</li>
																<li><a href=<?php echo $item->Teacher_HomePage; ?> target="_blank">个人主页</a></li>
															<?php } ?>
														</ul>
														
														<ul style="list-style-type:none;" class="list-inline">
															<?php
															if(isset($item->Teacher_Dir)) {
																if (mb_strlen($item->Teacher_Dir) >= 10) {
																	$show = mb_substr($item->Teacher_Dir, 0, 10, 'utf-8');
															?>
																	<li style='color:#737373'>方&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;向：</li>
																	<li id='showdir<?php echo $i; ?>'><?php echo $show; ?></li>
																	<li id='unfold<?php echo $i; ?>' onclick='showdir(<?php echo $i; ?>)'><a>展开>></a></li>
																	<li id='hiddendir<?php echo $i; ?>' style='display:none'><?php echo $item->Teacher_Dir; ?></li>
																	<li id='fold<?php echo $i; ?>' onclick='hidedir(<?php echo $i; ?>)' style='display:none'><a>收起<<</a></li>
															<?php
																}
																else {
															?>
																	<li style='color:#737373'>方&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;向：</li>
																	<li id='dirLi<?php echo $i; ?>'><?php echo $item->Teacher_Dir; ?></li>
															<?php 
																}
															}
															$i ++;
															?>
														</ul>
														
														<ul style="list-style-type:none;" class="list-inline">
															<?php
															if(isset($item->Teacher_Tel)) {
															?>
																<li style='color:#737373'>联系方式：</li>
																<li><?php echo $item->Teacher_Tel; ?></li>
															<?php } ?>
														</ul>
													</td>
													
												</tr>
												<?php endforeach; ?>
											
											</table>
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-2">
					<address>
						 <strong>Twitter, Inc.</strong><br> 795 Folsom Ave, Suite 600<br> San Francisco, CA 94107<br> <abbr title="Phone">P:</abbr> (123) 456-7890
					</address>
				</div>
				<div class="col-md-6">
					<p class="text-center">中国石油大学（北京）</p>
				</div>
				<div class="col-md-4">
					<p class="text-center">眼睛肿的像铜铃</p>
				</div>
			</div>
			
		</div>
	</body>
</html>
