<?php
//后端配置数组
return array(
    'db_type' => 'mysql',  //数据库类型
    'db_host' => 'localhost', //主机名
    'db_port' => '3306', //端口号
    'db_user' => 'root', //用户名
    'db_pass' => '',  //密码
    'db_name' => 'security', //数据库名
    'charset' => 'utf8', //字符集

    //后端默认URL路由
    'default_platform' => 'Admin',  //默认应用
    'default_controller' => 'Index', //默认控制器
    'default_action' => 'index',  //默认动作
);