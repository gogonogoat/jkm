<?php
namespace YawnGoat\Array_\PostData;
use YawnGoat\Array_\DestructiveOP\ByRefCruds;
/*==============================
use YawnGoat\Array_\PostData\Finder;
Desc:
	
*/
class Finder{
/*==============================
Finder::read($key)
Desc:ポストデータから対応するキーの値の取得を試みる
Param:key	String	キー文字列
Note:定義されていない値等を参照した場合はNULLが返る
*/
public static function read($key){
	$result = ByRefCruds::tryRead($_POST,$key);
	return $result;
}

/*==============================
Finder::isExist()
Desc:ポストデータの有無を取得する
*/
public static function isExist(){
	$result = empty($_POST)==false;
	return $result;
}
//==============================
}
?>