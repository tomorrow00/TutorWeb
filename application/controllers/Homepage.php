<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homepage extends CI_Controller {
	public $base_url;
	//构造函数
	public function _construct()
	{
		parent::_construct();
		$this->base_url = $this->config->item('base_url');
	}
	
	public function index()
	{
		$this->load->model('Model');
		$sql = "SELECT * FROM Teacher as a left join Major as b on a.Teacher_Major=b.Major_Code ORDER BY SearchTimes DESC LIMIT 5";
    	$teacherScroll = $this->Model->search_db($sql);
    	
    	$this->load->view('homepage', array('base_url' => $this->base_url, 'teacherScroll' => $teacherScroll));
	}
}
?>
