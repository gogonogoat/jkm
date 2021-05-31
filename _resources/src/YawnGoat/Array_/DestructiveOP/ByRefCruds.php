<?php
namespace YawnGoat\Array_\DestructiveOP;
use \Exception;
/*==============================
use YawnGoat\Array_\DestructiveOP\ByRefCruds;
Desc:
*/
class ByRefCruds{
/*==============================
ByRefCrud::create($arr,$key,$val)
Desc:
	配列に指定したキーを追加する
	キーが既に存在している場合は例外発生
Param:
	Array	arr	操作対象の配列
	Int,Str	key	配列キー
	Any		val	キーに対応する値
*/
public static function create(Array &$arr, $key, $val=NULL){
	$isSet	= isset($arr[$key]);
	$errMsg	= "配列にキーを追加できません。指定されたキーは既に存在しています";
	if($isSet)throw new Exception($errMsg);
	$arr[$key] = $val;
}
/*==============================
ByRefCrud::tryRead($arr,$key)
Desc:
	対象の配列からキー指定して値を読み込む
	エラーが返った場合はNULLの値とする
Param:
	Array	arr		操作対象の配列
	Int,Str	key		配列キー
Return:
	Any		result	キーに対応した値
*/
public static function tryRead(Array &$arr,$key){
	try{
		$isExist = isset($arr[$key])==true;
		$result	 = $isExist ? $arr[$key]:NULL;
	}catch(Exception $e){
		$result	 = NULL;
	}
	return $result;
}

/*==============================
ByRefCrud::update($arr,$key,$val)
Desc:
	配列の値を更新する
Param:
	Any		val		セットする値
	Int,Str	key		配列キー
Note:
*/
public static function update(Array &$arr,$key,$val){
	$isNull = is_null($arr[$key]);
	if($isNull) throw new Exception("配列の値を更新できません。対象のキーが存在しません");
	$_SESSION[$key] = $val;
}
/*==============================

Desc:
	配列から値を取り除く
Param:
	Int,Str	key 配列キー
Note:
*/
public static function delete(Array &$arr,$key){
	$arr[$key] = NULL;
}
/*==============================
Keeper::steal($key)
Desc:
	配列から対応するキーの値の取得を試みる
	その後、そのキーの値は抹消される
Param:
	Int,Str	key		配列キー
Return:
	Any		result	キーに対応していた値
Note:
	定義されていない値等を参照した場合はNULLが返る
*/
public static function steal(Array &$arr,$key){
	$result = self::tryRead($arr,$key);
	self::delete($arr,$key);
	return $result;
}
//==============================
}
?>