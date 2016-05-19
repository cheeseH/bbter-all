<?php
namespace Org\Util;

import("TokenInfo");
function hexToStr($hex)//十六进制转字符串
{   
	$string=""; 
	for($i=0;$i<strlen($hex)-1;$i+=2)
	$string.=chr(hexdec($hex[$i].$hex[$i+1]));
	return  $string;
}
class BoxTokenHelper{
	static private $expire = 604800;
	public static function verifyToken($token){
		$tokenString= hexToStr($token);
		$jsonData = \Think\Crypt\Driver\Base64::decrypt($tokenString,'easonchan');
		$array = json_decode($jsonData,true);
		if($array == null){
			return false;
		}
	
		$created = $array['created'];
		$expire = BoxTokenHelper::$expire;
		$now = time();
		if($created+$expire<$now)
			return false;
		$info = new TokenInfo($array['userId'],$array['departmentId'],$array['groupId'],$array['created']);
		$array['created'] = time();
		$tokenString =  strToHex(\Think\Crypt\Driver\Base64::encrypt(json_encode($array),'easonchan'));
		return array('str'=>$tokenString,'info'=>$info);
	} 
}
