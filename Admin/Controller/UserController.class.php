<?php
namespace Admin\Controller;

use Frame\Libs\BaseController;

class UserController extends BaseController
{
    //显示后台登录界面
    public function login(){
        $this->smarty->display("User/login.html");
    }
}