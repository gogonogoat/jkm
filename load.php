<?php
include_once 'autoLoader.php';
use Service\GameDb;
use Service\UserSession;

/*
use Service\Auth;
use Service\AuthStatusCode;
use YawnGoat\Array_\Session\Operator as SessionOP;
use YawnGoat\Document\Path\Rooter;
*/

/*==============================
Desc:
	ログイン中のアカウントに保存されたユーザーデータを返すクラス
	コード最下部において当クラスが直接実行される。
Note:
		@ 検証済
		$user_number = 1;
		$user_coin	 = 100;
		GameDb::save($user_number,$user_coin);
		
		@ 検証済
		$user_number = 2;
		GameDb::load($user_number);
*/
class load{
	/*==============================
	Desc:このクラスのメイン処理部分
	Note:
		セッション参照してユーザー番号を参照する
		取得したユーザー番号に応じてロードした情報を返す
		ユーザー番号が確認できなければ 500 Internal Server Error を発生させる
	*/
	public static function main(){
		//ログイン中のユーザー番号を取得
		$user_number = UserSession::getUserNumber();
		//ユーザー番号に該当するデータを取得
		$result = load::userData($user_number);
		//値を出力
		echo($result);
	}
	/*==============================
	Desc:指定されたユーザー番号に該当するデータのロードを試みる
	*/
	private static function userData($user_number){
		
		try{
			//ユーザー番号が指定されているか
			$isAssign = (NULL !== $user_number);
			//数値にキャスト
			$user_number = (int) $user_number;
			//データ取得
			$result = $isAssign ?
				GameDb::load($user_number):
				NULL;
		//失敗したらNULLを返す
		}catch(Exception $e){
			$result = NULL;
		}
		
		load::failedFix($result);
		return $result;
	}
	/*==============================
	Desc:ロードに失敗した場合に
	500 Internal Server Error を発生させる
	*/
	private static function failedFix($accept){
		$isFailed = (NULL === $accept);
		if($isFailed){
			http_response_code(500);
		}
	}
}
load::main();
?>