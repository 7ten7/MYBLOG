<?php
namespace Admin\Model;

use Frame\Libs\BaseModel;

class ArticleModel extends BaseModel
{
    protected $tableName = 'articles';

    public function fetchAllJoin($startrow,$pagesizr)
    {
        //构建联合查询的sql语句
        $sql  = "SELECT articles.*,categorys.name,users.username FROM {$this->tableName} ";
        $sql .= "LEFT JOIN categorys ON articles.c_id=categorys.id ";
        $sql .= "LEFT JOIN users ON articles.u_id=users.id ORDER BY articles.id DESC ";
        $sql .= "LIMIT {$startrow},{$pagesizr}";
        return $this->pdo->fetchAll($sql);
    }
}