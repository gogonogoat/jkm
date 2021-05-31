<?php
include_once 'autoLoader.php';
use Service\Auth;
use Service\AuthStatusCode;
use Service\UserSession;

use YawnGoat\Array_\Session\Operator as SessionOP;
use YawnGoat\Document\Path\Rooter;

/*==============================
Desc:
	認証先URLとして扱われるファイル。
	コード最下部において当クラスが直接実行される。
*/
class Login{
	//リダイレクト先ロケーション
	const LOCATE_GAME = "Location: game.php";
	const LOCATE_FAIL = "Location: index.php";
	
	/*==============================
	Desc:このクラスのメイン処理部分
	*/
	public static function main(){
		//セッションを初期化しておく
		SessionOP::init();
		//認証結果を要求
		$way = Login::requestStatus();
		//返された要求結果に応じて分岐
		Login::crossroad($way);
	}
	/*==============================
	Desc:PostされているID/PWから認証結果を返す
	*/
	private static function requestStatus(){
		//認証結果を得る
		$id		= $_POST['user_id'];
		$pw		= $_POST['user_pw'];
		$result	= Auth::verificate($id,$pw);
		return $result;
	}
	/*==============================
	Desc:認証結果ステータスコードに応じた処理を実行する
	Param:
		way Int AuthStatusCode::XXX クラス定数の入力を想定
	*/
	private static function crossroad($way){
		//認証結果に応じて処理を分岐
		switch($way){
			//失敗
			case AuthStatusCode::FAILED_ID_NOTFOUND:
				Login::failed("指定されたIDは見つかりませんでした");
				break;
			case AuthStatusCode::FAILED_PW_MISMATCH:
				Login::failed("PWが一致していません");
				break;
			//成功
			default:
				Login::success($way);
		}
	}
	/*==============================
	Desc:認証成功時の処理
	*/
	private static function success($user_number){

		
		/*$key = "user_number";
		$val = $user_number;
		SessionOP::setValue($key,$val);*/
		//セッションにログインしたユーザー番号を書き込む
		UserSession::setUserNumber($user_number);
		
		//ゲームページにリダイレクト
		$locate = Login::LOCATE_GAME;//."?".date(YmdHis);
		header($locate);
		//echo("OK");
	}
	/*==============================
	Desc:認証失敗時の処理
	Param:
		phrase	String	ユーザー向けエラーメッセージx
	*/
	private static function failed($phrase){
		//セッションに情報を書き込む
		$key = "msg";
		$val = $phrase;
		SessionOP::setValue($key,$val);
		//ログインページにリダイレクト
		$locate = Login::LOCATE_FAIL;
		header($locate);
	}
}
Login::main();
?>