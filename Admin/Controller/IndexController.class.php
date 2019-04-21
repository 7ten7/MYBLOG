<?php
//定义命名空间
namespace Admin\Controller;

use Admin\Model\ArticleModel;
use Admin\Model\LinkModel;
use Admin\Model\UserModel;
use \Frame\Libs\BaseController;
use Admin\Model\IndexModel;

final class IndexController extends BaseController
{
    //显示后台首页
    public function index(){
        $this->checkLogin();
        $modelObj = IndexModel::getInstance();

        $this->smarty->assign(array(
            "data" => $modelObj->putInfo(),
            "linkNum" => LinkModel::getInstance()->rowCount(), //友情链接总数
            "ArticleNum" => ArticleModel::getInstance()->rowCount(),  //文章总数
            "UserNum" => UserModel::getInstance()->rowCount("status=1"),  //管理员总数，没有登录权限的管理员账号不算在内
        ));

        $this->smarty->display("Index/index.html");
    }



}