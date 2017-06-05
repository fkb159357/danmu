<?php
class Util extends DIEntity {

    /**
     * 生成验证码图片
     * 调用之前需：header("Content-type: image/PNG"); 
     * 调用完成后：imagepng($im); imagedestroy($im);//输出并释放图片所占内存
     */
    static function genValicode($code, $w = 60, $h = 20){
        $im = imagecreate($w, $h); 
        $black = imagecolorallocate($im, 0, 0, 0); 
        $gray = imagecolorallocate($im, 200, 200, 200); 
        $bgcolor = imagecolorallocate($im, 255, 255, 255); 
        //填充背景 
        imagefill($im, 0, 0, $gray); 

        //画边框 
        imagerectangle($im, 0, 0, $w-1, $h-1, $black); 

        //随机绘制两条虚线，起干扰作用 
        $style = array ($black,$black,$black,$black,$black, 
            $gray,$gray,$gray,$gray,$gray 
        ); 
        imagesetstyle($im, $style); 
        $y1 = rand(0, $h); 
        $y2 = rand(0, $h); 
        $y3 = rand(0, $h); 
        $y4 = rand(0, $h); 
        imageline($im, 0, $y1, $w, $y3, IMG_COLOR_STYLED); 
        imageline($im, 0, $y2, $w, $y4, IMG_COLOR_STYLED);

        //在画布上随机生成大量黑点，起干扰作用; 
        for ($i = 0; $i < 80; $i++) { 
            imagesetpixel($im, rand(0, $w), rand(0, $h), $black); 
        } 
        //将数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成 
        $strx = rand(3, 8); 
        for ($i = 0; $i < 5; $i++) { 
            $strpos = rand(1, 6);
            imagestring($im, 5, $strx, $strpos, substr($code, $i, 1), $black); 
            $strx += rand(8, 12); 
        }
        return $im;
    }

}