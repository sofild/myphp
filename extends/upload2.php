<?php
error_reporting("E_ALL & ~E_NOTICE");
include("../conf/para.php");
$action = $_GET['act']; 
$img_dir = ROOT.'public/attached/video/';
$dir_name = YMD;
$img_dir .= $dir_name;

if(!is_dir($img_dir)){
	mkdir($img_dir);
}
$img_dir .= '/';

if($action=='delimg'){ //删除附件 
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
        if ($picsize > 51200000) { //限制上传大小 
            echo '文件大小不能超过50M'; 
            exit; 
        } 
        $type = strstr($picname, '.'); //限制上传格式 
        if ($type != ".flv") { 
            echo '文件格式不对！'; 
            exit; 
        } 
        $rand = rand(100, 999); 
        $pics = date("YmdHis") . $rand . $type; //命名图片名称 
        //上传路径 
        $pic_path = $img_dir.$pics; 
        move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
		
		$my_path = MY_SITE."/public/attached/video/".$dir_name."/".$pics; 
    } 
    $size = round($picsize/1024,2); //转换成kb 
    $arr = array( 
        'path'=>$my_path,
        'video'=>$pics, 
        'size'=>$size
    ); 
    echo json_encode($arr); //输出json数据 
} 
?>