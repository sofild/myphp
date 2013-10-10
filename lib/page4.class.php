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

class Page4 {
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
    protected $config  =	array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'first Page','last'=>'last Page','theme'=>'<p class="page">%upPage%%linkPage%%downPage%</p>');
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
    public function __construct($totalRows,$listRows='',$nowpage,$parameter='') {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->varPage = 'p' ;
        if(!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
			
		
        $this->nowPage  = !empty($nowpage)?intval($nowpage):1;
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
    public function show($cate,$ajax=0) {
        if(0 == $this->totalRows) return '';
        $p = $this->varPage;
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
        $url  =  $nowurl;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].http_build_query($params);
        }
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
			if($ajax==0){	
            	$upPage="<a href='#".$cate."' onclick='turnpage(".$cate.",".$upRow.");' class='pgbtn'>".$this->config['prev']."</a>";
			}
			else{
				$upPage="<a href='#".$cate."' data='".$upRow."' class='pgbtn'>".$this->config['prev']."</a>";
			}
		
		}else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
			if($ajax==0){
            	$downPage="<a id='page' href='#".$cate."' onclick='turnpage(".$cate.",".$downRow.");' class='pgbtn'>".$this->config['next']."</a>";
			}
			else{
				$downPage="<a id='page' href='#".$cate."' data='".$downRow."' class='pgbtn'>".$this->config['next']."</a>";
			}
		}else{
            $downPage="";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
			if($ajax==0){
            	$prePage = "<a href='#".$cate."' onclick='turnpage(".$cate.",".$preRow.");' >prev ".$this->rollPage."</a>";
			}
			else{
				$prePage = "<a href='#".$cate."' data='".$preRow."' >prev ".$this->rollPage."</a>";
			}
			//$theFirst = "<a href='".$url."".$cs."&".$p."=1' class='first' title='first page' alt='first page'>".$this->config['first']."</a>";
        }
        
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            if($ajax==0){
				$nextPage = "<a id='page' href='#".$cate."' onclick='turnpage(".$cate.",".$nextRow.");' >next".$this->rollPage."</a>";
			}
			else{
				$nextPage = "<a id='page' href='#".$cate."' data='".$nextRow."' >next".$this->rollPage."</a>";
			}
			//$theEnd = "<a id='page' href='".$url."".$cs."&".$p."=$theEndRow' class='last' title='goto page $theEndRow' alt='goto page $theEndRow'>".$this->config['last']."</a>";
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
								if($ajax==0){
									$linkPage .= "<a href='#".$cate."' onclick='turnpage(".$cate.",".$page.");'>".$page."</a>";
								}
								else{
									$linkPage .= "<a href='#".$cate."' data='".$page."'>".$page."</a>";
								}
							}
							else
							{
								break;
							}
						}else{
							if($this->totalPages != 1){
								if($ajax==0){
									$linkPage .= "<a href='#".$cate."' class='on'>".$page."</a>";
								}
								else{
									$linkPage .= "<a href='#".$cate."' class='on' data='".$page."'>".$page."</a>";
								}
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
					  if($ajax==0){
					   	$linkPage .= "<a href='#".$cate."' onclick='turnpage(".$cate.",".$k.");'>".$k."</a>";
					  }
					  else{
					  	$linkPage .= "<a href='#".$cate."' data='".$k."'>".$k."</a>";
					  }
					}
					$linkPage .= '<a href="#".$cate."" class="on">'.$this->nowPage.'</a>';
					for($k=$this->nowPage+1;$k<=($this->nowPage+2);$k++)
					{
						if($ajax==0){
			   				$linkPage .= "<a href='#".$cate."' onclick='turnpage(".$cate.",".$k.");'>".$k."</a>";
						}
						else{
							$linkPage .= "<a href='#".$cate."' data='".$k."'>".$k."</a>";
						}
				  }
			  }
			  else
			  {
					if(($this->totalPages - $this->nowPage)>=2)
					{
						for($k=$this->nowPage-(6-($this->totalPages-$this->nowPage));$k<$this->nowPage;$k++)
						{
							if($ajax==0){
						   		$linkPage .= "<a href='#".$cate."' onclick='turnpage(".$cate.",".$k.");'>".$k."</a>";
							}
							else{
								$linkPage .= "<a href='#".$cate."' data='".$k."'>".$k."</a>";
							}
						}
						if($ajax==0){
							$linkPage .= '<a href="#".$cate."" class="on">'.$this->nowPage.'</a>';
						}
						else{
							$linkPage .= '<a href="#b" class="on" data="'.$this->nowPage.'">'.$this->nowPage.'</a>';
						}	
						for($k=$this->nowPage+1;$k<=($this->totalPages-2);$k++)
						{
							if($ajax==0){
						 		$linkPage .= "<a href='#".$cate."' onclick='turnpage(".$cate.",".$k.");'>".$k."</a>";
							}
							else{
								$linkPage .= "<a href='#".$cate."' data='".$k."'>".$k."</a>";
							}
						}
					}
					else
					{
				   	for($k=$this->totalPages-6;$k<=$this->totalPages-2;$k++){
					  	if($ajax==0){
							$linkPage .= "<a href='#".$cate."' onclick='turnpage(".$cate.",".$k.");'>".$k."</a>";
						}
						else{
							$linkPage .= "<a href='#".$cate."' data='".$k."'>".$k."</a>";
						}
						}
					}
				}
			}
			else
			{
			  for($k=1;$k<$this->nowPage;$k++)
			  {
			  	if($ajax==0){
					$linkPage .= "<a href='#".$cate."' onclick='turnpage(".$cate.",".$k.");'>".$k."</a>";
				}
				else{
					$linkPage .= "<a href='#".$cate."' data='".$k."'>".$k."</a>";
				}
				}
				if($ajax==0){
					$linkPage .= '<a href="#".$cate."" class="on">'.$this->nowPage.'</a>';
				}
				else{
					$linkPage .= '<a href="#".$cate."" class="on" data="'.$this->nowPage.'">'.$this->nowPage.'</a>';
				}
				for($k=$this->nowPage+1;$k<=5;$k++)
				{
					if($ajax==0){
			  			$linkPage .= "<a href='#".$cate."' onclick='turnpage(".$cate.",".$k.");'>".$k."</a>";
					}
					else{
						$linkPage .= "<a href='#".$cate."' data='".$k."'>".$k."</a>";
					}
				}
			}
			$linkPage .= '<span>...</span>';
			$kkdc = $this->totalPages - 1;
			if($kkdc== $this->nowPage){
				if($ajax==0){
					$linkPage .= '<a href="#".$cate."" class="on">'.$this->nowPage.'</a>';
				}
				else{
					$linkPage .= '<a href="#".$cate."" class="on" data="'.$this->nowPage.'">'.$this->nowPage.'</a>';
				}
			}else{
				if($ajax==0){
					$linkPage .= "<a href='#".$cate."' onclick='turnpage(".$cate.",".$kkdc.");'>".$kkdc."</a>";
				}
				else{
					$linkPage .= "<a href='#".$cate."' data='".$kkdc."'>".$kkdc."</a>";
				}
			}
			if($this->totalPages== $this->nowPage){
				if($ajax==0){
					$linkPage .= '<a href="#".$cate."" class="on">'.$this->nowPage.'</a>';
				}
				else{
					$linkPage .= '<a href="#".$cate."" class="on" data="'.$this->nowPage.'">'.$this->nowPage.'</a>';
				}
			}else{
				if($ajax==0){
					$linkPage .= "<a href='#".$cate."' onclick='turnpage(".$cate.",".$this->totalPages.");'>".$this->totalPages."</a>";
				}
				else{
					$linkPage .= "<a href='#".$cate."' data='".$this->totalPages."'>".$this->totalPages."</a>";
				}
			}
		}
        $pageStr	 =	 str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }

}