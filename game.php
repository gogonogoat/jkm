<?php
include_once 'autoLoader.php';

use YawnGoat\Document\Path\Rooter;
use qiita\economist\document\document;
use YawnGoat\Document\Path\Operator as PathOP;
/*==============================
Desc:
	ゲームページとして扱われるファイル。
	コード最下部において当クラスが直接実行される。
*/
class JkmGame{
	/*==============================
	Desc:このクラスのメイン処理部分
	*/
	public static function main(){
		//キャッシュ無効
		header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");
		header("Cache-Control:pre-check=0,post-check=0",false);
		header("Pragma:no-cache");
		//ドキュメント読み込んで表示
		$html =JkmGame::loadDocment();
		echo($html);
	}
	/*==============================
	Desc:テンプレート文書を読み込む
	*/
	private static function loadDocment(){
		$path    = PathOP::fromRoot("/_resources/src/client/html/game.html");
		$content = file_get_contents($path);
		$result  = new document($content);
		return $result;
	}
}
JkmGame::main();
?>