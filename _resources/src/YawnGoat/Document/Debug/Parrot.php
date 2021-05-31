<?php
namespace YawnGoat\Document\Debug;
/*==============================
use YawnGoat\Document\Debug\Parrot;
Desc:デバッグ用文字列の出力を補助してくれるお供
*/
class Parrot{
/*==============================
Parrot::br();
Desc:改行する
*/
public static function br(String $str=''){echo($str.'<br>');}
public static function mes(String $str=''){self::br($str);}
/*==============================
Parrot::line();
Desc: **********<br> のような文字列を出力
*/
public static function line( String $ch='*', Int $rep=10 ){
	$str = str_repeat($ch,$rep).'<br>';
	echo($str);
}
/*==============================
Parrot::mesLine($title);
Desc: *****タイトル*****<br> のような文字列を出力
*/
public static function mesLine( String $title, String $ch='*', Int $rep=5 ){
	$bar = str_repeat($ch,$rep);
	$str = $bar.$title.$bar.'<br>';
	echo($str);
}
/*==============================
Parrot::pair($val);
Desc:前置きと指定された値を改行込みで出力
*/
public static function pair($name,$val,$exp=false){
	$str = self::echo($val,true);
	$result = $name.':'.$str;
	if($exp)return $result;
	echo($result);
}
/*==============================
Parrot::expair($val);
Desc:前置きと指定された値を改行込みで出力
*/
public static function expair($name,$val,$exp=false){
	$str = self::var_export($val,true);
	$result = $name.':'.$str;
	if($exp)return $result;
	echo($result);
}
/*==============================
Parrot::echo($val);
Desc:指定された値を改行込みで出力
*/
public static function echo($val,$exp=false){
	try{
		$str = (String) $val .'<br>';
	}catch(Exception $e){
		$str = self::var_export($val,true);
	}
	if($exp)return $str;
	echo($str);
}
/*==============================
Parrot::var_export($val);
Desc:指定された値のエクスポート情報を改行込みで出力
*/
public static function var_export($val,$exp=false){
	$result = var_export($val,true) .'<br>';
	if($exp)return $result;
	echo($result);
}
//==============================
}
?>