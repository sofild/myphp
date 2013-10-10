<?php
	date_default_timezone_set("PRC");
	define('SITE','http://127.0.0.1:8087');
	define('MY_SITE','http://127.0.0.1:8087/webadmin');
	define('PUBLICS',MY_SITE.'/template/public');
	//define('PUBLICS','http://qszms.allyes.cn/qszms/heinz/v1/public');
	define('CURDIR',preg_replace("/\/[a-z0-9]+\.php.*/is", "", "http://". $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]));
	define('CURPAGE',"http://". $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
	define('ROOTDIR','/new_heinz/');
	define('ROOT',$_SERVER["DOCUMENT_ROOT"].ROOTDIR);
	define('IS_DEBUG','1');
	define('STAMPTIME',time());
	define('WHOLETIME',date('Y-m-d H:i:s',time()));
	define('YM',date('Y-m',time()));
	define('YMD',date('Y-m-d',time()));
	define('HIS',date('H:i:s',time()));
	define('IP',$_SERVER["REMOTE_ADDR"]);