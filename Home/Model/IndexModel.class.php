<?php
namespace Home\Model;
use \Frame\Libs\BaseModel;

//定义最终的首页模型类并继承基础模型类
final class IndexModel extends BaseModel
{
    public function fetchAll(){
        //构建查询的sql语句
        $sql = "SELECT * FROM users ORDER BY id";
        //执行sql语句并返回结果
        return $this->pdo->fetchAll($sql);
    }

    //删除记录
    public function delete($id){
        $sql = "DELETE FROM users WHERE id={$id}";
        return $this->pdo->exec($sql);
    }
}