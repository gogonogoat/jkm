<?php
namespace YawnGoat\DataBase\MySql;
use YawnGoat\Array_\Operation\Prototype2D;
use Exception;
/*==============================
use YawnGoat\DataBase\MySql\ValidateParams;
Desc:
	事前にバインドする値をサンプルと比較検証してから
	プリペアドステートメントを実行するクラス
*/
class ValidateParams{
//==============================
//検証不適格の場合に例外を投げるか
const IS_THROW = true;
/*==============================
Desc:
Param:
	dbh			PDO		データベースハンドラ
	prepare		String	SQL文
	sampleDict	Array	パラメータ検証用モデル
	paramDict	Array	入力パラメータ配列
Return:
	Statement	SQL文が実行されたステートメント
	NULL		SQL文が実行されなかった場合に返る
Exception:
	KeyTypeMismatchException
		入力パラメータ配列のデータ型が
		検証用モデルに一致していない場合に発生
Note:
*/
public static function isValid(Array $params,Array $sample){
	//入力パラメータとサンプルモデルの比較
	$isPretty	= self::isCongruence($sample,$params);
	//[0]はNULLであるかを検証
	$isFormaly	= self::isNullZero($params);
	return true;
}
/*==============================
Desc:
	サンプル同士で全てのキーが等しく、
	キーに対するデータ型も等しいならば真を得る
	そうでなければ例外を投げる
*/
protected static function isCongruence($sampleA,$sampleB){
	/*
	var_dump($sampleA);
	echo("<br>");
	var_dump($sampleB);
	echo("<br>");
	*/
	
	$box = [$sampleA,$sampleB];
	$isShapeless = Prototype2D::isEvenKeysType($box)==false;
	$throw = (self::IS_THROW and $isShapeless);
	switch(true){
		case $throw:
			throw new Exception("入力パラメータが検証用モデルに一致しません");
		case $isShapeless:
			return false;
		default:
			return true;
	}
}
/*===============================
Desc:
	サンプルの配列で[0]がNULLならば真を得る
	そうでなければ例外を投げる
*/
protected static function isNullZero($sample){
	$isShapeless = isset($sample[0]);
	$throw = (self::IS_THROW and $isShapeless);
	switch(true){
		case $throw:
			throw new Exception("入力パラメータ内から不要な値が検出されました");
		case $isShapeless:
			return false;
		default:
			return true;
	}
}
//==============================
}?>