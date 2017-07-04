<html>
	<head>
    	<meta http-equiv="content-type" content="text/html; charset=utf-8">
    	<title>导师信息</title>
		<script src="<?php echo $base_url; ?>/static/js/jquery-3.1.1.min.js"></script>
		<link rel="stylesheet" href="<?php echo $base_url; ?>/static/css/bootstrap.min.css" type="text/css" />
		<script src="<?php echo $base_url; ?>/static/js/bootstrap.min.js"></script>
		<script type="text/javascript">
		$(function() {
			$.ajax({
				url:"<?php echo $base_url;?>search_major",
				type:"POST",
				dataType:'json',
				data:{"code":0},
				success:function (data) {
					//$('#major1').append("<option value='*'>不限</option>");
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
				document.location = "<?php echo $base_url;?>regular_search?txt=" + txt + "&sv=" + sv + "&page=1";
			})
			
			$("#advanced_search").click(function () {
				var name = "";
				//var age1 = "";
				//var age2 = "";
				var sex = "";
				var unit = "";				
				var protitle = "";
				var duty = "";
				var direction = "";
				var title = "";
				var sql = "SELECT * FROM Teacher as a left join Major as b on a.Teacher_Major=b.Major_Name WHERE ";
				var flag = 0;
				
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
						sql += "Teacher_Major='" + major + "'";
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
					sql = "SELECT * FROM Teacher";
				}
				
				document.location = "<?php echo $base_url;?>advanced_search?sql=" + sql + "&page=1";
			})
		})
		
		function replace_search(data) {
			var selected = data;
			document.getElementById("show").text = selected;
		}
		
		function page_pre_alert(pageNow, pageCount) {
			if (pageNow == 1) {
				alert("这已经是第一页了！");
			}
		}
		
		function page_next_alert(pageNow, pageCount) {
			if (pageNow == pageCount) {
				alert("这已经是最后一页了！");
			}
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
										<!-- <li><a href="#" id="ID" onclick="replace_search('ID')">ID</a></li> -->
										<li><a href="#" id="name" onclick="replace_search('姓名')">姓名</a></li>
										<!-- <li><a href="#" id="age" onclick="replace_search('年龄')">年龄</a></li> -->
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
												<button type="button" class="btn btn-primary" id="advanced_search" data-dismiss="modal">提交</button>
											</div>
										</div>
									</div>
								</div>
							</form>
							
							<ul class="nav navbar-nav navbar-right">
								<li>
									<a href="<?php echo $base_url;?>/register">注册</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>/login">登录</a>
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
								列表标题
							</li>
							<li class="active">
								<a href="#">首页</a>
							</li>
							<li>
								<a href="#">库</a>
							</li>
							<li>
								<a href="#">应用</a>
							</li>
							<li class="nav-header">
								功能列表
							</li>
							<li>
								<a href="#">资料</a>
							</li>
							<li>
								<a href="#">设置</a>
							</li>
							<li class="divider">
							</li>
							<li>
								<a href="#">帮助</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-md-10">
					<div class="span">
						<h4>对不起，您所查找的内容不存在！</h4>
					</div>
				</div>
			</div>
			
			<div class="row" id="footer">
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