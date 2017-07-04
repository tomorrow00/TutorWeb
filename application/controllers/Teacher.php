<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller{
	public $base_url;

	//构造函数
	public function _construct()
	{
		parent::__construct();
		$this->base_url = $this->config->item('base_url');
	}

	//显示用户信息列表
	public function index()
	{
		$this->load->view('teacher', array('base_url' => $this->base_url, 'teacherScroll' => $teacherScroll));
	}

	//显示搜索结果
	public function regular_search()
	{
//		print microtime(true);
//		print "\n";
		$txt = trim($_GET['txt']);
		$sv = trim($_GET['sv']);
	   
		if ($sv == "*" && $txt != "") {
			$sql = "SELECT * FROM Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code WHERE concat(ifnull(`Teacher_Name`,''),ifnull(`Teacher_Sex`,''),ifnull(`Teacher_Unit`,''),ifnull(`Teacher_ProTitle`,''),ifnull(`Teacher_Duty`,''),ifnull(`Major_Name`,''),ifnull(`Teacher_Dir`,''),ifnull(`Teacher_Title`,'')) LIKE N'%".$txt."%'";
			
		}
		elseif ($sv == "*" && $txt == "") {
			$sql = "SELECT * FROM Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code";
		}
		else {
			$sql = "SELECT * FROM Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code WHERE ".$sv." LIKE N'%".$txt."%'";
		}

		$this->load->model('Model');
		$resultCount = $this->Model->search_num($sql);

		if($resultCount > 0) {
			$pageSize = 10;
			$pageNow = $_GET['page'];
			$pageCount = ceil($resultCount / $pageSize);
			$pre = ($pageNow - 1) * $pageSize;
			
			$sql.=" LIMIT ".$pre.", ".$pageSize;
			$teacherList = $this->Model->search_db($sql);
			
			$sql_searchmajor = "SELECT * FROM Major";
			$this->load->model('Model');
			$major_result = $this->Model->search_db($sql_searchmajor);
			$count = count($major_result);
			
			foreach ($teacherList as $item):
				$id = $item->Teacher_ID;
				$add = "UPDATE Teacher SET SearchTimes=SearchTimes+1 WHERE Teacher_ID='".$id."'"; 
				$this->load->model('Model');
				$this->Model->addsearchtimes($add);
				
				$major = $item->Major_Code;
				if(strlen($major) >= 5){
					$item->Major2 = $item->Major_Name;					//二级学科
//					$sql_searchmajor = "SELECT Major_Name FROM Major WHERE Major_Code='".$item->Major_RelatedID."'";
//					$this->load->model('Model');
//					$major_result =  $this->Model->search_db($sql_searchmajor);
					
					$i = 0;
					for ($i; $i <= $count; $i ++) {
						if (isset($major_result[$i]->Major_Code) && isset($item->Major_RelatedID) && ($major_result[$i]->Major_Code == $item->Major_RelatedID)) {
							$item->Major1 = $major_result[$i]->Major_Name;
						}
					}
//					$item->Major1 = $major_result[0]->Major_Name;
				}
				
				else {													//一级学科
					$item->Major1 = $item->Major_Name;
				}
				
				endforeach;
			
			$this->load->view('teacher', array('base_url' => $this->base_url, 'teacherList' => $teacherList, 'pageCount' => $pageCount, 'pageNow' => $pageNow));
			
//			print microtime(true);
			/*$json=array(
				'flag'=>1,
				'teacherList'=>$teacherList,
				);
			echo json_encode($json);*/
		}
		else {
			/*$json=array(
				'flag'=>0,
				);
			echo json_encode($json);*/
			//echo '<script type="text/javascript">alert("No result!");history.back();</script>';
			$this->load->view('error',  array('base_url' => $this->base_url));
		}
	}

	public function advanced_search()
	{
		$sql = trim($_GET['sql']);
		
		$this->load->model('Model');
		$resultCount = $this->Model->search_num($sql);
		
		if($resultCount > 0) {
			$pageSize = 10;
			$pageNow = $_GET['page'];
			$pageCount = ceil($resultCount / $pageSize);
			$pre = ($pageNow - 1) * $pageSize;
			
			$sql.=" LIMIT ".$pre.", ".$pageSize;
			$teacherList = $this->Model->search_db($sql);
			
			$sql_searchmajor = "SELECT * FROM Major";
			$this->load->model('Model');
			$major_result = $this->Model->search_db($sql_searchmajor);
			$count = count($major_result);
			
			foreach ($teacherList as $item):
				$id = $item->Teacher_ID;
				$add = "UPDATE Teacher SET SearchTimes=SearchTimes+1 WHERE Teacher_ID='".$id."'"; 
				$this->load->model('Model');
				$this->Model->addsearchtimes($add);
				
				$major = $item->Major_Code;
				if(strlen($major) >= 5){
					$item->Major2 = $item->Major_Name;					//二级学科
//					$sql_searchmajor = "SELECT Major_Name FROM Major WHERE Major_Code='".$item->Major_RelatedID."'";
//					$this->load->model('Model');
//					$major_result =  $this->Model->search_db($sql_searchmajor);
					
					$i = 0;
					for ($i; $i <= $count; $i ++) {
						if (isset($major_result[$i]->Major_Code) && isset($item->Major_RelatedID) && ($major_result[$i]->Major_Code == $item->Major_RelatedID)) {
							$item->Major1 = $major_result[$i]->Major_Name;
						}
					}
//					$item->Major1 = $major_result[0]->Major_Name;
				}
				
				else {													//一级学科
					$item->Major1 = $item->Major_Name;
				}
				
				endforeach;
			
			$this->load->view('teacher', array('base_url' => $this->base_url, 'teacherList' => $teacherList, 'pageCount' => $pageCount, 'pageNow' => $pageNow));
			
//			print microtime(true);
			/*$json=array(
				'flag'=>1,
				'teacherList'=>$teacherList,
				);
			echo json_encode($json);*/
		}
		else {
			/*$json=array(
				'flag'=>0,
				);
			echo json_encode($json);*/
			//echo '<script type="text/javascript">alert("No result!");history.back();</script>';
			$this->load->view('error',  array('base_url' => $this->base_url));
		}
	}

	public function search_major()
	{
		$code = trim($_POST['code']);

		$sql = "SELECT Major_Code,Major_Name FROM Major WHERE Major_RelatedID='".$code."';";

		$this->load->model('Model');
		$majorList = $this->Model->search_db($sql);

		$json = array('majorList'=>$majorList);
		echo json_encode($json);
	}
}
?>
