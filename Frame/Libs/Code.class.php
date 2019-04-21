<?php

namespace Frame\Libs;


class Code
{
    private $w; //宽
    private $h; //高
    private $len; //长度
    private $font; //字体
    private $lineNum; //干扰线数量
    private $img; //画布资源
    private $code; //验证码值
    private $fontSize; //字体大小

    public function __construct($w=150, $h=45, $len=4,$fontSize=30, $lineNum=5)
    {
        $this->w = $w;
        $this->h = $h;
        $this->len = $len;
        $this->font = './Public/Admin/Fonts/msyh.ttc';
        $this->lineNum = $lineNum;
        $this->fontSize = $fontSize;
        $this->code = $this->getCode();
        $this->img = $this->getimg();
        $this->getimg();
        $this->putcolor();
        $this->fillCode();
        $this->fillline();
        $this->putCode();
    }
    private function getimg(){
        //1.创建画布
        return imagecreatetruecolor($this->w, $this->h);
    }
    private function putcolor(){
        //2.分配颜色
        $color = imagecolorallocate($this->img, mt_rand(210, 250), mt_rand(210, 250), mt_rand(210, 250));
        //3.填充颜色
        imagefill($this->img, 0, 0, $color);
    }

    private function fillCode(){
        //4.绘制字母
        for ($i = 0; $i < strlen($this->code); $i++) {
            $color = imagecolorallocate($this->img, mt_rand(100, 150), mt_rand(100, 150), mt_rand(100, 150));
            imagettftext($this->img, $this->fontSize, mt_rand(-10, 10), 25 * ($i + 1), 40, $color, $this->font, $this->code[$i]);
        }
    }

    private function fillline(){
        //5.绘制干扰线
        for ($i = 0; $i < $this->lineNum; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imageline($this->img, mt_rand(0,150), mt_rand(0, 60), mt_rand(0, 150), mt_rand(0, 60), $color);
        }
    }

    private function putCode(){
        //6.输出
        header('content-type:image/jpg');
        imagejpeg($this->img);
        imagedestroy($this->img);
    }

    //生成随机字符串
    private function getCode()
    {
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < $this->len; $i++) {
            $code .= $charset[mt_rand(0, 61)];
        }
        //将随机产生的验证码写入session中
        $_SESSION['code'] = $code;
        return $code;
    }
}