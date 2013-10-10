<?php
	class action{
		function __construct(){
						
		}
		
		function get($name,$default=''){
			$value = trim($_GET[$name]);
			$value = addslashes(htmlspecialchars($value));
			$value = $value?$value:$default;
			return $value;
		}
		
		function post($name,$default='',$tag=1){
			$value = trim($_POST[$name]);
			if($tag==1){
				$value = addslashes(htmlspecialchars($value));
			}
			$value = $value?$value:$default;
			return $value;
		}
		
		function request($name){
			$value = $this->post($name)?$this->post($name):$this->get($name);
			return $value;
		}
		
		function grade($cando){
			$cando = intval($cando);
			$nowdo = intval($_SESSION["grade"]);
			if($cando<=$nowdo){
				msg("您无权限操作此模块！");
			}
		}
		
		function between($field,$t1,$t2){
			$sql = "";
			if($t1!='' && $t2!=''){
				$sql .= "(".$field." between '".$t1."' and '".$t2."')";
			}
			elseif($t1!='' && $t2==''){
				$sql .= "(".$field.">'".$t1."')";
			}
			elseif($t1=='' && $t2!=''){
				$sql .= "(".$field."<'".$t2."')";
			}
			return $sql;
		}
		
		function success($action){
			$data["tag"]=1;
			$data["gotourl"] = $action;
			echo json_encode($data);
		}
		
		function error(){
			$data["tag"]=0;
			echo json_encode($data);
		}
								
		function gets(){
			$url = $_SERVER["PATH_INFO"];
			$exp = explode("/",$url);
			$count = count($exp);
			
			for($i=3;$i<$count;$i=$i+2){
				if(strpos($exp[$i+1],".")!==false){
					$t = explode(".",$exp[$i+1]);
					$test["".$exp[$i].""] = $t["0"];
				}
				else{
					$test["".$exp[$i].""] = $exp[$i+1];
				}
			}
			return $test;
		}
		
		function tryeat(){
			$hz_try_product = D("hz_try_products");
			$result = $hz_try_product->find('zizhu=0',"StartTime desc");
			$stime = strtotime($result["StartTime"]);
			$etime = strtotime($result["EndTime"]);
			if($stime>STAMPTIME && $etime>STAMPTIME){//活动还没开始
				$active = "2";
			}
			elseif($etime<STAMPTIME && $stime<STAMPTIME){//活动已结束
				$active = "0";
			}
			else{
				$active = "1";//活动进行中
			}
			$id = $result["ID"];
			$name = $result["Name"];//标题
			$descm = $result["Descm"];//描述
			$starttime = $result["StartTime"];//活动开始时间
			$endtime = $result["EndTime"];//活动结束时间
			$pic = $result["Pic"];//产品大图
			$picm = $result["Picm"];//产品小图
			$picb = $result["Picb"];//banner图
			$request = $result['Request'];//申请要求
			$other = $result['Other'];//其他福利
			$dx_time = $result['ResultTime'];//短信公布时间
			$bg_time = $result['ReportEndTime'];//报告最后提交时间
			$piece = $result['Piece'];//商品数量
			$desc = $result['Desc'];//商品详情
			$cate = $result["cate"];
			$age = $result["age"];
			$fitage = $result["fitage"];
			
			assign("tryid",$id);		
			assign("active",$active);
			assign("name",utfcode($name));
			assign("descm",utfcode($descm));
			assign("starttime",substr($starttime,0,10));
			assign("endtime",substr($endtime,0,10));
			assign("starttime_d",substr($starttime,5,5));
			assign("endtime_d",substr($endtime,5,5));
			assign("pic",$pic);
			assign("picm",$picm);
			assign("picb",$picb);
			assign("request",htmlspecialchars_decode(utfcode($request)));
			assign("other",htmlspecialchars_decode(utfcode($other)));
			assign("dx_time",substr($dx_time,0,10));
			assign("bg_time",substr($bg_time,0,10));
			assign("piece",$piece);
			assign("desc",htmlspecialchars_decode(utfcode($desc)));
			assign("cate",$cate);
			assign("age",$age);
			assign("fitage",utfcode($fitage));
			
			$hz_try_orders = D("hz_try_orders");
			$usercount = $hz_try_orders->counts("ProductID=".$id."");
			assign("usercount",$usercount);
			
			return $starttime;			
		}
		
		function score($scores,$uid,$desc='',$type=0){
			$hz_score_log = D("hz_score_log");
			$hzuser = D("hzUser","db4");
			
			$result = $hzuser->find("ID=".$uid."");
			$now_score = intval($result["integral"])+intval($scores);
			
			if($now_score<0){
				$now_score = 0;
			}
			$value = array("score"=>$scores,"userid"=>$uid,"`desc`"=>"".gbcode($desc)."","dateline"=>"".STAMPTIME."","type"=>$type);			
			$result1 = $hz_score_log->insert($value);
				
			$value = array("integral"=>$now_score);
			$result2 = $hzuser->update($value,"ID=".$uid."");
				
			if($result1 && $result2){
				return "1";
			}
			else{
				return "0";
			}
		}
		
		function datetime_diff($datetime,$tag='年')
		{ 
			$datetime = is_string($datetime) ? new DateTime($datetime) : $datetime; 
			$diff = date_create('now')->diff($datetime);
			$suffix = $diff->invert; //过去的时间返回true,未来的时间返回false
			$diff_str = ''; 
			$years      = $diff->y ? $diff->y . $tag : null; 
			$months     = $diff->m ? $diff->m . '个月' : null; 
			$days       = $diff->d ? $diff->d . '天' : null; 
			$hours      = $diff->h ? $diff->h . '小时' : null; 
			$minutes    = $diff->i ? $diff->i . '分钟' : null; 
			$seconds    = $diff->s ? $diff->s . '秒' : null; 
			
			if($years){ 
				$diff_str = $years . $months;
			}
			elseif($months){ 
				$diff_str = $months . $days;
			}
			elseif($days){ 
				$diff_str = $days . $hours;
			}
			elseif($hours){ 
				$diff_str = $hours . $minutes;
			}
			else{ 
				$diff_str = $minutes . $seconds;
			}
			$result[0] = $diff_str;
			$result[1] = $suffix;
			return $result;	 
		}
		
		/*获取宝宝年龄*/
		function zh_getage($datetime){
			$t1 = strtotime($datetime);
			$t2 = time();
			if($t1=='' || $t1==0){
				$m = '';
			}
			else{
				if($t1>$t2){
					$m = -1;
				}
				else{
					$t = intval($t2)-intval($t1);
					$m = ceil($t/3600/24/30);	
				}
			}
			return $m;			
		}
		
		function getage($birth){
			if($birth=='' || $birth=='0000-00-00'){
				$age = '不详';	
			}
			else{
				$m = $this->zh_getage($birth);
				if($m<=0){
					$age = '怀孕期';
				}
				else{
					$y = floor($m/12);
					if($y<=0){
						$age = $m."个月";
					}
					else{
						$mon = $m-$y*12;
						$age = $y."岁".$mon."个月";
					}
				}
			}
			return $age;
		}
		
		function replaceHtmlAndJs($document)
		{
			$document = trim($document);
			if (strlen($document) <= 0)
			{
			return $document;
			}
			$search = array ("'<script[^>]*?>.*?</script>'si", // 去掉 javascript
			"'<[/!]*?[^<>]*?>'si", // 去掉 HTML 标记
			"'([rn])[s]+'", // 去掉空白字符
			"'&(quot|#34);'i", // 替换 HTML 实体
			"'&(amp|#38);'i",
			"'&(lt|#60);'i",
			"'&(gt|#62);'i",
			"'&(nbsp|#160);'i"
			); // 作为 PHP 代码运行
			
			$replace = array ("",
			"",
			"\1",
			"&",
			"<",
			">",
			" "
			);
			
			return @preg_replace ($search, $replace, $document);
		}
		
		/*获取用户名*/
		function getuname(){
			$gets = $this->gets();
			$cuid = $gets["u"];//参数传递的uid
			$suid = $_SESSION["userid"];//session传递的uid
			
			if($suid=='' && $cuid!=''){//没有登录，访问别人空间
				$uid = $cuid;
				$state = 0;
			}
			elseif(($suid!='' && $cuid!='' && ($suid==$cuid)) || ($suid!='' && $cuid=='')){//已登录，在自己的空间
				$uid = $suid;
				$state = 1;
			}
			elseif($suid!='' && $cuid!='' && ($suid!=$cuid)){//已登录，访问别人的空间
				$uid = $cuid;
				$state = 2;
			}
						
			if($uid==''){
				echo "<script language='javascript'>alert('请勿非法访问！'); window.location.href='".MY_SITE."'; </script>";
				exit();
			}
			else{
				//$result = $hzUser->find("ID=".$uid."");	
				$result = E("select ID,Account,handset,ProvinceName,CountyName,integral,UserFace,totalview,logincount from hzUser where ID=".$uid." limit 0,1");
				if(!empty($result)){
					$result = arrconv($result);
					foreach($result as $k=>$v){
						$data["ID"] = $v["ID"];
						$data["Account"] = $v["Account"];
						$data["handset"] = $v["handset"];
						$data["ProvinceName"] = $v["ProvinceName"];
						$data["CountyName"] = $v["CountyName"];
						$data["integral"] = $v["integral"];
						$data["UserFace"] = $v["UserFace"];
						$data["totalview"] = $v["totalview"];
						$data["logincount"] = $v["logincount"];
						$data["state"] = $state;
					}
				}
				return $data;
			}
		}
		
		function gonggao(){
			$hz_gonggao = D("hz_gonggao");
			$field = "id,content,url";
			$where = "url!=''"; 
			$order = "dateline desc";
			$limit = "0,5";
			
			$result = $hz_gonggao->select($field,$where,'',$order,$limit);
			if(!empty($result)){
				$result = arrconv($result);
			}
			assign("list_gonggao",$result);	
		}
		
		// Cookie 设置、获取、删除
		function cookie($name, $value='',$time=2592000, $option=null) {
			// 默认设置
			$config = array(
				'prefix' => '', // cookie 名称前缀
				'expire' => $time, // cookie 保存时间
				'path' => '/', // cookie 保存路径
				'domain' => '.heinz.com.cn', // cookie 有效域名
			);
			// 参数设置(会覆盖黙认设置)
			if (!empty($option)) {
				if (is_numeric($option))
					$option = array('expire' => $option);
				elseif (is_string($option))
					parse_str($option, $option);
				$config = array_merge($config, array_change_key_case($option));
			}
			// 清除指定前缀的所有cookie
			if (is_null($name)) {
				if (empty($_COOKIE))
					return;
				// 要删除的cookie前缀，不指定则删除config设置的指定前缀
				$prefix = empty($value) ? $config['prefix'] : $value;
				if (!empty($prefix)) {// 如果前缀为空字符串将不作处理直接返回
					foreach ($_COOKIE as $key => $val) {
						if (0 === stripos($key, $prefix)) {
							setcookie($key, '', time() - 3600, $config['path'], $config['domain']);
							unset($_COOKIE[$key]);
						}
					}
				}
				return;
			}
			$name = $config['prefix'] . $name;
			if ('' === $value) {
				return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null; // 获取指定Cookie
			} else {
				if (is_null($value)) {
					setcookie($name, '', time() - 3600, $config['path'], $config['domain']);
					unset($_COOKIE[$name]); // 删除指定cookie
				} else {
					// 设置cookie
					$expire = !empty($config['expire']) ? time() + intval($config['expire']) : 0;
					setcookie($name, $value, $expire, $config['path'], $config['domain']);
					$_COOKIE[$name] = $value;
				}
			}
		}
		
		function synclogin($hz_username,$hz_password){
			$hz_username = $this->decrypt($hz_username);
			$hz_password = $this->decrypt($hz_password);			
			if($hz_username!='' && $hz_password!=''){
				$hzUser = D("hzUser","db4");
				$where0 = "(Account = '".$hz_username."' or Email='".$hz_username."' or handset='".$hz_username."') and Password='".$hz_password."'";
				$field0 = "ID,Account,integral,UserFace,BabyBirthday";
				$result0 = $hzUser->findf($where0,$field0);
				if(!empty($result0)){
					$result0 = arrconv($result0);
					$id = $result0["ID"];
					$account = $result0["Account"];
					$integral = $result0["integral"];
					$userface = $result0["UserFace"];
					$bbirth = $result0["BabyBirthday"];
					
					$_SESSION["userid"] = $id;
					$_SESSION["uname"] = $account;
					$_SESSION["score"] = $integral;
					$_SESSION["uface"] = $userface;
					$_SESSION["bbirth"] = $bbirth;
					
					$value = array("lasttime"=>WHOLETIME,"lastip"=>$_SERVER["REMOTE_ADDR"],"logincount"=>logincount+1);
					$where = "ID=".$id."";
					$hzUser->update($value,$where);
					
					$hz_score_log = D("hz_score_log");
					$ltime = strtotime("".YMD." 00:00:00");
					$btime = strtotime("".YMD." 23:59:59");
					$res = $hz_score_log->find("userid=".$id." and type=3 and (dateline between ".$ltime." and ".$btime.")");
					if(empty($res)){
						$sc = $this->score("5",$id,"每日登录","3");
						$integral+=5;	
						$_SESSION["score"] = $integral;			
					}
					$this->loginlog($id);
				}
			}
		}
		
		function printageqj($bbirth){
			$age = $this->zh_getage($bbirth);
			$qj = '';
			if($age==''){
				$qj = '无宝宝';	
			}
			elseif($age<0){
				$qj = '怀孕中';
			}
			elseif($age>=0 && $age<6){
				$qj = '0-6个月';
			}
			elseif($age>=6 && $age<12){
				$qj = '6-12个月';
			}
			elseif($age>=12 && $age<24){
				$qj = '12-24个月';
			}
			elseif($age>=24 && $age<36){
				$qj = '12-24个月';
			}
			elseif($age>=36){
				$qj = '大于36个月';
			}
			else{
				$qj = '无宝宝';
			}
			return ''.$qj.'';			
		}
		
		function encrypt($yawen,$key='BEA00682C7F1A1E251CCB67A28899161'){
			$crypt = base64_encode(base64_encode($key).base64_encode($yawen));
			return $crypt;
		}
			
		function decrypt($miwen,$key='BEA00682C7F1A1E251CCB67A28899161'){
			$j1 = base64_decode($miwen);
			$km = base64_encode($key);
			$arr = explode($km,$j1);
			$j2 = $arr[1];
			$j3 = base64_decode($j2);
			return $j3;
		}
		
	}
