<?php

namespace Frame\Libs;

class Pager
{
    //私有的成员属性
    private $page; //当前页
    private $pages;//总页数
    private $jumpCont; //获取跳转的控制器的控制器方法

    //构造方法
    public function __construct($page,$pages,$jumpCont)
    {
        $this->page = $page;
        $this->pages = $pages;
        $this->jumpCont = $jumpCont;
    }

    //公共的分页方法
    public function tabs(){

        //计算循环的开始页和结束页
        $start = $this->page-5;
        $end = $this->page+4;
        if($this->page<6){
            $start = 1;
            $end = $end+6-$this->page;
        }
        if ($end>$this->pages){
            $start = $this->pages-10+1;
            $end = $this->pages;
        }
        if($this->pages<=10){
            $start = 1;
            $end = $this->pages;
        }
        //循环显示所有的页码
        for($i=$start;$i<=$end;$i++){
            if($i==$this->page){
                $arr[] =  "<a href='javascript:void(0)' style='color: black'>$i</a>";
            }else{
                $arr[] =  "<a href=\"?{$this->jumpCont}&page=$i\" style=\"cursor:pointer\">$i</a>";
            }
        }
        return $arr;
    }
}