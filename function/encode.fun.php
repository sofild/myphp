<?php
	include(MY_PATH."extends/ArrayiConv.php");
	/*
		从数据库取数据转换成utf-8编码
		存数据导数据库转成gb2312编码
	*/
	function utfcode($content){
		$content = iconv("gb2312","utf-8",$content);
		return $content;
	}
	
	function gbcode($content){
		$content = iconv("utf-8","gb2312//ignore",$content);
		return $content;
	}
	
	function arrconv($b,$t=1){
		if($t==1){
			$a = arrayiconv::Conversion($b,"gb2312","utf-8");    
		}
		else{
			$a = arrayiconv::Conversion($b,"utf-8","gb2312//ignore");
		}
		return $a;
	}