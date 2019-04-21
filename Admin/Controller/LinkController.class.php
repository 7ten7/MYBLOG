<?php
namespace Admin\Controller;


use Admin\Model\LinkModel;
use Frame\Libs\BaseController;
use Frame\Libs\Pager;

class LinkController extends BaseController
{
    public function index(){
        $this->checkLogin();
        $modelObj = LinkModel::getInstance();
        //分页参数
        $pagesizr = 5;  //每页记录数
        $page = isset($_GET['page']) ? $_GET['page']:1; //当前页
        $startrow = ($page-1)*$pagesizr;  //当前页开始的记录数

        //获取总记录数和总页数
        $records = $modelObj->rowCount(); //总记录数
        $pages = ceil($records/$pagesizr); //总页数

        //获取分页数据
        $where ="ORDER BY ID LIMIT {$startrow},{$pagesizr}";
        $arrs = $modelObj->fetchAll($where);
        $jumpCont = "c=Link&a=index";
        $pageObj = new Pager($page,$pages,$jumpCont);
        $pageArr = $pageObj->tabs();


        $this->smarty->assign(array(
            "arrs" => $arrs,
            "pageArr" => $pageArr,
            "currentPage" => $page,
            "pages" => $pages,
            "linkNum" => $modelObj->rowCount(), //获取友链总数
        ));
        $this->smarty->display("Link/index.html");
    }
    public function add(){
        $this->checkLogin();
        $this->smarty->display("Link/add.html");
    }
    public function insert(){
        $this->checkLogin();
        $data['webname'] = $_POST['name'];
        $data['website'] = $_POST['url'];
        $data['webimage'] = $_POST['imgurl'];
        $data['description'] = $_POST['describe'];
        $data['addtime'] = time();
        if(LinkModel::getInstance()->insert($data)){
            $this->jump("友情链接添加成功！","?c=Link&a=index",3);
        }else{
            $this->jump("<font color='red'>友情链接添加失败！</font>","?c=Link&a=index",3);
        }
    }
    public function edit(){
        $this->checkLogin();
        $where = "id={$_GET['id']}";
        $arr = LinkModel::getInstance()->fetchOne($where);
        $this->smarty->assign("arr",$arr);
        $this->smarty->display("Link/edit.html");
    }

    public function update(){
        $this->checkLogin();
        $data['webname'] = $_POST['name'];
        $data['website'] = $_POST['url'];
        $data['webimage'] = $_POST['imgurl'];
        $data['description'] = $_POST['describe'];
        $data['addtime'] = time();
        if(LinkModel::getInstance()->update($data,$_POST['id'])){
            $this->jump("友情链接修改成功！","?c=Link&a=index",3);
        }else{
            $this->jump("<font color='red'>友情链接修改失败！</font>","?c=Link&a=index",3);
        }
    }
    public function delete(){
        $this->checkLogin();
        if(LinkModel::getInstance()->delete($_GET['id'])){
            header("LOCATION:?c=Link&a=index");
        }else{
            $this->jump("<font color='red'>友情链接删除失败！</font>","?c=Link&a=index",3);
        }
    }

    public function deleteAll(){
        $this->checkLogin();
        if(isset($_POST['checkbox'])) {
            $arrs = $_POST['checkbox'];
            $where = "";
            foreach ($arrs as $arr) {
                $where .= "id={$arr} or ";
            }
            $where = rtrim($where, " or ");
            if (LinkModel::getInstance()->deleteAll($where)) {
                header("LOCATION:?c=Link&a=index");
            } else {
                $this->jump("<font color='red'>友情链接删除失败！</font>", "?c=Link&a=index", 3);
            }
        }else{
            header("LOCATION:?c=Link&a=index");
        }
    }
}