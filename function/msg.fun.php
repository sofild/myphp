<?php
	function msg($content,$href='',$js=''){
		if($href=="0" && $js!=''){
			echo "<script language='javascript'>alert('".$content."'); ".$js.";</script>";
		}
		elseif($href==''){
			echo "<script language='javascript'>alert('".$content."'); history.go(-1); </script>";
		}
		else{
			echo "<script language='javascript'>alert('".$content."');window.location.href='".$href."'</script>";
		}
	}

	function smsg($title,$content,$href=''){
		echo "<script language='javascript' src='".MY_PATH."public/alert.js'></script>";	
		echo "<script language='javascript'>sAlert(".$title.",".$content.");</script>";
		if($href!=''){		
			header("Location:".$href."");
		}
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
			$u = explode($url,"/");
			header("Location:?c=".$u["0"]."&a=".$u["1"]."");
		}
	}
	
	function jump($url){
		if(!strstr($url,'/')){
			$url = $_SERVER["PATH_INFO"];
			$exp = explode("/",$url);
			$c = $exp["1"];
			if($c==''){
				$c = "index";
			}
			header("Location:".MY_SITE."/".$c."/".$url."");
		}
		else{
			$u = explode($url,"/");
			header("Location:".MY_SITE."/".$u["0"]."/".$u["1"]."");
		}

	}

	
	
	