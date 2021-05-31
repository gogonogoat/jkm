<?php
namespace Service;
use Service\DbConnection;
use YawnGoat\DataBase\MySql\SendRequest;

/*==============================
Desc:ゲームのセーブアンドロードに関するDB操作を行う
*/
class GameDb{
	/*==============================
	Desc:DBにコインをセーブする
	Note:
		SQLはphpmyadminからコピー＆Fixしたやつ
	*/
	public static function save($user_number,$user_coin){
		$dbh 		= DbConnection::connect();
		$prpr		= "INSERT INTO `coin` (`record_number`, `user_number`, `user_coin`) VALUES (NULL, :user_number, :user_coin);";
		$params		= [NULL,":user_number"=>$user_number,":user_coin"=>$user_coin,];
		$sample		= [NULL,":user_number"=>0,":user_coin"=>0,];
		$stmt		= SendRequest::request($dbh, $prpr, $params, $sample);
		//var_export($stmt);
		return("your coin is saved.");
	}
	/*==============================
	Desc:DBからコインをロードする
	Note:
		@ 書いたSQLの原文
			SELECT
				user_coin
			FROM
				coin
			WHERE
				record_number = (
					SELECT
						MAX(user_history.record_number)
					FROM(
						SELECT
							*
						FROM
							coin
						WHERE
							user_number = :user_number
					) AS user_history
				)
		@ SQLの草文
			coin テーブルにおいて指定した user_number を持つ行の群れを user_history とする
			user_history グループ内において record_number の最大値を取得する
			取得した record_number の値を持つ行の user_coin の値を取得する
	*/
	public static function load($user_number){
		$dbh 		= DbConnection::connect();
		$prpr		= "SELECT user_coin FROM coin WHERE record_number = ( SELECT MAX(user_history.record_number) FROM ( SELECT * FROM coin WHERE user_number = :user_number ) AS user_history );";
		$params		= [NULL,":user_number"=>$user_number];
		$sample		= [NULL,":user_number"=>0];
		$stmt		= SendRequest::request($dbh, $prpr, $params, $sample);
		$response	= $stmt->fetch();
		$coin		= $response["user_coin"];
		return $coin;
	}
}
?>