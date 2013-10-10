<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: Page.class.php 2712 2012-02-06 10:12:49Z liu21st $

class Page {
    // 分页栏每页显示的页数
    public $rollPage = 5;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow	;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    //protected $config  =	array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
    protected $config  =	array('header'=>'条记录','prev'=>'prev','next'=>'next','first'=>'first Page','last'=>'last Page','theme'=>'<p class="total">%totalRow% %header% %nowPage%/%totalPage% 页</p>%first%%upPage%%linkPage%%downPage%%end%<span class="inp"><input type="text" size="4" id="pagenum" value="1"></span><a href="javascript:;" id="gotopage">GO</a>');
    // 默认分页变量名
    protected $varPage;

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
    public function __construct($totalRows,$listRows='',$parameter='') {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->varPage = 'p' ;
        if(!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show($cs='') {
        if(0 == $this->totalRows) return '';
        $p = $this->varPage;
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a id='page' href='".$url."".$cs."&".$p."=$upRow' class='prev' title='prev page' alt='prev page'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a id='page' href='".$url."".$cs."&".$p."=$downRow' class='next' title='next page' alt='next page'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<a id='page' href='".$url."".$cs."&".$p."=$preRow' >prev ".$this->rollPage."</a>";
            $theFirst = "<a id='page' href='".$url."".$cs."&".$p."=1' class='first' title='first page' alt='first page'>".$this->config['first']."</a>";
        }
        
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a id='page' href='".$url."".$cs."&".$p."=$nextRow' >next".$this->rollPage."</a>";
            $theEnd = "<a id='page' href='".$url."".$cs."&".$p."=$theEndRow' class='last' title='goto page $theEndRow' alt='goto page $theEndRow'>".$this->config['last']."</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        /*
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "&nbsp;<a href='".$url."&".$p."=$page' title='goto page ".$page."' alt='goto page ".$page."'>&nbsp;".$page."&nbsp;</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "&nbsp;<span class='current'>".$page."</span>";
                }
            }
        }*/
        if($this->totalPages <=7)
        {
		      for($i=1;$i<=$this->totalPages;$i++)
		      {
						$page=($nowCoolPage-1)*$this->rollPage+$i;
						if($page!=$this->nowPage)
						{
							if($page<=$this->totalPages)
							{
								$linkPage .= "<a id='page' href='".$url."".$cs."&".$p."=$page' title='goto page ".$page."' alt='goto page ".$page."'>".$page."</a>";
							}
							else
							{
								break;
							}
						}else{
							if($this->totalPages != 1){
								$linkPage .= "<span class='current'>".$page."</span>";
							}
						}
          	} 	
		}
		else
		{
			if($this->nowPage - 2 >1)
			{
				if($this->totalPages - $this->nowPage >= 4)
				{
				  for($k=$this->nowPage-2;$k<$this->nowPage;$k++)
				  {
					   $linkPage .= "<a id='page' href='".$url."".$cs."&".$p."=$k' title='goto page ".$k."' alt='goto page ".$k."'>".$k."</a>";
					}
					$linkPage .= '<span class="current">'.$this->nowPage.'</span>';
					for($k=$this->nowPage+1;$k<=($this->nowPage+2);$k++)
					{
			   		$linkPage .= "<a id='page' href='".$url."".$cs."&".$p."=$k' title='goto page ".$k."' alt='goto page ".$k."'>".$k."</a>";
				  }
			  }
			  else
			  {
					if(($this->totalPages - $this->nowPage)>=2)
					{
						for($k=$this->nowPage-(6-($this->totalPages-$this->nowPage));$k<$this->nowPage;$k++)
						{
						   $linkPage .= "<a id='page' href='".$url."".$cs."&".$p."=$k' title='goto page ".$k."' alt='goto page ".$k."'>".$k."</a>";
						}
						$linkPage .= '<span class="current">'.$this->nowPage.'</span>';
						for($k=$this->nowPage+1;$k<=($this->totalPages-2);$k++)
						{
						   $linkPage .= "<a id='page' href='".$url."".$cs."&".$p."=$k' title='goto page ".$k."' alt='goto page ".$k."'>".$k."</a>";
						}
					}
					else
					{
				   	for($k=$this->totalPages-6;$k<=$this->totalPages-2;$k++){
					  	$linkPage .= "<a id='page' href='".$url."".$cs."&".$p."=$k' title='goto page ".$k."' alt='goto page ".$k."'>".$k."</a>";
						}
					}
				}
			}
			else
			{
			  for($k=1;$k<$this->nowPage;$k++)
			  {
			  	$linkPage .= "<a id='page' href='".$url."".$cs."&".$p."=$k' title='goto page ".$k."' alt='goto page ".$k."'>".$k."</a>";
				}
				$linkPage .= '<span class="current">'.$this->nowPage.'</span>';
				for($k=$this->nowPage+1;$k<=5;$k++)
				{
			  	$linkPage .= "<a id='page' href='".$url."".$cs."&".$p."=$k' title='goto page ".$k."' alt='goto page ".$k."'>".$k."</a>";
				}
			}
			$linkPage .= '<span>...</span>';
			$kkdc = $this->totalPages - 1;
			if($kkdc== $this->nowPage){
				$linkPage .= '<span class="current">'.$this->nowPage.'</span>';
			}else{
				$linkPage .= "<a id='page' href='".$url."".$cs."&".$p."=$kkdc' title='goto page ".$kkdc."' alt='goto page ".$kkdc."'>".$kkdc."</a>";
			}
			if($this->totalPages== $this->nowPage){
				$linkPage .= '<span class="current">'.$this->nowPage.'</span>';
			}else{
				$linkPage .= "<a id='page' href='".$url."".$cs."&".$p."=".$this->totalPages."' title='goto page ".$this->totalPages."' alt='goto page ".$this->totalPages."'>".$this->totalPages."</a>";
			}
		}
        $pageStr	 =	 str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }

}