<?php
//声明命名空间
namespace Frame\Libs;
use Frame\Vendor\Smarty;

//定义抽象的基础控制器类
abstract class BaseController
{
    //受保护的Smarty对象属性
    protected $smarty = NULL;

    //公共的构造方法
    public function __construct()
    {
        $this->initSmarty();
    }

    //私有的smarty对象初始化
    private function initSmarty(){
        //创建Smarty类的对象
        $smarty = new Smarty();
        //Smarty配置
        $smarty->left_delimiter = "<{"; //左定界符
        $smarty->right_delimiter = "}>"; //右定界符
        $smarty->setTemplateDir(VIEW_PATH); //设置视图文件目录
        $smarty->setCompileDir(sys_get_temp_dir().DS."view"); //设置编译目录
        //给$smarty属性赋值
        $this->smarty = $smarty;
    }

    //受保护的跳转方法
    protected function jump($message='失败',$url='?',$time=5){
        //向跳转视图赋值
        $this->smarty->assign("message",$message);
        $this->smarty->assign("url",$url);
        $this->smarty->assign("time",$time);
        //显示跳转视图
        $this->smarty->display("Public/jump.html");
        die();
    }

    //判断用户是否已经登录
    protected function checkLogin(){
        if(!isset($_SESSION['username'])){
            header("Location:?c=User&a=login");
        }
    }
}