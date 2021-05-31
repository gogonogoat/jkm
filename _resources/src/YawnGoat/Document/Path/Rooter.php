<?php
namespace YawnGoat\Document\Path;
use YawnGoat\Document\Path\Operator as PathOP;
/*==============================
use YawnGoat\Document\Path\Rooter;
Desc:
ルートディレクトリを設定する
ディレクトリ構成に応じて
プログラマ側で下記のrelRootを調整する
*/
class Rooter{
	public static function root(){
		$option = PathOP::osIsWin() ?
			'../../../../../../':
			'/../../../../../';
		$relRoot = PathOP::normalize(__DIR__) . $option;
		$result = realpath($relRoot);
		return $result;
	}
}
?>