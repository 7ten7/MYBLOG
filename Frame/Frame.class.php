<?php
//声明命名空间
namespace Frame;

//定义最终的框架初始类
class Frame
{
    public static function run(){
        self::initCharset();//初始化字符集设置
        self::initConfig();//初始化配置文件
        self::initRoute();//初始化路由参数
        self::initConst();//初始化常量目录设置
        self::initAutoLoad();//初始化类的自动加载
        self::initDispatch();//初始化请求分发
    }
    //初始化字符集设置
    private static function initCharset(){
        header("content-type:text/html;charset=utf-8");
    }
    //初始化配置信息
    private static function initConfig(){
        $GLOBALS['config'] = require_once(APP_PATH."Conf".DS."Config.php");
    }
    private static function initRoute(){
        $p = $GLOBALS['config']['default_platform']; //平台参数
        $c = isset($_GET['c']) ?$_GET['c'] : $GLOBALS['config']['default_controller']; //控制器参数
        $a = isset($_GET['a']) ?$_GET['a'] : $GLOBALS['config']['default_action']; //用户动作参数
        define("PLAT",$p); //平台常量
        define("CONTROLLER",$c);
        define("ACTION",$a);
    }
    //初始化目录常量设置
    private static function initConst(){
        define("VIEW_PATH",APP_PATH."View".DS); //View目录
        define("FRAME_PATH",ROOT_PATH."Frame".DS);//Frame目录
    }
    private static function initAutoLoad(){
        spl_autoload_register(function ($className){
            //构建类文件的真实路径
            $filename = ROOT_PATH.str_replace("\\",DS,$className).".class.php";
            //如果类文件存在，则包含
            if(file_exists($filename)){
                require_once($filename);
            }
        });

    }
    //初始化请求分发
    private static function initDispatch(){
        //构建控制器类名
        $className = "\\".PLAT."\\"."Controller"."\\".CONTROLLER."Controller";
        //创建控制器类的对象
        $controllerObj = new $className();
        //调用控制器对象的方法
        $actionName = ACTION;
        $controllerObj->$actionName();
    }
}