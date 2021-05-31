<?php
namespace YawnGoat\Array_\Session;
use YawnGoat\Array_\DestructiveOP\ByRefCruds;

/*==============================
use YawnGoat\Array_\Session\Operator as SessionOP;
Desc:
	セッションの値を管理するクラス
*/
class Operator{
/*==============================
Operator::session_start()
Desc:セッション開始されていなければセッションを開始する
Note:
	これを用いることでsession_start重複によるNoticeを回避できる
	名称変更
		旧：mightSessionStart
		新：session_start
*/
public static function session_start(){if(!isset($_SESSION)){session_start();}}
/*==============================
Operator::read($key)
Desc:セッションから対応するキーの値の取得を試みる
Param:
	Int,Str	key		配列キー
Return:
	Any		result	キーに対応する値
Note:
	定義されていない値等を参照した場合はNULLが返る
	名称変更
		旧：tryGetValue
		新：tryRead
*/
public static function read($key){
	self::session_start();
	$result = ByRefCruds::tryRead($_SESSION,$key);
	return $result;
}
/*==============================
Operator::setValue($key,$val)
Desc:
	※非推奨
	セッションに値をセットする
Param:
	Any		val		セットする値
	Int,Str	key		配列キー
Note:
*/
public static function setValue($key,$val){
	self::session_start();
	$_SESSION[$key] = $val;
}
/*==============================
Operator::create($key,$val)
Desc:
	セッションに値を新規作成する
	既にキーが存在する場合は失敗する
Param:
	Any		val		セットする値
	Int,Str	key		配列キー
Note:
*/
public static function create($key,$val){
	self::session_start();
	try{
		ByRefCruds::create($_SESSION,$key,$val);
	}catch(Exception $e){
		throw new Exception("セッション操作に失敗しました　指定されたキーが既に存在します");
	}
}
/*==============================
Operator::update($val,$key)
Desc:
	セッションの値を更新
	対象のキーが存在しない場合は失敗する
Param:
	Any		val		セットする値
	Int,Str	key		配列キー
Note:
*/
public static function update($key,$val){
	self::session_start();
	try{
		ByRefCruds::update($_SESSION,$key,$val);
	}catch(Exception $e){
		throw new Exception("セッション操作に失敗しました　指定されたキーは存在しません");
	}
}
/*==============================
Operator::delete($key)
Desc:
	セッションから値を取り除く
Param:
	Int,Str	key 配列キー
Note:
*/
public static function delete($key){
	self::session_start();
	ByRefCruds::update($_SESSION,$key,NULL);
}
/*==============================
Operator::tryDelete($key)
Desc:
	セッションから値を取り除こうとする
Param:
	Int,Str	key 配列キー
Note:
	☆アップデート案
	この機能自体ByRefCrudに入れてもいいかもしれない
	今は他のやるべきことがあるからやらないけどな
*/
public static function tryDelete($key){
	self::session_start();
	$isset = (null !== self::read($key));
	if($isset)ByRefCruds::update($_SESSION,$key,NULL);
}
/*==============================
Operator::steal($key)
Desc:
	セッションから対応するキーの値の取得を試みる
	その後、そのキーの値はNULLにより抹消される
Param:
	Int,Str	key		配列キー
Return:
	Any		result	キーに対応していた値
Note:
	定義されていない値等を参照した場合はNULLが返る
*/
public static function steal($key){
	$result = self::read($key);
	self::tryDelete($key);
	return $result;
}
/*==============================
Operator::init()
Operator::finish()
Desc:
	セッションを初期化/終了する
Note:
*/
public static function init()  {$_SESSION=NULL;}
public static function finish(){$_SESSION=NULL;}
//==============================
}
?>