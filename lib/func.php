<?php
	require("".MY_PATH."/extends/smarty/Smarty.class.php");
	$smarty = new Smarty();
	
	function assign($content,$variable){
		$GLOBALS["smarty"]->assign($content,$variable);
		//$smarty = new Smarty();
		//$smarty->assign($content,$variable);
	}
	
	function regFun($tofun,$fromfun){
		$GLOBALS["smarty"]->registerPlugin("function",$tofun,$fromfun);
		//$smarty = new Smarty();
		//$smarty->registerPlugin("function",$tofun,$fromfun);
	}
	
	function display($filename){
		if(!isset($_SERVER["PATH_INFO"])){
			$class = $_GET["c"]?$_GET["c"]:"index";
		}
		else{
			$url = $_SERVER["PATH_INFO"];
			$exp = explode("/",$url);
			$class = $exp["1"]?$exp["1"]:"index";
			if(strpos($class,".")!==false){
				$co = explode(".",$class);
				$class = $co["0"]?$co["0"]:"index";
			}
		}
	
		$temp_file = "./template/".$class."/".$filename;
		if(file_exists($temp_file)){
			//$smarty = new Smarty();
			//$smarty->display($temp_file);
			$GLOBALS["smarty"]->display($temp_file);
		}
		else{
			logs("模板页：".$temp_file."找不到！");
			msg("您访问的页面不存在！",MY_SITE);
		}
	}
		
	function show($filename){
		if(!isset($_SERVER["PATH_INFO"])){
			$class = $_GET["c"]?$_GET["c"]:"index";
		}
		else{
			$url = $_SERVER["PATH_INFO"];
			$exp = explode("/",$url);
			$class = $exp["1"]?$exp["1"]:"index";
			if(strpos($class,".")!==false){
				$co = explode(".",$class);
				$class = $co["0"]?$co["0"]:"index";
			}
		}
		$temp_file = "./template/".$class."/".$filename;
		if(file_exists($temp_file)){
			//include_once($temp_file);
			return $temp_file;
		}
		else{
			logs("模板页：".$temp_file."找不到！");
			msg("您访问的页面不存在！",MY_SITE);
		}
	}
	
	function msg($content,$href='',$js=''){
		if($href!=''){
			echo "<script language='javascript'>alert('".$content."');window.location.href='".$href."'</script>";
			exit();
		}
		elseif($js!=''){
			echo "<script language='javascript'>alert('".$content."'); ".$js.";</script>";
			exit();
		}
		else{
			echo "<script language='javascript'>alert('".$content."'); history.go(-1); </script>";
			exit();
		}
	}
	
	function logs($content){
		if(!is_dir(MY_PATH."/err/".YM)){
			mkdir(MY_PATH."/err/".YM);
		}
		$logfile = "".MY_PATH."/err/".YM."/".YMD.".log";
		$fp = fopen($logfile,"a");
		fwrite($fp,"".WHOLETIME."\n".$content."\n\n");
		fclose($fp);
	}
	
	function D($table,$db="db",$code="utf8"){
		$mydb = new db($table,$GLOBALS["".$db.""],$code);
		return $mydb;
	}
	
	function E($sql,$db="db",$code="utf8"){
		$mydb = new db('',$GLOBALS["".$db.""],$code);
		return $mydb->exe($sql);
	}
	
	function go($url){
		if(!strstr($url,'/')){
			$c = $_GET["c"];
			if($c==''){
				$c = "index";
			}
			header("Location:?c=".$c."&a=".$url."");
		}
		else{
			$u = explode("/",$url);
			header("Location:?c=".$u["0"]."&a=".$u["1"]."");
		}
	}
	
	
	
	