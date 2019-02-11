<?php
//定义命名空间
namespace Home\Controller;
use \Home\Model\IndexModel;
use \Frame\Libs\BaseController;

//定义最终的首页控制器类，并基础基础控制器类
final class IndexController extends BaseController
{
    public function index(){
        //创建模型类对象
        $modelObj = IndexModel::getInstance();
        //获取多行数据
        $arrs = $modelObj->fetchAll();
        //向视图赋值，并显示视图
        $this->smarty->assign("arrs",$arrs);
        $this->smarty->display("Index/index.html");
    }

    //删除记录
    public function delete(){
        $id = $_GET['id'];
        $modelObj = IndexModel::getInstance();
        //判断是否删除成功
        if($modelObj->delete($id)){
            $this->jump("id={$id}的记录删除成功！","?c=Index");
        }else{
            $this->jump("id={$id}的记录删除失败！","?c=Index");
        }
    }
}