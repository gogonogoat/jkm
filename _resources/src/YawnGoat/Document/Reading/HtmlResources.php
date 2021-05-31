<?php
namespace YawnGoat\Document\Reading;
/*==============================
use YawnGoat\Document\Reading\HtmlResources;
Desc:HTMLリソースを取得するクラス
基本的にfile_get_contentsを使うべきなので非推奨
*/
class HtmlResources{
	public static function content($dir,$fileName){
		$path	= $dir.$fileName;
		$result	= file_get_contents($path);
		return $result;
	}
}
?>
