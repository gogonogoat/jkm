<?php
namespace YawnGoat\DataBase\MySql;
use \PDO;
/*==============================
use YawnGoat\DataBase\MySql\Connection;
Desc:
	DB(MySQL)への接続を得るクラス
Note:
	DB作る時の覚書き
	・日本語が含まれるカラム文字コードについて
		utf8mb4_unicode_ci を選ぼう。
		その他の初期値選んでると文字化けしてクエスチョンマークになる。
		投稿内容が????になったらこれを見直そう。
*/
class Connection{
/*
Connection::access($dbname,$host,$charset,$user,$password,$option)
Desc:新規コネクションを得る
Note:接続失敗時は一律NULLを得る
*/
public static function access($dbname,$host,$charset,$user,$password,$option=self::OPTION){
	//DSNの取得
	$dsn = self::dsn($dbname,$host,$charset);
	//DSNから新規接続を得て結果を返す
	$pdo = self::tryNewPDO($dsn,$user,$password,$option);
	return $pdo;
}
/*==============================
Desc:パラメータを元にDSN文字列を取得する
*/
protected static function dsn($dbname,$host,$charset){
	$prefix		= "mysql:";
	$dbname_	= "dbname="		.$dbname	.";";
	$host_		= "host="		.$host		.";";
	$charset_	= "charset="	.$charset;
	$dsn 		= $prefix .$dbname_ .$host_ .$charset_;
	return $dsn;
}
/*==============================
Desc:新規コネクションの取得を試行する
Note:接続失敗時は一律NULLを得る
*/
protected static function tryNewPDO($dsn,$user,$password,$option){
	try{
		//グローバルスコープ配下のPDOクラスのインスタンス作成
		$result = new PDO($dsn,$user,$password,$option);
	}catch(Exception $e){
		$result = NULL;
	}
	return $result;
}
/*==============================
Desc:PDO標準オプション
*/
const OPTION = [
	PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC
];
//==============================
}

?>