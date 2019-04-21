<?php

namespace Admin\Controller;

use Admin\Model\ArticleModel;
use Admin\Model\CategoryModel;
use Frame\Libs\BaseController;
use Frame\Libs\Pager;

class ArticleController extends BaseController
{
    public function index(){
        $this->checkLogin();
        $modelObj = ArticleModel::getInstance();

        //分页参数
        $pagesizr = 5;  //每页记录数
        $page = isset($_GET['page']) ? $_GET['page']:1; //当前页
        $startrow = ($page-1)*$pagesizr;  //当前页开始的记录数

        //获取总记录数和总页数
        $records = $modelObj->rowCount(); //总记录数
        $pages = ceil($records/$pagesizr); //总页数

        //获取分页数据

        $arrs = $modelObj->fetchAllJoin($startrow,$pagesizr);

        //分页页码显示
        $jumpCont = "c=Article&a=index";
        $pageObj = new Pager($page,$pages,$jumpCont);
        $pageArr = $pageObj->tabs();



        $this->smarty->assign(array(
            "arrs" => $arrs,
            "pageArr" => $pageArr,
            "currentPage" => $page,
            "pages" => $pages,
            "ArticleNum" => $modelObj->rowCount(),    //获取文章总数
        ));
        $this->smarty->display("Article/index.html");
    }
    public function add(){
        $this->checkLogin();
        $modelObj = CategoryModel::getInstance();
        $arrs = $modelObj->fetchAll();
        $arrs = $modelObj->categoryList($arrs);
        $this->smarty->assign("arrs",$arrs);
        $this->smarty->display("Article/add.html");
    }

    public function insert(){
        $this->checkLogin();
        $data['c_id'] = $_POST['category'];
        $data['u_id'] = $_SESSION['uid'];
        $data['title'] = $_POST['title'];
        $data['content'] = $_POST['content'];
        $data['label'] = $_POST['tags'];
        $data['t_img'] = $_POST['titlepic'];
        $data['time'] = time();
        $data['open'] = $_POST['visibility'];
        $data['describes'] = $_POST['describe'];
        if(ArticleModel::getInstance()->insert($data)){
            $this->jump("文章发布成功","?c=Article&a=index",2);
        }else{
            $this->jump("<font color='red'>文章发布失败</font>","?c=Article&a=index",3);
        }
    }

    public function delete(){
        $this->checkLogin();
        if(ArticleModel::getInstance()->delete($_GET['id'])){
            header("LOCATION:?c=Article&a=index");
        }else{
            $this->jump("<font color='red'>文章删除失败！</font>","?c=Article&a=index",3);
        }
    }

    public function edit(){
        $this->checkLogin();
        $modelObj = CategoryModel::getInstance();
        $arrs = $modelObj->fetchAll();
        $arrs = $modelObj->categoryList($arrs);
        $where = "id={$_GET['id']}";
        $articles = ArticleModel::getInstance()->fetchOne($where);
//        print_r($articles);
//        die();
        $this->smarty->assign(array(
            'arrs' => $arrs,
            "articles" => $articles,
        ));

        $this->smarty->display("Article/edit.html");

    }
    public function update(){
        $data['c_id'] = $_POST['category'];
        $data['title'] = $_POST['title'];
        $data['content'] = $_POST['content'];
        $data['label'] = $_POST['tags'];
        $data['t_img'] = $_POST['titlepic'];
        $data['time'] = time();
        $data['open'] = $_POST['visibility'];
        $data['describes'] = $_POST['describe'];
        if(ArticleModel::getInstance()->update($data,$_POST['id'])){
            $this->jump("文章修改成功！","?c=Article&a=index",3);
        }else{
            $this->jump("<font color='red'>文章修改失败！</font>","?c=Article&a=index",3);
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
            if (ArticleModel::getInstance()->deleteAll($where)) {
                header("LOCATION:?c=Article&a=index");
            } else {
                $this->jump("<font color='red'>友情链接删除失败！</font>", "?c=Link&a=index", 3);
            }
        }else{
            header("LOCATION:?c=Article&a=index");
        }
    }

}