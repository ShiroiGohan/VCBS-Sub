<?php
/*
入口文件
 */
define('DEBUG',true); //主调试开关
require_once('config.php');
require_once('error_report.php');
function exception_error_transfer($severity,$msg,$file,$line)
{
	if (!(error_reporting() & $severity)) return;
	throw new ErrorException($msg,0,$severity,$file,$line);
}
//set_error_handler("exception_error_transfer");
function autoloader($class)
{
	$name=explode('_',$class);
	try{
	include_once './models/'.strtolower($name[0]).'.php';
	include_once './controllers/'.strtolower($name[0]).'.php';
	include_once './views/'.strtolower($name[0]).'.php';
	}
	catch (ErrorException $e)
	{
		error_report($e);
		die();
	}
}
spl_autoload_register('autoloader'); //自动加载
//TODO:获取URL做路由
if (!isset($_GET['action'])) //没有任何参数时调度到mainpage.php视图上
{
	$mp_m=new Mainpage_Model();
	$mp_c=new Mainpage_Controller($mp_m);
	$mp_v=new Mainpage_View($mp_m,$mp_c);		
	$mp_v->render();
}
else
{
	try{
		$modelname=$_GET['action']."_Model";
		$conname=$_GET['action']."_Controller";
		$viewname=$_GET['action']."_View";
		$page_m=new $modelname();
		$page_c=new $conname($page_m);
		$page_v=new $viewname($page_m,$page_c);
		$page_v->render();
	}
	catch(ErrorException $e)
	{
		
	}
}	
?>

