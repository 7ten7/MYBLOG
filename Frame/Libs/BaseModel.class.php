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

    public function insert($data){

        $str = "";//构建插入的字段
        $num = "";//插入的数据
        foreach ($data as $key => $value){ //构建通用的插入语句
            $str .= "$key,";
            $num .= "'$value',";
        }
        //去除结尾的逗号
        $str = rtrim($str,',');
        $num = rtrim($num,',');
        //组织sql语句
        $sql = "INSERT INTO {$this->tableName} ($str) VALUES ($num)";
        return $this->pdo->exec($sql); //调用PDO对象来执行sql语句，并将结果返回
    }

    public function fetchAll($where="WHERE 2>1 ORDER BY id"){
        $sql = "SELECT * FROM {$this->tableName} {$where}";
        return $this->pdo->fetchAll($sql);
    }

    public function fetchOne($where){
        $sql = "SELECT * FROM {$this->tableName} WHERE {$where}";
        // echo $sql;
        // die();
        return $this->pdo->fetchOne($sql);
    }

    //更新方法
    public function update($data,$id){
        $str = "";
        foreach ($data as $key=>$value){ //构建通用的更新语句
            $str .= "$key='$value',";
        }
        //去除结尾逗号
        $str = rtrim($str,",");

        $sql = "UPDATE {$this->tableName} SET {$str} WHERE id={$id}";
//        echo $sql;
//        die();
        return $this->pdo->exec($sql);
    }

    //删除方法
    public function delete($id){
        $sql = "DELETE FROM {$this->tableName} WHERE id={$id}";
        return $this->pdo->exec($sql);
    }

    //获取所有记录数
    public function rowCount($where="2>1"){
        $sql = "SELECT * FROM {$this->tableName} WHERE $where";
        return $this->pdo->rowCount($sql);
    }

    //全选删除
    public function deleteAll($where){
        $sql = "DELETE FROM {$this->tableName} WHERE {$where}";
        return $this->pdo->exec($sql);
    }
}