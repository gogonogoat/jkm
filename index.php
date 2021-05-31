<?php
include_once 'autoLoader.php';

use YawnGoat\Array_\Session\Operator as SessionOP;
use YawnGoat\Document\Path\Operator as PathOP;
use YawnGoat\Document\Path\Rooter;
use qiita\economist\document\document;
/*==============================
Desc:
	ログインページとして扱われるファイル。
	コード最下部において当クラスが直接実行される。
*/
class LoginForm{
	/*==============================
	Desc:このクラスのメイン処理部分
	*/
	public static function main(){
		//ドキュメントを読み込む
		$html = LoginForm::loadFixedDocment();
		echo($html);
	}
	/*==============================
	Desc:テンプレートを読み込み、書き換えた文書を返す
	*/
	private static function loadFixedDocment(){
		//セッションにメッセージがある場合取得する
		$msg = LoginForm::loadMsg();
		//ログインページのHTMLを取得
		$docment = LoginForm::loadDocment();
		//上記に対してDOM操作でメッセージを書き込む
		$result = LoginForm::writeMsg($msg,$docment);
		return $result;
	}
	/*==============================
	Desc:セッションからメッセージを読み込む
	*/
	private static function loadMsg(){
		$key = "msg";
		$result = SessionOP::read($key);
		return $result;
	}
	/*==============================
	Desc:テンプレート文書を読み込む
	*/
	private static function loadDocment(){
		$path = PathOP::fromRoot("/_resources/src/client/html/login.html");
		$content = file_get_contents($path);
		$result = new document($content);
		return $result;
	}
	/*==============================
	Desc:テンプレート文書に変更を加える
	*/
	private static function writeMsg($msg, $document){
		$isset = (null !== $msg);
		if($isset){
			$el = $document -> querySelector('.msg');
			$el -> textContent = $msg;
		}
		return $document;
	}
}
LoginForm::main();
?>