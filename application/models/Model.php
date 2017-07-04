<?php
class Model extends CI_Model{
	//构造函数
	public function _construct()
	{
		parent::_construct();
	}
	
	//搜索数据库
	public function search_db($sql)
	{
		$this->load->database();
		$result = $this->db->query($sql);
		
		if($result->num_rows() > 0){
			return $result->result();
		}
		else{
			return null;
		}
	}
	
	//搜索一级或二级学科
//	public function search_db($sql)
//	{
//		$this->load->database();
//		$result = $this->db->query($sql);
//		
//		if($result->num_rows() > 0){
//			return $result->result();
//		}
//		else{
//			return null;
//		}
//	}
	
	//增加搜索次数
	public function addsearchtimes($add) {
		$this->load->database();
		$this->db->query($add);
	}
	
	//
	public function search_num($sql) {
		$this->load->database();
		$result = $this->db->query($sql);
		
		if($result->num_rows()){
			return $result->num_rows();
		}
		else{
			return null;
		}
	}
}?>
