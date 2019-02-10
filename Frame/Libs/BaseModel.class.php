<?php
//声明命名空间
namespace Frame\Libs;

use Frame\Vendor\PDOWrapper;

//定义抽象的基础模型类
abstract class BaseModel
{
    //受保护的$pdo对象属性
    protected $pdo = NULL;

    //私有的静态的保存不同模型类对象的数组属性
    private static $arrModelObj = array();

    //构造方法
    public function __construct()
    {
        $this->pdo = new PDOWrapper();
    }

    //公共的静态的创建模型类对象的方法
    public static function getInstance(){
        //获取静态化方式调用的类名
        $modelClassName = get_called_class();

        //判断当前模型类对象是否存在
        if(!isset(self::$arrModelObj[$modelClassName])){
            //如果当前模型类不存在，则创建并保存它
            self::$arrModelObj[$modelClassName] = new $modelClassName();
        }
        //返回当前模型类的对象
        return self::$arrModelObj[$modelClassName];
    }
}