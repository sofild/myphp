<?php
error_reporting("E_ALL & ~E_NOTICE");
include("../conf/para.php");
include("image.class.php");
$action = $_GET['act']; 
//$cc = $_GET["cc"]?$_GET["cc"]:'';
$cc = $_POST["cc"]?$_POST["cc"]:'';
$img_dir = ROOT.'public/attached/image/';
$dir_name = YMD;
$img_dir .= $dir_name;

if(!is_dir($img_dir)){
	mkdir($img_dir);
}
$img_dir .= '/';

if($action=='delimg'){ //删除图片 
    $filename = $_POST['imagename']; 
    if(!empty($filename)){ 
        unlink($img_dir.$filename); 
        echo '1'; 
    }else{ 
        echo '删除失败.'; 
    } 
}else{ //上传图片 
    $picname = $_FILES['mypic']['name']; 
    $picsize = $_FILES['mypic']['size']; 
    if ($picname != "") { 
        if ($picsize > 512000) { //限制上传大小 
            echo '图片大小不能超过500k'; 
            exit; 
        } 
        $type = strstr($picname, '.'); //限制上传格式 
        if ($type != ".gif" && $type != ".jpg" && $type != ".jpeg" && $type != ".png" && $type != ".bpm") { 
            echo '图片格式不对！'; 
            exit; 
        } 
        $rand = rand(100, 999); 
        $pics = date("YmdHis") . $rand . $type; //命名图片名称 
        //上传路径 
        $pic_path = $img_dir.$pics; 
        move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
		
		if($cc!=''){
			$tag = 1;
			$simg_path = $img_dir."s_".$pics;
			$arr = explode("x",$cc);
			$width = intval($arr["0"]);
			$height = intval($arr["1"]);
			
			$now_pic = new resizeimage($pic_path,$width,$height,0,$simg_path);
			$s_path = MY_SITE."/public/attached/image/".$dir_name."/s_".$pics;
		}
		else{
			$s_path = MY_SITE."/public/attached/image/".$dir_name."/".$pics;
		}
		$my_path = MY_SITE."/public/attached/image/".$dir_name."/".$pics; 
    } 
    $size = round($picsize/1024,2); //转换成kb 
    $arr = array( 
        'orgimg'=>$my_path,
		'simg'=> $s_path,
        'pic'=>$pics, 
        'size'=>$size 
    ); 
    echo json_encode($arr); //输出json数据 
} 
?>