<?php
namespace Admin\Model;
use \Frame\Libs\BaseModel;

final class IndexModel extends BaseModel
{
    public function fetchAll(){
        //构建查询的sql语句
        $sql = "SELECT * FROM users ORDER BY id";
        //执行sql语句并返回结果
        return $this->pdo->fetchAll($sql);
    }
}