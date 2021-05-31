<?php
namespace Service;
use YawnGoat\Array_\Session\Operator as SessionOP;

/*==============================
use Service\UserSession;
Desc:ユーザーのセッション情報を取得する
*/
class UserSession{
	/*==============================
	Desc:セッションからログイン中のユーザー番号を取得
	*/
	public static function getUserNumber(){
		$result = SessionOP::read("user_number");
		return $result;
	}
	/*==============================
	Desc:セッションへログイン中のユーザー番号を設定
	*/
	public static function setUserNumber($user_number){
		$result = SessionOP::setValue("user_number",$user_number);
		return $result;
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
		echo($coin);
	}
}
?>