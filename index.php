<?php

require_once('config.inc.php'); // 配置文件
require_once('dbmodel.php');    // 数据库模型
require_once('utils.php');		// 辅助函数

function OnActionView(){
	global $user,$data,$db,$u_pid,$u_pid,$u_aid;
	if ($u_pid > -1){
		$data['post'] = $db->GetPost($u_pid);
		if (is_null($data['post']))
			show404();
		$data['articles'] = $db->GetArticlesSummary($u_pid);
		if (is_null($data['articles']))
			show404(); //施工中
		if ($u_aid == -1) //未设置第几篇则默认为第一篇
			$u_aid = $data['articles'][0]->aid;
		$data['current_article'] = $db->GetArticleDetail($u_pid, $u_aid);
		if (is_null($data['current_article']))
			show404();
		ShowTemplatePage("article");
	} else {
		$data['posts'] = $db->GetPosts();
		ShowTemplatePage("index");
	}
}

function OnActionEdit(){
	global $user,$data,$db,$u_pid,$u_pid,$u_aid;
	ShowTemplatePage("editor");
}

function OnActionSearch(){
	global $user,$data,$db,$u_pid,$u_pid,$u_aid;
	ShowTemplatePage("search");
}

function OnActionLogin(){
	global $user,$data,$db;
	if ($_SERVER['REQUEST_METHOD'] == 'GET'){
		ShowTemplatePage("login");
	} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$email = GetStrParam('email', $_POST, MIN_EMAIL_LENGTH, MAX_EMAIL_LENGTH);
		$password = GetStrParam('password', $_POST, MIN_PASSWORD_LENGTH, MAX_PASSWORD_LENGTH);
		if (strlen($email) > 0 && strlen($password) > 0)
			$ret['ret'] = $user->Login($email, $password);
		else {
			$ret['ret'] = false;
		}
		_json_encode($ret);
	}
}

function OnActionLogout(){
	global $user,$data,$db;
	$ret['ret'] = $user->Logout();
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		_json_encode($ret);	
	} else {
		header("Location: /");
	}
}

/*=============== main start ===============*/
$data;
$user;

$db = new DataManager();
if ( ! $db->IsConnected())
	show404();

$user = new User($db);
$u_act = GetStrParam("act", $_GET);
$u_pid = GetIntParam("pid", $_GET);
$u_aid = GetIntParam("aid", $_GET);

switch ($u_act){
case 'search':
	OnActionSearch();
	break;	
case 'edit':
	if ($user->IsLogined())
		OnActionEdit();
	else
		OnActionView();
	break;
case 'login':
	OnActionLogin();
	break;
case 'logout':
	OnActionLogout();
	break;
default:
	OnActionView();
	break;
}

?>