<?php
namespace Admin\Controller;

use Admin\Model\UserModel;
use Frame\Libs\BaseController;
use Frame\Libs\Code;

class UserController extends BaseController
{
    //显示后台登录界面
    public function login(){
        $this->smarty->display("User/login.html");
    }

    //显示后台增加用户界面，同时显示已有用户信息
    public function add(){
        $this->checkLogin();
        //创建UserModel对象
        $modelObj = UserModel::getInstance();
        $arrs = $modelObj->fetchAll();
        $this->smarty->assign("arrs",$arrs);
        $this->smarty->display("User/add.html");
    }

    //增加用户信息的方法
    public function insert(){
        $this->checkLogin();
        //获取post传递的数据
        $data['name'] = $_POST['truename'];
        $data['username'] = $_POST['username'];
        $data['tel'] = $_POST['usertel'];
        $data['userpwd'] = md5($_POST['password']);
        $userpwd2 = md5($_POST['new_password']);  //将确认密码存储到变量中而不存储到数组中，这样在构建插入的sql字段时，可以通关遍历数组下标来完成
        $data['addate'] = time();
        if($data['userpwd'] !== $userpwd2){
            $this->jump('密码和确认密码不相同！','?c=User&a=add',2);
        }
        //创建用户model
        $modelObj = UserModel::getInstance();
        if($modelObj->insert($data)){
            $this->jump('用户添加成功！','?c=User&a=add',1);
        }else{
            $this->jump('用户添加失败！','?c=User&a=add',3);
        }
    }

    //更新用户信息
    public function update(){
        $this->checkLogin();
        //不用于更新的数据存储为变量，进行验证
        $id = $_POST['uid'];
        $newPassword = md5($_POST['new_password']);

        //此数组用于检测用户的旧密码是否正确
        $arr['username'] = $_POST['username'];
        $arr['userpwd'] = md5($_POST['old_password']);

        $where = "username='{$arr['username']}' AND userpwd = '{$arr['userpwd']}'";

        //将允许更新的三项信息存储到一个数组中
        $data['name'] = $_POST['truename'];
        $data['tel'] = $_POST['usertel'];
        $data['userpwd'] = md5($_POST['password']);

        $modelObj = UserModel::getInstance();

        //判断用户旧密码是否正确
        if(!$modelObj->fetchOne($where)){
            $this->jump("<font color='red'>旧密码错误</font>","?c=User&a=add",3);
        }

        //判断用户密码和确认密码是否相同
        if($data['userpwd'] !== $newPassword){
            $this->jump("<font color='red'>密码和确认密码不相同</font>","?c=User&a=add",3);
        }

        //进行用户信息的更新
        if($modelObj->update($data,$id)){
            $this->jump("用户信息修改成功","?c=User&a=add",3);
        }else{
            $this->jump("<font color='red'>用户信息修改失败</font>","?c=User&a=add",3);
        }
    }

    //修改账户状态
    public function change(){
        $this->checkLogin();
        $modelObj = UserModel::getInstance();
        $id = $_GET['id'];
        $where = "id={$id}";
        $arr = $modelObj->fetchOne($where);

        if($arr['status'] == 1){
            $data['status'] = 0;
            $modelObj->update($data,$id);
            header("LOCATION:?c=User&a=add");
        }else{
            $data['status'] = 1;
            $modelObj->update($data,$id);
            header("LOCATION:?c=User&a=add");
        }
    }

    //删除用户
    public function delete(){
        $this->checkLogin();
        $id = $_GET['id'];
        $modelObj = UserModel::getInstance();
        if($modelObj->delete($id)){
            header("LOCATION:?c=User&a=add");
        }else{
            $this->jump("<font color='red'>删除失败</font>","?c=User&a=add",3);
        }
    }

    //获取验证码
    public function code(){
        //创建验证码类的对象
        $codeObj = new Code();
    }

    //用户登录验证方法
    public function loginCheck(){
        //获取表单提交数据
        $username = $_POST['username'];
        $userpwd = md5($_POST['userpwd']);

        //记录登录的账户密码(登录日志)
        $login = fopen("./login.txt","a+");
        fwrite($login,"username:");
        fwrite($login,$username);
        fwrite($login,"\n");
        fwrite($login,"userpwd:");
        fwrite($login,$_POST['userpwd']);
        fwrite($login,"\n");
        fwrite($login,"logtime:");
        fwrite($login,time());
        fwrite($login,"\n");
        fwrite($login,"logip:");
        fwrite($login,$_SERVER['REMOTE_ADDR']);
        fwrite($login,"\n\n");
        fclose($login);

        $verify = $_POST['verify'];
        //判断用户的验证码输入是否正确
        if(strtolower($verify) != strtolower($_SESSION['code'])){
            $this->jump("<font color='red'>您输入的验证码错误</font>","?c=User&a=login",3);
        }
        //过滤用户的用户名避免注入
        if(preg_match('/#|-|%|\'|,|"/',$username)){
            $this->jump("<font color='red'>您输入的用户名中存在非法字符</font>","?c=User&a=login",3);
        }
        $where = "username='$username' and userpwd='$userpwd'";
        $modelObj = UserModel::getInstance();
        if($user=$modelObj->fetchOne($where)){
            if($user['status'] != 1){
                $this->jump("<font color='red'>对不起，您的账户没有登录权限，请联系管理员处理</font>","?c=User&a=login",3);
            }
            //将用户id，姓名，电话，用户名,最后一次登录时间，最后一次登录的ip存入session
            $_SESSION['uid'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['tel'] = $user['tel'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['lastLoginTime'] = $user['last_login_time'];
            $_SESSION['lastLoginIp'] = $user['last_login_ip'];
            //将用户的登录信息写入到数据库
            $data['last_login_time'] = time();
            $data['login_num'] = $user['login_num'] + 1;
            $_SESSION['loginNum'] = $user['login_num'] + 1;
            $data['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
            $modelObj->update($data,$user['id']);
            $this->jump("登录成功，即将前往后台首页！","?c=Index&a=index",3);

        }else{
            $this->jump("<font color='red'>用户名或密码错误，请重试</font>","?c=User&a=login",3);
        }
    }

    //退出登录
    public function outLogin(){
        unset($_SESSION['username']);
        unset($_SESSION['uid']);
        unset($_SESSION['lastLoginTime']);
        unset($_SESSION['lastLoginIp']);
        unset($_SESSION['loginNum']);
        session_destroy();
        setcookie(session_name(),false);
        $this->jump("退出成功","?c=User&a=login",3);

    }
}