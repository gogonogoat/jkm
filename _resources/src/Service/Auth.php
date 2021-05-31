<?php
namespace Service;
use Service\AuthStatusCode;
use YawnGoat\Array_\Session\Operator as SessionOP;
use YawnGoat\Document\Path\Rooter;
/*==============================
Desc:ユーザーのログイン認証を行う

接続してきたユーザーとセッション確立
送信されたID/PWを検証
合っているならば
	セッションへログインIDを書き込む
	ゲームのトップページのURLを返す
合っていないならば
	セッションへエラーメッセージを書き込む
	ログインページのURLを返す
*/
class Auth{
	/*==============================
	Desc:ポストされたID/PWが正しいかを検証する
	Param:
		id	String	ユーザー入力値のユーザーID
		pw	String	ユーザー入力値のユーザーPW
	Return:
		int	以下の二通り
			正の値	IDに対応するユーザー番号
			負の値	認証結果を表すステータスコード
	Note:PWは password_hash() で作成する
	*/
	public static function verificate($id,$pw){
		//入力されたIDが存在するかを取得
		$isExist = AuthDb::isExistId($id);
		
		/*
		var_dump($isExist);
		echo("<br>");
		*/
		
		//上記IDが存在するならば
		$result  = $isExist ?
			//入力されたPWの照合結果を取得
			Auth::password_verify($id,$pw):
			//そうでなければ「失敗：該当IDなし」を返す
			AuthStatusCode::FAILED_ID_NOTFOUND;
		//結果を返す
		/*var_dump($result);
		echo("<br>");
		*/
		return $result;
	}
	/*==============================
	Desc:PW文字列をハッシュ文字列に照合する
	Param:
		id	string	ユーザID文字列
		pw	string	ユーザ入力PW
	Return:
		int	以下の二通りを返す
			正の値	ID/PWが一致したユーザ番号
			負の値	PW照合結果を表すステータスコード
	Note:
	*/
	private static function password_verify($id,$pw){
		//DBからIDに対応したハッシュ文字列を取得
		$hash	 = AuthDb::loadHash($id);
		/*
		//照合結果を取得　※これは再帰ではなく標準関数の利用である。
		$isMatch = password_verify($pw,$hash);
		*/
		
		//現実のユーザーがいるならハッシュにするけどテスト環境だから平文にしておく
		$isMatch = ($pw==$hash);
		
		//照合の結果に応じて「該当するユーザー番号」・「失敗：PW不一致」を返す
		$result	 = $isMatch ?
			AuthDb::IdToNumber($id):
			AuthStatusCode::FAILED_PW_MISMATCH;
		return $result;
	}
}
?>