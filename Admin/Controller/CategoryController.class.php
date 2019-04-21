<?php
namespace Admin\Controller;

use Admin\Model\CategoryModel;
use Frame\Libs\BaseController;

class CategoryController extends BaseController
{
    public function index(){
        $this->checkLogin();
        $modelObj = CategoryModel::getInstance();
        $arrs = $modelObj->fetchAll();
        $arrs = $modelObj->categoryList($arrs);
        $this->smarty->assign("arrs",$arrs);
        $this->smarty->display("Category/index.html");
    }

    public function insert(){
        $this->checkLogin();
        $data['name'] = $_POST['name'];
        $data['alias'] = $_POST['alias'];
        $data['pid'] = $_POST['pid'];
        $data['keywords'] = $_POST['keywords'];
        $data['describes'] = $_POST['describes'];
        if(CategoryModel::getInstance()->insert($data)){
            $this->jump("栏目添加成功","?c=category&a=index",3);
        }else{
            $this->jump("<font color='red'>栏目添加失败</font>","?c=category&a=index",3);
        }
    }

    public function edit(){
        $this->checkLogin();
        $modelObj = CategoryModel::getInstance();
        $arrs = $modelObj->fetchAll();
        $arrs = $modelObj->categoryList($arrs);
        $arrOne = $modelObj->fetchOne("id={$_GET['id']}");
        $this->smarty->assign("arrs",$arrs);
        $this->smarty->assign("arrOne",$arrOne);
        $this->smarty->display("Category/edit.html");
    }

    public function update(){
        $this->checkLogin();
        $id = $_POST['id'];
        $data['name'] = $_POST['name'];
        $data['alias'] = $_POST['alias'];
        $data['pid'] = $_POST['pid'];
        $data['keywords'] = $_POST['keywords'];
        $data['describes'] = $_POST['describes'];
        if (CategoryModel::getInstance()->update($data,$id)){
            $this->jump("栏目信息修改成功","?c=category&a=index",3);
        }else{
            $this->jump("<font color='red'>栏目信息修改失败</font>","?c=category&a=index",3);
        }
    }
    public function delete(){
        $this->checkLogin();
        echo '等待功能添加';
    }
}