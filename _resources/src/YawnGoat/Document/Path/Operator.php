<?php
namespace YawnGoat\Document\Path;
/*==============================
use YawnGoat\Document\Path\Operator as PathOP;
Desc:
パスの変換・結合・検出について諸々行う。
オートローダーに使わせたりする都合上役割多め
*/
class Operator{
	/*==============================
	Desc:鯖側OS検出
	*/
	public static function osIsWin(){ return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');}
	public static function osIsNix(){ return (Converter::osIsWin() == false);}
	/*==============================
	Desc:パスの結合を行う
	Note:
		ディレクトリパスは
		nix環境の場合において末尾にセパレータがある
		Win環境の場合において末尾にセパレータがない
		その違いを吸収した処理が必要である
	*/
	public static function combine($dir,$file){
		//パスの末尾のセパレータを除外
		$dir_ = rtrim($dir,'\\/');
		//鯖側のセパレータを間に挟み結合する
		$result = $dir_.DIRECTORY_SEPARATOR.$file;
		return $result;
	}
	/*==============================
	Desc:パス文字列を鯖の動作環境の形式に揃える
	Note:
	*/
	public static function normalize($subject){
		$osIsWin = Operator::osIsWin();
		$search  = $osIsWin ? '/':'\\';
		$replace = DIRECTORY_SEPARATOR;
		$result  = str_replace($search, $replace, $subject);
		return $result;
	}
	
	/*==============================
	Desc:ルートに対する結合を得る
	Note:
		このような構文へのショートハンド
		PathOP::normalize(Rooter::root().$rel)
	*/
	public static function fromRoot($rel){
		//OS間のギャップを埋める
		$result = Operator::combine(Rooter::root(),$rel);
		return $result;
	}
}
?>