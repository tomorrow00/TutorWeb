<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller{
	//构造函数
	public function __construct()
	{
		parent::__construct();
		$this->base_url = $this->config->item('site_url');
		$this->load->model('Model');
		session_start();
	}

	//显示用户信息列表
	public function index()
	{
		$this->load->view('teacher', array('base_url' => $this->base_url, 'teacherScroll' => $teacherScroll));
	}

	//显示搜索结果
	public function regular_search()
	{
		$txt = trim($_GET['txt']);
		
		if ($txt != "") {
			$sql = "SELECT * FROM Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code WHERE concat(ifnull(`Teacher_Name`,''),ifnull(`Teacher_Sex`,''),ifnull(`Teacher_Unit`,''),ifnull(`Teacher_ProTitle`,''),ifnull(`Teacher_Duty`,''),ifnull(`Major_Name`,''),ifnull(`Teacher_Dir`,''),ifnull(`Teacher_Title`,'')) LIKE N'%".$txt."%'";
		}
		else {
			$sql = "SELECT * FROM Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code";
		}
		
		$resultCount = $this->Model->search_num($sql);

		if($resultCount > 0) {
			$pageSize = 10;
			$pageNow = $_GET['page'];
			$pageCount = ceil($resultCount / $pageSize);
			$pre = ($pageNow - 1) * $pageSize;
			
			$sql.=" ORDER BY a.SearchTimes DESC"." LIMIT ".$pre.", ".$pageSize;
			$teacherList = $this->Model->search_db($sql);
			
			$sql_searchmajor = "SELECT * FROM Major";
			$major_result = $this->Model->search_db($sql_searchmajor);
			$count = count($major_result);
			
			foreach ($teacherList as $item):
				$add_teacher = "UPDATE Teacher SET SearchTimes=SearchTimes+1 WHERE Teacher_ID='".$item->Teacher_ID."'";
				$add_major = "UPDATE Major SET SearchTimes=SearchTimes+1 WHERE Major_ID='".$item->Major_ID."'";
				$this->Model->execute_db($add_teacher);
				$this->Model->execute_db($add_major);
				
				$major = $item->Major_Code;
				if(strlen($major) >= 5){
					$item->Major2 = $item->Major_Name;					//二级学科
					
					$i = 0;
					for ($i; $i <= $count; $i ++) {
						if (isset($major_result[$i]->Major_Code) && isset($item->Major_RelatedID) && ($major_result[$i]->Major_Code == $item->Major_RelatedID)) {
							$item->Major1 = $major_result[$i]->Major_Name;
						}
					}
				}
				
				else {													//一级学科
					$item->Major1 = $item->Major_Name;
				}
				
				endforeach;
			
			$this->load->view('teacher', array('base_url' => $this->base_url, 'teacherList' => $teacherList, 'pageCount' => $pageCount, 'pageNow' => $pageNow));
		}
		else {
			$this->load->view('error',  array('base_url' => $this->base_url));
		}
	}

	public function advanced_search()
	{
		$sql = trim($_GET['sql']);
		
		$resultCount = $this->Model->search_num($sql);
		
		if($resultCount > 0) {
			$pageSize = 10;
			$pageNow = $_GET['page'];
			$pageCount = ceil($resultCount / $pageSize);
			$pre = ($pageNow - 1) * $pageSize;
			
			$sql.=" ORDER BY a.SearchTimes DESC"." LIMIT ".$pre.", ".$pageSize;
			$teacherList = $this->Model->search_db($sql);
			
			$sql_searchmajor = "SELECT * FROM Major";
			$major_result = $this->Model->search_db($sql_searchmajor);
			$count = count($major_result);
			
			foreach ($teacherList as $item):
				$add_teacher = "UPDATE Teacher SET SearchTimes=SearchTimes+1 WHERE Teacher_ID='".$item->Teacher_ID."'";
				$add_major = "UPDATE Major SET SearchTimes=SearchTimes+1 WHERE Major_ID='".$item->Major_ID."'";
				$this->Model->execute_db($add_teacher);
				$this->Model->execute_db($add_major);
				
				$major = $item->Major_Code;
				if(strlen($major) >= 5){
					$item->Major2 = $item->Major_Name;					//二级学科
					
					$i = 0;												//查找一级学科
					for ($i; $i <= $count; $i ++) {
						if (isset($major_result[$i]->Major_Code) && isset($item->Major_RelatedID) && ($major_result[$i]->Major_Code == $item->Major_RelatedID)) {
							$item->Major1 = $major_result[$i]->Major_Name;
						}
					}
				}
				
				else {													//一级学科
					$item->Major1 = $item->Major_Name;
				}
				
				endforeach;
			
			$this->load->view('teacher', array('base_url' => $this->base_url, 'teacherList' => $teacherList, 'pageCount' => $pageCount, 'pageNow' => $pageNow));
		}
		else {
			$this->load->view('error',  array('base_url' => $this->base_url));
		}
	}

	public function search_major()
	{
		$code = trim($_POST['code']);
		
		$sql = "SELECT Major_Code,Major_Name FROM Major WHERE Major_RelatedID='".$code."';";
		
		$majorList = $this->Model->search_db($sql);
		
		$json = array('majorList'=>$majorList);
		echo json_encode($json);
	}
}
?>
