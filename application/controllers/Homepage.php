<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homepage extends CI_Controller {
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
		$sql_teacher = "SELECT * FROM Teacher ORDER BY SearchTimes DESC LIMIT 5";
		$sql_major = "SELECT * FROM Major ORDER BY SearchTimes DESC LIMIT 5";
		$teacherSearch = $this->Model->search_db($sql_teacher);
		$majorSearch = $this->Model->search_db($sql_major);
		
		$this->load->view('homepage', array('base_url' => $this->base_url, 'teacherSearch' => $teacherSearch, 'majorSearch' => $majorSearch));
	}
}
?>
