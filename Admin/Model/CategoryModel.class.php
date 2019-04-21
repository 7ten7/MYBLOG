<?php

namespace Admin\Model;


use Frame\Libs\BaseModel;

class CategoryModel extends BaseModel
{
    protected $tableName = "categorys";

    public function categoryList($arrs,$level=0,$pid=0){
        static $category = array();
        foreach ($arrs as $arr){

            //如果栏目的pid等于参数pid 则添加到新数组中
            if($arr['pid'] == $pid){
                $arr['level'] = $level; //当前菜单的等级
                $category[] = $arr;
                $this->categoryList($arrs,$level+1,$arr['id']); //递归调用，逐级找出菜单
            }
        }
        //最终返回有序排列的菜单
        return $category;
    }

}