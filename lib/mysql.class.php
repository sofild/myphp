<?php
	!defined('MY_SITE') && exit('Access Denied!');
	class db{
		private $thedb,$table,$code;
		public function __construct($table,$thedb,$code){
			$this->thedb = $thedb;
			$this->table = $table;
			$this->code = $code;
		}
		
		function conect(){
			$thedb = $this->thedb;
			$code = $this->code;
			$conn = mysql_connect($thedb["host"],$thedb["user"],$thedb["pwd"]);
			if(!$conn){
				logs("数据库服务器连接失败，请检查服务器的地址、用户名和密码是否正确！");	
			}
			if(!mysql_select_db($thedb["dbname"])){
				logs("无法打开数据库，请检查数据库名是否正确，并确保数据库没有其他问题！");
			}

			mysql_query("set names ".$code."");	
			return $conn;
		}
		
		function query($sql){
			$conn = $this->conect();
			$result = mysql_query($sql,$conn);
			if(!$result){
				logs("".CURPAGE.":SQL语句执行失败：".mysql_error()."...sql:".$sql);
			}else{
				if(IS_DEBUG==1){
					logs("".CURPAGE.":成功执行SQL语句：".$sql);
				}
			}
			return $result;
		}
		
		function exe($sql){
			$result = $this->query($sql);
			$i=0;
			while($arr = mysql_fetch_array($result)){
				$i++;
				$res[$i] = $arr;
			}
			$this->free($result);
			$this->close($this->conect());
			return $res;
		}
		
		/**/
		function select($field='',$where='',$order='',$limit='',$group='',$return=1){
			$sql = '';
			
			/*field array('id','name','password')*/
			if($field!=''){
				if(is_array($field)){
					$field = implode(',',$field);
				}
				else{
					$field = $field;
				}
			}
			else{
				$field = '*';
			}
			$sql .= 'select '.$field.' from '.$this->table.'';
			
			/*where array('user'=>"1","name"=>"like %tt%")*/
			if($where!=''){
				if(is_array($where)){
					$wh .= $this->where($where);
				}
				else{
					$wh .= $where;
				}
				$sql .= ' where '.$wh.'';
			}
			
			/*group by  */
			if($group!=''){
				$sql .= ' group by '.$group;
			}
			
			/*order by array('id','time')*/
			if($order!=''){
				if(is_array($order)){
					$order = implode(',',$order);
				}
				else{
					$order = $order;
				}
				$sql .= ' order by '.$order.'';
			}
			
			/*limit array($nowpage,$perpage)*/
			if($limit!=''){
				$sql .= ' limit '.$limit;
			}
			$result = $this->query($sql);
			
			if($return==1){
				$i=0;
				while($arr = mysql_fetch_array($result)){
					$i++;
					$res[$i] = $arr;
				}
				$this->free($result);
				$this->close($this->conect());
				return $res;
			}
			else{
				$this->free($result);
				$this->close($this->conect());
				return $result;
			}
			
		}
		
		function find($where,$order=''){
			$sql = 'select * from '.$this->table.'';
			if($where!=''){
				if(is_array($where)){
					$wh .= $this->where($where);
				}
				else{
					$wh .= $where;
				}
				$sql .= ' where '.$wh.'';
			}
			if($order!=''){
				$sql .= ' order by '.$order.'';
			}
			$sql .= ' limit 0,1';
			$result = $this->query($sql);
			if(!$result){
				logs("".CURPAGE.":SQL语句执行失败：".mysql_error()."...sql:".$sql);
			}else{
				if(IS_DEBUG==1){
					logs("".CURPAGE.":成功执行SQL语句：".$sql);
				}
			}
			$arr = mysql_fetch_array($result);
			$this->free($result);
			$this->close($this->conect());
			return $arr;
		}
		
		function findf($where,$field='',$order=''){
			if($field!=''){
				if(is_array($field)){
					$field = implode(',',$field);
				}
				else{
					$field = $field;
				}
			}
			else{
				$field = '*';
			}
			$sql = 'select '.$field.' from '.$this->table.'';
			if($where!=''){
				if(is_array($where)){
					$wh .= $this->where($where);
				}
				else{
					$wh .= $where;
				}
				$sql .= ' where '.$wh.'';
			}
			if($order!=''){
				$sql .= ' order by '.$order.'';
			}
			$sql .= ' limit 0,1';
			$result = $this->query($sql);
			if(!$result){
				logs("".CURPAGE.":SQL语句执行失败：".mysql_error()."...sql:".$sql);
			}else{
				if(IS_DEBUG==1){
					logs("".CURPAGE.":成功执行SQL语句：".$sql);
				}
			}
			$arr = mysql_fetch_array($result);
			$this->free($result);
			$this->close($this->conect());
			return $arr;
		}
		
		function update($field,$where,$tag=1){
			$sql = 'update '.$this->table.' set';
			
			$fvalue = '';
			if(is_array($field)){
				$k=0;
				$count = count($field);
				foreach($field  as $name=>$value){
					$k++;
					if($count==1){
						$fvalue .= ''.$name.'="'.$value.'"';
					}
					elseif($k==$count && $count>1){
						$fvalue .= ''.$name.'="'.$value.'"';
					}
					else{
						$fvalue .= ''.$name.'="'.$value.'",';
					}
				}
			}
			else{
				$fvalue = $field;
			}
			$sql .= ' '.$fvalue.'';
			
			/*where array('user'=>"1","name"=>"like %tt%")*/
			if($where!='' && $tag==1){
				if(is_array($where)){
					$wh .= $this->where($where);
				}
				else{
					$wh .= $where;
				}
				$sql .= ' where '.$wh.'';
			}
			else{
				$sql = '';
			}
			$this->query($sql);
			$rows = mysql_affected_rows();
			$this->close($this->conect());
			return $rows;
		}
		
		function insert($field){
			$sql = 'insert into '.$this->table.'';
			if(is_array($field)){
				$i = 0;
				$count = count($field);
				$fname = '';
				$fvalue = '';
				foreach($field as $name=>$value){
					$i++;
					if($count==1){
						$fname .= $name;
						$fvalue .= "'".$value."'";
					}
					elseif($i==$count && $count>1){
						$fname .= $name;
						$fvalue .= "'".$value."'";
					}
					else{
						$fname .= $name.',';
						$fvalue .= "'".$value."',";
					}
				}
				$sql .= '('.$fname.') values('.$fvalue.')';
			}
			else{
				$sql .= $field;
			}
			$this->query($sql);
			$rows = mysql_insert_id();
			$this->close($this->conect());
			return $rows;
		}
		
		function del($where,$whole=0){
			$sql = 'delete from '.$this->table.'';
			if($where!=''){
				if(is_array($where)){
					$wh .= $this->where($where);
				}
				else{
					$wh .= $where;
				}
				$sql .= ' where '.$wh;
				$this->query($sql);
			}
			else{
				if($whole==1){
					$this->query($sql);
				}
				else{
					return '';
				}
			}
			$rows = mysql_affected_rows();
			$this->close($this->conect());
			return $rows;
		}
		
		function counts($where=''){
			$sql = "select count(*) as counts from ".$this->table."";
			if($where!=''){
				if(is_array($where)){
					$wh .= $this->where($where);
				}
				else{
					$wh .= $where;
				}
				$sql .= ' where '.$wh;
			}
			$result = $this->query($sql);
			$arr = mysql_fetch_array($result);
			$this->free($result);
			$this->close($this->conect());
			return $arr["counts"];
		}
				
		function free($result){
			mysql_free_result($result);
		}
		
		function close($conn){
			mysql_close($conn);
		}
		
		function arrtostr($arr,$stype='and'){
			$str = '';
			$i=0;
			$count = count($value);
			foreach($arr as $n=>$v){
				$i++;	
				if($count==1 || $i==1){
					if($stype=='and'){
						$str .= ''.$n.'="'.$v.'"';
					}
					elseif($stype=='or'){
						$str .= ''.$n.'="'.$v.'"';
					}
					elseif($stype=='like'){
						$str .= ''.$n.' like "'.$v.'"';
					}
					elseif($stype=='in'){
						$str .= ''.$n.' in '.$v.'';
					}
					elseif($stype=='string'){
						$str .= ''.$v.'';
					}
				}else{
					if($stype=='and'){
						$str .= ' and '.$n.'="'.$v.'"';
					}
					elseif($stype=='or'){
						$str .= ' or '.$n.'="'.$v.'"';
					}
					elseif($stype=='like'){
						$str .= ' and '.$n.' like "'.$v.'"';
					}
					elseif($stype=='in'){
						$str .= ' and '.$n.' in '.$v.'';
					}
					elseif($stype=='string'){
						$str .= ' and '.$v.'';
					}
				}
			}
			return $str;
		}
		
		function where($where){
			$sql = '1=1';
			foreach($where as $name=>$value){
				if($name=="and"){
					$and .= $this->arrtostr($where["and"]);	
				}
				if($name=="or"){
					$or .= $this->arrtostr($where["or"],"or");	
				}
				if($name=="like"){
					$like .= $this->arrtostr($where["like"],"like");	
				}
				if($name=="in"){
					$in .= $this->arrtostr($where["in"],"in");	
				}
				if($name=="string"){
					$string .= $this->arrtostr($where["string"],"string");	
				}
			}
			if($and!=''){
				$sql .= ' and '.$and;
			}
			if($or!=''){
				$sql .= ' and ('.$or.') ';
			}
			if($like!=''){
				$sql .= ' and '.$like;
			}
			if($in!=''){
				$sql .= ' and '.$in;
			}
			if($string!=''){
				$sql .= ' and '.$string;
			}
			return $sql;
		}
		
	}