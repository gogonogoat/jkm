<?php
namespace Service;
use Service\DbConnection;

//use YawnGoat\DataBase\MySql\Connection;
use YawnGoat\DataBase\MySql\SendRequest;
/*==============================
Desc:ユーザーのログイン認証を行う

接続してきたユーザーとセッション確立
送信されたID/PWを検証
合っているならば
	セッションへログインIDを書き込む
	ゲームのトップページのURLを返す
合っていないならば
	セッションへエラーメッセージを書き込む
	ログインページへリダイレクト
*/
class AuthDb{
	/*==============================
	Desc:入力されたIDの存在有無を取得
	Note:
	*/
	public static function isExistId($id){
		$dbh		= DbConnection::connect();
		$prpr		= "SELECT user_id FROM auth where user_id=:user_id;";
		$params		= [NULL,":user_id"=>$id];
		$sample		= [NULL,":user_id"=>""];
		$stmt		= SendRequest::request($dbh, $prpr, $params, $sample);
		$response	= $stmt->fetchAll();
		$isExist	= isset($response[0]);//ヒットした行が存在する
		$result		= $isExist;
		return $result;
	}
	/*==============================
	Desc:入力されたIDに対応する番号を取得
	Note:
	*/
	public static function IdToNumber($id){
		$dbh		= DbConnection::connect();
		$prpr		= "SELECT user_number FROM auth where user_id=:user_id;";
		$params		= [NULL,":user_id"=>$id];
		$sample		= [NULL,":user_id"=>""];
		$stmt		= SendRequest::request($dbh, $prpr, $params, $sample);
		$response	= $stmt->fetch();
		$result		= $response["user_number"];
		return $result;
	}
	/*==============================
	Desc:DB接続
	Note:DB接続は認証する時だけではないので独立化させた
	*/
	/*
	private static function connect(){
		$dbname		= "jkm";
		$host		= "localhost";
		$charset	= "utf8";
		$user		= "root";
		$password	= "";
		$pdo = Connection::access($dbname,$host,$charset,$user,$password);
		return $pdo;
	}
	*/
	/*==============================
	Desc:入力されたIDに対するハッシュ化PWを取得
	Note:
		メソッド呼び出し元は文字数で合致の合否を検証する
	*/
	public static function loadHash($id){
		$dbh		= DbConnection::connect();
		$prpr		= "SELECT user_pw_hash FROM auth where user_id=:user_id;";
		$params		= [NULL,":user_id"=>$id];
		$sample		= [NULL,":user_id"=>""];
		$stmt		= SendRequest::request($dbh, $prpr, $params, $sample);
		$hash		= $stmt->fetch()["user_pw_hash"];
		$result		= $hash;
		return $result;
	}
}
?>