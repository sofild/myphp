<?php
	error_reporting(E_ALL & ~E_NOTICE);
	header("Content-type:text/html; Charset=utf-8");
	session_start();
	
	$files = array(array("conf","para"),array("conf","db"),array("lib","mysql.class"),array("lib","route.class"),array("lib","action.class"),array("lib","page.class"),array("lib","func"),array("lib","smarty"));
	foreach($files as $v){
		$file = MY_PATH."/".implode("/",$v).".php";
		if(file_exists($file)){
			require_once("".$file."");
		}
		unset($file);
	}
	unset($files);
	
	$url = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$route = new route($url);
	$route->getUrl();