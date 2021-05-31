<?php
namespace YawnGoat\Document\Encode;
/*==============================
use YawnGoat\Document\Encode\AntiBom;
Desc:
	BOMへの対策を行うクラス
	JSONエンコする時とかBOM有りUTF8だとNULLが返る事が
	気に触ったので作成する事にした
*/
class AntiBom{
/*==============================
YawnGoat/AntiBom::deleteBom($str)
Desc:BOMを取り除くいた文字列を取得する
Param:
	str	BOMがあるかもしれない文字列
Return:
	String	BOMなし文字列
*/
public static function deleteBom(String $str){
	$bom	= hex2bin('EFBBBF');
	$result	= preg_replace("/^{$bom}/",'',$str);
	return $result;
}
/*==============================
YawnGoat/AntiBom::mb_convert_encoding($str)
Desc:アンチボムUTF8エンコード
Param:
	str	BOMがあったりUTF8ではないかもしれない文字列
Return:
	String	BOMなしUTF8文字列
*/
public static function mb_convert_encoding(String $str){
	$enc	= mb_convert_encoding($str,'UTF8','ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
	$result	= self::deleteBom($enc);
	return $result;
}
/*==============================
YawnGoat/AntiBom::detonation($str)
Desc:BOMをジョークテキストに置き換える
Param:
	str	BOMがあるかもしれない文字列
Return:
	String	BOMなし文字列
Note:
	クラスの動作確認を主な目的としたメソッド。
*/
public static function detonation(String $str){
	$bom	= hex2bin('EFBBBF');
	//echo"@@@@";
	$result	= preg_replace("/^{$bom}/",'[[[BOMB!!!]]]',$str);
	return $result;
}
//==============================
}
?>