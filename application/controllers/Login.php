<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	//构造函数
	public function __construct()
	{
		parent::__construct();
		$this->base_url = $this->config->item('site_url');
		$this->load->model('Model');
		$this->load->library('email');
		$this->load->helper('captcha');
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
		
		$sql = "SELECT * FROM User WHERE User_Type='".$type."' AND User_Name='".$usr."' OR User_Email='".$usr."'";
    	$userList = $this->Model->search_db($sql);
    	
    	if($userList[0]) {
    		if(password_verify($pwd, $userList[0]->User_Password)) {
				$_SESSION['usr'] = $userList[0]->User_Name;
				$_SESSION['id'] = $userList[0]->User_ID;
				
    			$json=array(
					'session_usr' => $_SESSION['usr'],
					'flag' => 0,
					'data' => $userList[0],
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
		$pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
		
		if (trim($_POST['email'] != null)) {
			$email = trim($_POST['email']);
			$sql_search = "SELECT User_ID FROM TeacherSchema.User WHERE User_Name='".$usr."' OR User_Email='".$email."'";
			
			if ($this->Model->search_num($sql_search) == 0) {
				$code_std = rand(100000, 999999);
				$_SESSION['code'] = $code_std;
				$_SESSION['expiretime'] = date("H:i:s", strtotime("+3 minutes"));
				
				$email_message = "亲爱的".$usr."，您好：<br/><br/>感谢您注册导师网，您的验证码是".$code_std."。<br/><br/>请在3分钟内输入验证码，过期无效。<br/><br/>导师网";
				$this->email->initialize();
				
				$this->email->from('tutor_web@163.com', "导师网");
				$this->email->to($email);
				$this->email->subject("账户验证邮件");
				$this->email->message($email_message);
				$this->email->send();
				
				$json = array(
					'email' => 1,
					'flag' => 1,
					'time' => $_SESSION['expiretime']
				);
				echo json_encode($json);
			}
			else {
				$json = array(
					'email' => 1,
					'flag' => 0 
				);
				echo json_encode($json);
			}
		}
		
		else {
			$sql_search = "SELECT User_ID FROM TeacherSchema.User WHERE User_Name='".$usr."'";
			if ($this->Model->search_num($sql_search) == 0) {
	//			$sql_register = "INSERT INTO User(User_Name, User_Password, User_Type) VALUES('".$usr."', '".$pwd."', 'user')";
	//			$this->Model->execute_db($sql_register);
				$json = array(
					'email' => 0,
					'flag' => 1
				);
				echo json_encode($json);
			}
			else {
				$json = array(
					'email' => 0,
					'flag' => 0 
				);
				echo json_encode($json);
			}
		}
	}
	
	//验证码
	public function check_code()
	{
		$code = trim($_POST['code']);
		$usr = trim($_POST['usr']);
		$pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
		$email = trim($_POST['email']);
		
		if (date("H:i:s") > $_SESSION['expiretime']) {
			unset($_SESSION['code']);
			
			$json = array(
				'flag' => 3
			);
			echo json_encode($json);
		}
		elseif ($code != '' && isset($_SESSION['code'])) {
			if ($code == $_SESSION['code']) {
				$sql_search = "SELECT Teacher_Name FROM Teacher WHERE Teacher_Tel LIKE '%".$email."%'";
				$names = $this->Model->search_db($sql_search);
				
				if (count($names) != 0) {
					$name = $names[0]->Teacher_Name;
					$sql_register = "INSERT INTO User(User_Name, User_RealName, User_Password, User_Email, User_Type) VALUES('".$usr."', '".$name."', '".$pwd."', '".$email."', 'user')";
				}
				else {
					$sql_register = "INSERT INTO User(User_Name, User_Password, User_Email, User_Type) VALUES('".$usr."', '".$pwd."', '".$email."', 'user')";
				}
				$this->Model->execute_db($sql_register);
				
				unset($_SESSION['code']);
				$json = array(
					'flag' => 1
				);
				echo json_encode($json);
			}
			else {
				$json = array(
					'flag' => 2
				);
				echo json_encode($json);
			}
		}
		elseif ($code == '') {
			$json = array(
				'flag' => 0
			);
			echo json_encode($json);
		}
		elseif (!isset($_SESSION['code'])) {
			$json = array(
				'flag' => 2
			);
			echo json_encode($json);
		}
	}
}
?> 
