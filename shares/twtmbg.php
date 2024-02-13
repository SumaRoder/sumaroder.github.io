<?php
class need{
    public static function request() {
		$explode = explode('&', $_SERVER['QUERY_STRING']);
		// print_r($explode);
		$Request = [];
		foreach($explode as $value) {
			$explod = explode('=', $value, 2);
			$Request[@$explod[0]] = @$explod[1] ?: null;
			//print_r($explod);
			unset($explod);
		}
		foreach($Request as $k=>$v) {
			if(!empty($v)) {
				$fileType = mb_detect_encoding($v, array('UTF-8','GBK','LATIN1','BIG5','GB2312')) ;
				if($fileType != 'UTF-8') {
					$data = mb_convert_encoding($v, 'utf-8', $fileType);
				}else{
					$data = $v;
				}
			}else{
				$data = $v;
			}
			$array[$k] = urldecode((String) $data);
			unset($data, $fileType);
		}
		// print_r($array);
		return array_merge($array, $_POST);
	}
}
$request = need::request();
$msg = isset($request['msg']) ? $request['msg'] : false;
$size=16;//字体大小
$font="/storage/emulated/0/apache2/font/jetbrainsmono.ttf";//字体文件引入
$img = imagecreatetruecolor(500,400);//建立一张图片，设置宽高
$bg = imagecolorallocatealpha($img,0,0,0,127);//设置图片透明背景
$color = imagecolorallocate($img,0,0,0); //设置字体颜色
imagealphablending($img, false);//显示透明背景
imagefill($img,0,0,$bg);//填充背景
imagefttext($img,$size,0,0,31,$color,$font,$msg);
imagesavealpha($img,true);
header('Content-Type: image/png');//header信息
imagepng($img);//输出图片