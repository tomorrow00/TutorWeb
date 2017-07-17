<?php
class Model extends CI_Model{
	//构造函数
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	//搜索数据库
	public function search_db($sql)
	{
		$result = $this->db->query($sql);
		
		if($result->num_rows() > 0){
			return $result->result();
		}
		else{
			return null;
		}
	}
	
	//搜索条数
	public function search_num($sql)
	{
		$result = $this->db->query($sql);
		
		if($result->num_rows()){
			return $result->num_rows();
		}
		else{
			return null;
		}
	}
	
	//增加搜索次数
	public function execute_db($sql)
	{
		$this->db->query($sql);
	}
}?>
