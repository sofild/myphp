<?php
	!defined('MY_SITE') && exit('Access Denied!');
	class route{
		private $url;
		public function __construct($url){
			$this->url = $url;		
		}
		
		function getUrl(){
			if(!isset($_SERVER["PATH_INFO"])){
				$control = $_GET["c"]?$_GET["c"]:"index";		
				$action = $_GET["a"]?$_GET["a"]:"index";
			}
			else{
				$url = $_SERVER["PATH_INFO"];
				$exp = explode("/",$url);				
				$control = $exp["1"]?$exp["1"]:"index";				
				$action = $exp["2"]?$exp["2"]:"index";
				if(strpos($control,".")!==false){
					$co = explode(".",$control);
					$control = $co["0"]?$co["0"]:"index";
				}
				
				if(strpos($action,".")!==false){
					$ac = explode(".",$action);
					$action = $ac["0"];
				}
			}
			
			$class_file = "./action/".$control.".class.php";
			$class = $control."Action";
			$fun = $action;
			
			if(file_exists($class_file)){
				include($class_file);
				if(class_exists($class)){
					$classname = new $class();
					if(method_exists($class,$fun)){
						$classname->$fun();
					}
					else{
						//logs("".$class_file."中，".$class."类的".$fun."方法找不到！");
						msg("此页面不存在！",MY_SITE);
					}
				}
				else{
					//logs($class_file."中，".$class."类找不到！");
					msg("此页面不存在！",MY_SITE);
				}
			}
			else{
				//logs($class_file."文件不存在！");
				msg("此页面不存在！",MY_SITE);
			}
		}
		
	}