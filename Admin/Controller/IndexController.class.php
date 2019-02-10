<?php
//定义命名空间
namespace Admin\Controller;
use \Admin\Model\IndexModel;
use \Frame\Libs\BaseController;

final class IndexController extends BaseController
{
    public function index(){
        //创建模型类对象
        $modelObj = IndexModel::getInstance();
        //获取多行数据
        $arrs = $modelObj->fetchAll();
        //向视图赋值，并显示视图
        $this->smarty->assign("arrs",$arrs);
        $this->smarty->display("index.html");
    }
}