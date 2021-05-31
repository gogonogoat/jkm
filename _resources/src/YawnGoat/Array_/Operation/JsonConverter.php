<?php
namespace YawnGoat\Array_\Operation;
use YawnGoat\Document\Encode\AntiBom;
/*==============================
use YawnGoat\Array_\Operation\JsonConverter;
Desc:
	JSONの入出力を行うクラス
*/
class JsonConverter{
/*==============================
JsonConverter::toArray($filePath)
Desc:
	指定したファイルパスのJSONから連想配列を得る
Param:
	filePath	String	JSONのファイルパス
Return:
	Array		result	JSONが展開された連想配列
Note:
	今考えたけどコレおかしくない？
	AntiBom::mb_convert_encodingってさ
	基本的にUTF8にするのが大半だろ？
	んーこれはー
	デフォルト引数を与える感じにした方がスマートかなー？
	
	名称変更
		旧：fileToArray
		新：toArray
*/
public static function toArray(String $filePath){
	//パスで指定されたファイルを全て読み込む
	$str	= file_get_contents($filePath);
	//(BOMなし)UTF8にエンコードする
	$json	= AntiBom::mb_convert_encoding($str);
	//上記をJSON文字列として連想配列に変換して返す
	$result	= json_decode($json,true);
	return $result;
}
/*==============================
JsonConverter::toJson($filePath,$arr)
Desc:
	指定したファイルパスにJSON文字列に変換した配列を保存する
Param:
	String	filePath	保存先のファイルパス
	Array	arr			JSON化する配列
Return:
	Bool	result	保存できているならば真を返す
Note:
	名称変更
		旧：arrayToFile
		新：toJson
*/
public static function toJson(String $filePath, Array $arr){
	//ファイルがなければ新規作成
	touch($filePath);
	//配列をJSON文字列に変換
	$json		= json_encode($arr);
	//指定されたパスに上記文字列を記したファイルを保存する
	$isSaved	= file_put_contents($filePath,$json);
	//ファイルの保存に失敗していなければ真を返す
	$result		= !($isSaved === false);
	return $result;
}
//==============================
}
?>