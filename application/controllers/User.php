<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	//构造函数
	public function __construct()
	{
		parent::__construct();
		$this->base_url = $this->config->item('site_url');
		session_start();
		$this->load->model('Model');
	}
	
	public function index()
	{
		if (isset($_SESSION['usr'])) {
			$this->load->view('information', array('base_url' => $this->base_url));
		}
		else {
			$this->load->view('login', array('base_url' => $this->base_url));
		}
	}
	
	public function information()
	{
		$this->load->view('information', array('base_url' => $this->base_url));
	}
	
	public function collect() {
		$teacherID = trim($_POST['teacherID']);
		$userID = $_SESSION['id'];
		
		$sql_search = "SELECT * FROM Collection WHERE C_User_ID=".$userID." AND C_Teacher_ID=".$teacherID;
		$num = $this->Model->search_num($sql_search);
		
		if (isset($num)) {
			$sql_update = "UPDATE Collection SET Collected=1 WHERE C_User_ID=".$userID." AND C_Teacher_ID=".$teacherID;
			$this->Model->execute_db($sql_update);
		}
		else {
			$sql_insert = "INSERT INTO Collection(C_User_ID, C_Teacher_ID, Collected) VALUES (".$userID.",".$teacherID.",1)";
			$this->Model->execute_db($sql_insert);
		}
		
		echo true;
	}
	
	public function cancel_collect() {
		$teacherID = trim($_POST['teacherID']);
		$userID = $_SESSION['id'];
		
		$sql_delete = "UPDATE Collection SET Collected=0 WHERE C_User_ID=".$userID." AND C_Teacher_ID=".$teacherID;
		$this->Model->execute_db($sql_delete);
		
		echo true;
	}
	
	public function collection()
	{
		$sql = "SELECT * FROM (Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code) inner join Collection as c on a.Teacher_ID=c.C_Teacher_ID WHERE C_User_ID=".$_SESSION['id']." AND Collected=1";
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
			
			$this->load->view('collection', array('base_url' => $this->base_url, 'teacherList' => $teacherList, 'pageCount' => $pageCount, 'pageNow' => $pageNow));
		}
		else {
			$this->load->view('collection',  array('base_url' => $this->base_url));
		}
	}
	
	public function app()
	{
		$this->load->view('app', array('base_url' => $this->base_url));
	}
}
?>
