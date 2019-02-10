<?php
//声明命名空间
namespace Frame\Vendor;

//包含原始的Smarty类
require_once (FRAME_PATH."Vendor".DS."smarty-3.1.33".DS."libs".DS."Smarty.class.php");

//定义最终的自己的smarty类，并基础原始的smarty类
final class Smarty extends \Smarty
{
    //只需基础，不需要写内容
}