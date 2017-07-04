<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public $base_url;
	
	//构造函数
	public function _construct()
	{
		parent::_construct();
		$this->base_url = $this->config->item('base_url');
	}
	
	//登录页面
	public function index()
	{
		$this->load->view('login', array('base_url' => $this->base_url));
	}
	
	//注册页面
	public function register() 
	{
		$this->load->view('register', array('base_url' => $this->base_url));
	}
	
	//验证帐号密码
	public function check() 
	{
		$usr = trim($_POST['usr']);
		$pwd = trim($_POST['pwd']);
		$type = trim($_POST['type']);
		
		$sql = "SELECT * FROM User WHERE User_Type='".$type."' AND User_Name='".$usr."'";
		$this->load->model('Model');
    	$userList = $this->Model->search_db($sql);
    	
    	if($userList[0]) {
    		if($pwd == $userList[0]->User_Password) {
    			$json=array(
    			'flag' => 0,
    			'data'=>$userList[0],
    			'msg' => '登录成功！'
    			);
    		}
    		else {
    			$json=array(
    			'flag' => 2,
    			'msg' => '密码错误！'
    			);
    		}
    	}
    	else {
    		$json=array(
    		'flag' => 1,
    		'msg' => '用户名错误！'
    		);
    	}
    	echo json_encode($json);
	}
}
?>
