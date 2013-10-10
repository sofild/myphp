<?php
/******************************************
 * @package: Jblog
 * @file: vdcode.php
 * @link: http://www.ijesse.cn
 * @copyright: Jblog (C) 2008 Jblog team.
 * @author: Jesse Lee (lisijie86@gmail.com)
 * @update: 2008-10-23
******************************************/

session_start();

$sessionvar = 'vdcode';	//Session��������
$code_type = 3;	//��֤������

$width = 70;	//ͼ����
$height = 20;	//ͼ��߶�

if (function_exists('imagecreate')) {

	if ( $code_type == 3 ) {

		//����4���ַ�������ַ���
		$str = "0123456789";
		$len = strlen($str);
		$code = array();
		for ($i=0; $i<4; $i++) {
			$code[] = $str{mt_rand(0,$len-1)};
		}

		$_SESSION[$sessionvar] = strtolower(implode('',$code));

		$img = ImageCreate($width,$height);	//����ͼ��
		ImageColorAllocate($img,255,255,255); //��䱳��

		//�����ɫ
		for ($i=0; $i<100; $i++) {
			$pxcolor = ImageColorAllocate($img,mt_rand(100,255),mt_rand(100,255),mt_rand(100,255));
			ImageSetPixel($img,mt_rand(0,$width),mt_rand(0,$height),$pxcolor);
		}

		//���Ʊ߿�
		$bordercolor = ImageColorAllocate($img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
		ImageRectangle($img,0,0,$width-1,$height-1,$bordercolor);

		//д����֤������
		$offset = round($width/4/2);
		$h1 = 0;
		$h2 = round($height/3);
		foreach ($code as $char) {			
			$textcolor = ImageColorAllocate($img,mt_rand(0,250),mt_rand(0,150),mt_rand(0,250));
			ImageChar($img,5,$offset + mt_rand(-5,round($width/4/3)), mt_rand($h1, $h2),$char,$textcolor);
			$offset += round($width/4)-3;
		}
	//����+�ַ�����֤��
	}elseif ( $code_type == 1 ) {

		//����4���ַ�������ַ���
		$str = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$len = strlen($str);
		$code = array();
		for ($i=0; $i<4; $i++) {
			$code[] = $str{mt_rand(0,$len-1)};
		}

		$_SESSION[$sessionvar] = strtolower(implode('',$code));

		$img = ImageCreate($width,$height);	//����ͼ��
		ImageColorAllocate($img,255,255,255); //��䱳��

		//�����ɫ
		for ($i=0; $i<100; $i++) {
			$pxcolor = ImageColorAllocate($img,mt_rand(100,255),mt_rand(100,255),mt_rand(100,255));
			ImageSetPixel($img,mt_rand(0,$width),mt_rand(0,$height),$pxcolor);
		}

		//���Ʊ߿�
		$bordercolor = ImageColorAllocate($img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
		ImageRectangle($img,0,0,$width-1,$height-1,$bordercolor);

		//д����֤������
		$offset = round($width/4/2);
		$h1 = 0;
		$h2 = round($height/3);
		foreach ($code as $char) {			
			$textcolor = ImageColorAllocate($img,mt_rand(0,250),mt_rand(0,150),mt_rand(0,250));
			ImageChar($img,5,$offset + mt_rand(-5,round($width/4/3)), mt_rand($h1, $h2),$char,$textcolor);
			$offset += round($width/4)-3;
		}
	
	//����ѧ������֤��
	} elseif ( $code_type == 2 ) {

		$operator = '+-*';	//�����

		$code = array();
		$code[] = mt_rand(1,9);
		$code[] = $operator{mt_rand(0,2)};
		$code[] = mt_rand(1,9);
		$code[] = $operator{mt_rand(0,2)};
		$code[] = mt_rand(1,9);
		$codestr = implode('',$code);
		eval("\$result = ".implode('',$code).";");
		$code[] = '=';

		$_SESSION[$sessionvar] = $result;

		$img = ImageCreate($width,$height);
		ImageColorAllocate($img, mt_rand(230,250), mt_rand(230,250), mt_rand(230,250));
		$color = ImageColorAllocate($img, 0, 0, 0);

		$offset = round($width/6/2);
		$h1 = 0;
		$h2 = round($height/6);
		foreach ($code as $char) {
			
			$txtcolor = ImageColorAllocate($img, mt_rand(0,255), mt_rand(0,150), mt_rand(0,255));
			ImageChar($img, mt_rand(3,5), $offset + mt_rand(-5,round($width/6/3)), mt_rand($h1, $h2), $char, $txtcolor);
			$offset += round($width/6)-3;

		}
		for ($i=0; $i<100; $i++) {
			
			$pxcolor = ImageColorAllocate($img, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
			ImageSetPixel($img, mt_rand(0,$width), mt_rand(0,$height), $pxcolor);
		}

	}
	
	header("pragma:no-cache\r\n");
	header("Cache-Control:no-cache\r\n");
	header("Expires:0\r\n");
	//���ϵͳ֧�ֵ��ļ����ͣ����ȼ�ΪPNG->JPEG->GIF
	if (ImageTypes() & IMG_PNG) {
		header('Content-Type:image/png');
		ImagePNG($img);
	} elseif (ImageTypes() & IMG_JPEG) {
		header('Content-Type:image/jpeg');
		ImageJPEG($img);
	} else {
		header('Content-Type:image/gif');
		ImageGif($img);
	}

} else {

	//��֧��GD�⣬�����Ĭ����֤��ABCD
	$_SESSION[$sessionvar] = 'abcd';
	header('Content-Type:image/jpeg');
	$fp = fopen('images/vdcode.jpg','rb');
	echo fread($fp,filesize('images/vdcode.jpg'));
	fclose($fp);

}
?>