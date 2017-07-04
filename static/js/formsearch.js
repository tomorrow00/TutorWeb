$(function() {
	$.ajax({
		url:"<?php echo $base_url;?>teacher/search_major",
		type:"POST",
		dataType:'json',
		data:{"code":0},
		success:function (data) {
			//$('#major1').append("<option value='*'>请选择专业</option>");
			for (var i = 0; i < data.majorList.length; i ++) {
				$('#major1').append("<option value='" + data.majorList[i].Major_Code + "'>" + data.majorList[i].Major_Name + "</option>");
			}
		}
	});
	
	$('#major1').change(function () {
		var relatedID = $('#major1').find("option:selected").val();
		$('#major2').empty();
		$('#major3').empty();
		$('#major3').append("<option value='*'>请选择专业</option>");
		
		$.ajax({
			url:"<?php echo $base_url;?>teacher/search_major",
			type:"POST",
			dataType:'json',
			data:{"code":relatedID},
			success:function (data) {
				$('#major2').append("<option value='*'>请选择专业</option>");
				for (var i = 0; i < data.majorList.length; i ++) {
					$('#major2').append("<option value='" + data.majorList[i].Major_Code + "'>" + data.majorList[i].Major_Name + "</option>");
				}
			}
		});
	})
	
	$('#major2').change(function () {
		var relatedID = $('#major2').find("option:selected").val();
		$('#major3').empty();
		
		$.ajax({
			url:"<?php echo $base_url;?>teacher/search_major",
			type:"POST",
			dataType:'json',
			data:{"code":relatedID},
			success:function (data) {
				$('#major3').append("<option value='*'>请选择专业</option>");
				for (var i = 0; i < data.majorList.length; i ++) {
					$('#major3').append("<option value='" + data.majorList[i].Major_Code + "'>" + data.majorList[i].Major_Name + "</option>");
				}
			}
		});
	})
	
	$("#search_button").click(function () {
		var txt = $("#search_text").val();
		var selected = $('#show').html();
		switch(selected) {
			case '搜索全部':
				var sv = '*';
				break;
			case 'ID':
				var sv = 'Teacher_ID';
				break;
			case '姓名':
				var sv = 'Teacher_Name';
				break;
			case '年龄':
				var sv = 'Teacher_Age';
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
		}
		$('#divTemp').load('<?php echo $base_url;?>teacher/search_result', {"txt":txt, "sv":sv});
	})
	
	$("#submit").click(function () {
		var name = "";
		var age1 = "";
		var age2 = "";
		var sex = "";
		var unit = "";				
		var protitle = "";
		var duty = "";
		var direction = "";
		var title = "";
		var sql = "SELECT * FROM Teacher as a left join Major as b on a.Teacher_Major=b.Major_Name WHERE ";
		var flag = 0;
		
		if ($("#name").val()) {
			name = $("#name").val();
			sql += "Teacher_Name LIKE N'%" + name + "%'";
			flag = 1;
		}
		
		if (($("#age1").find("option:selected").val() != "*" && $("#age2").find("option:selected").val() == "*") || ($("#age1").find("option:selected").val() == "*" && $("#age2").find("option:selected").val() != "*")) {
			alert("请选择年龄区间！");
		}
		else if ($("#age1").find("option:selected").val() != "*" && $("#age2").find("option:selected").val() != "*") {
			if ($("#age1").find("option:selected").val() <= $("#age2").find("option:selected").val()) {
				age1 = $("#age1").find("option:selected").val();
				age2 = $("#age2").find("option:selected").val();
				if (flag == 1) {
					sql += " AND ";
				}
				sql += "Teacher_Age<=" + age2 + " AND Teacher_Age>=" + age1;
				flag = 1;
			}
			else {
				alert("请选择正确的年龄区间！");
			}
		}
		
		if ($("input:radio[name='sex']:checked").val()) {
			sex = $("input:radio[name='sex']:checked").val();
			if (sex != "*") {
				if (flag == 1) {
					sql += " AND ";
				}
				sql += "Teacher_Sex='" + sex + "'";
				flag = 1;
			}
		}
		
		if ($("#unit").val()) {
			unit = $("#unit").val();
			if (flag == 1) {
				sql += " AND ";
			}
			sql += "Teacher_Unit LIKE N'%" + unit + "%'";
			flag = 1;
		}
				
		if ($("#protitle").find('option:selected').val()) {
			protitle = $("#protitle").find('option:selected').val();
			if (protitle != "*") {
				if (flag == 1) {
					sql += " AND ";
				}
				sql += "Teacher_ProTitle='" + protitle + "'";
				flag = 1;
			}
		}
		
		if ($("#duty").find('option:selected').val()) {
			duty = $("#duty").find('option:selected').val();
			if (duty != "*") {
				if (flag == 1) {
					sql += " AND ";
				}
				sql += "Teacher_Duty='" + duty + "'";
				flag = 1;
			}
		}
		
		if ($("#major1").find('option:selected').val() && $("#major2").find('option:selected').val() && $("#major3").find('option:selected').val()) {
			if ($("#major1").find('option:selected').val() != "*" && $("#major2").find('option:selected').val() != "*" && $("#major3").find('option:selected').val() != "*") {
				if (flag == 1) {
					sql += " AND ";
				}
				major = $("#major3").find('option:selected').text();
				sql += "Teacher_Major='" + major + "'";
				flag = 1;
			}
			else if ($("#major1").find('option:selected').val() != "*" && $("#major2").find('option:selected').val() != "*" && $("#major3").find('option:selected').val() == "*") {
				if (flag == 1) {
					sql += " AND ";
				}
				
				var flag1 = 0;
				$("#major3 option[value!='*']").each(function () {
					temp = $(this).text();
					if (flag1 == 1) {
						sql += " OR ";
					}
					sql += "Teacher_Major='" + temp + "'";
					flag1 = 1;
				})
				flag = 1;
			}
			else if ($("#major1").find('option:selected').val() != "*" && $("#major2").find('option:selected').val() == "*" && $("#major3").find('option:selected').val() == "*") {
				if (flag == 1) {
					sql += " AND ";
				}
				var major = $("#major1").find('option:selected').val();
				
				sql += "Major_Code LIKE '" + major + "____%'";
				flag = 1;
			}
		}
		
		if ($("#title").find('option:selected').val()) {
			title = $("#title").find('option:selected').val();
			if (title != "*") {
				if (flag == 1) {
					sql += " AND ";
				}
				sql += "Teacher_Title='" + title + "'";
				flag = 1;
			}
		}
		
		if (flag == 0) {
			sql = "SELECT * FROM Teacher";
		}
		
		$('#divTemp').load('<?php echo $base_url;?>teacher/advanced_search', {"sql":sql});
	})
})

function replace_search(data) {
	var selected = data;
	document.getElementById("show").innerHTML = selected;
}
