<?php
namespace Service;
use YawnGoat\DataBase\MySql\Connection;
use YawnGoat\Server\Finder as ServerInfo;
/*==============================
Desc:JKMのDB鯖へ接続を行う
Note:
	[1]
		環境ごとの設定一覧については
		本番環境前提ならCSVなり読んで来るのがベターだと思う
		この実際に運用しない前提の環境においては
		ファイル分ける手間がリスクになるので分けない。

*/
class DbConnection{
	/*==============================
	Desc:DB接続
	Note:
	*/
	public static function connect(){
		//環境ごとのDB接続情報一覧 Note[1]参照
		$props = [
			'win'=>[
				'dbname'	=> "",
				'host'		=> "",
				'charset'	=> "",
				'user'		=> "",
				'password'	=> "",
			],
			'nix'=>[
				'dbname'	=> "",
				'host'		=> "",
				'charset'	=> "",
				'user'		=> "",
				'password'	=> "",
			]
		];
		
		//環境の取得
		$env        = ServerInfo::osIsWin();
		//環境から設定のインデックスを取得
		$index      = $env ? 'win':'nix';
		//環境に応じた設定情報を取得
		$prop       = $props[$index];
		//各パラメータに割り振って接続
		$dbname		= $prop["dbname"];
		$host		= $prop["host"];
		$charset	= $prop["charset"];
		$user		= $prop["user"];
		$password	= $prop["password"];
		$pdo = Connection::access($dbname,$host,$charset,$user,$password);
		return $pdo;
	}
}
?>