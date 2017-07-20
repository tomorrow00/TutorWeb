<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	//构造函数
	public function __construct()
	{
		parent::__construct();
		$this->base_url = $this->config->item('site_url');
		$this->load->model('Model');
		session_start();
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
	public function check_login() 
	{
		$usr = trim($_POST['usr']);
		$pwd = trim($_POST['pwd']);
		$type = trim($_POST['type']);
		
		$sql = "SELECT * FROM User WHERE User_Type='".$type."' AND User_Name='".$usr."'";
    	$userList = $this->Model->search_db($sql);
    	
    	if($userList[0]) {
    		if($pwd == $userList[0]->User_Password) {
				$_SESSION['usr'] = $userList[0]->User_Name;
				$_SESSION['id'] = $userList[0]->User_ID;
				
    			$json=array(
					'session_usr' => $_SESSION['usr'],
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
	
	//注销帐号
	public function logout()
	{
		unset($_SESSION['usr']);
		unset($_SESSION['id']);
	}
	
	//注册账户
	public function registerin()
	{
		$usr = trim($_POST['usr']);
		$pwd = trim($_POST['pwd']);
		
		$sql_search = "SELECT User_ID FROM TeacherSchema.User WHERE User_Name='".$usr."'";
		if ($this->Model->search_num($sql_search) == 0) {
			$sql_register = "INSERT INTO User(User_Name, User_Password, User_Type) VALUES('".$usr."', '".$pwd."', 'user')";
			$this->Model->execute_db($sql_register);
			echo 1;
		}
		else {
			echo 0;
		}
	}
}
?> 
