<?php
namespace YawnGoat\Document\Csv;
use YawnGoat\Array_\DestructiveOP\ByRefCruds;
/*==============================
Desc: 
	[キー：値...]の形式で保存したCSVからデータを読み込むクラス
	静的メソッドのみを持つ
Note:
	・行を取得した場合[0]である1列目の値はキー文字列である

	・値は全て文字列で取得される
		Bool値を表現する文字列がBoolで取り込まれるのか
		Stringで取り込まれるのか考える必要はない。
		全て文字列なので値として用いる時に適宜キャストしよう。

		・PHPにおいて"False"はTrueである問題。
			Bool値の取得に関して専用のメソッド作るか…？
				やめた
				文字列なのはわかっているのだから
				余計なデカブツに責任負わせずにその場でキャストしよう
				これはYAGNIってやつなんだぞ
*/
class Finder{
const CSV_KEY = 0;
/*==============================
Desc: 指定した行に指定したキーが存在するかを取得
Parm:
	row		Array	csvから取得した行である一次元配列
	key		String	検索するキー文字列
	return	Bool	キーが一致するならば真
Note:
	エラー想定
		rowパラメータの値がArrayではなくNULLである
*/
protected static function isExistKey($row,$key){
	//配列のインデックス[0]とキー文字列の比較結果を返す
	try{
		$result = $row[self::CSV_KEY] == $key;
	//エラー発生した場合はNULLを返す[Note参照]
	}catch(Exception $e){
		$result = NULL;
	}
	return $result;
}

/*==============================

Desc: CSV内の指定したキーに一致する行を文字列の配列で取得する
Param:
	csvPath	String	CSVへのファイルパス
	key		String	キー文字列(CSVの1列目に列挙記述)
	return	Array	行を示す配列を得る
			NULL	キーに対応する配列が存在しない場合
Note:
	配列の中の値は全て文字列である

	PHPのfopenを静的メソッド内で用いた場合
	そのリソースはメソッドの終了とともに解放される。
	故にfcloseを省略した。
*/
public static function readRow($csvPath,$key){
	//NULLキーは無効
	if(empty($key))throw new InvalidArgumentException("空文字列によるキー指定は無効です");
	//ファイルを開く
	$file = fopen($csvPath,"r");
	//一致するまでファイル内を検索
	Do{
		//行を新しく取得
		$line = fgetcsv($file);
		//上記の行はキーが一致するかを取得
		$found = self::isExistKey($line,$key);
		//キーが一致しているならばその行を返しメソッドを終了
		if($found) return $line;
	//行が取得できている限りループを継続
	}While($line);
	//一致するキーが存在しないのでNULLを返す
	return NULL;
}

/*==============================
Desc: CSV内・指定キー行の指定した列の文字列値を得る
Param:
	csvPath	String	CSVへのファイルパス
	key		String	キー文字列(CSVの1列目に列挙記述)
	column	Integer	取得するインデックス値
	return	String	参照した結果の文字列
			NULL	参照先が見つからない等のエラーの場合
Note:
	取得される値は文字列に統一されている。
		
	#1 取得文字列はUTF8に限定する。
	読み込んだCSVがUTF8ではない事へ対して
	フォローは行わずに即座に指摘して根本的な修正を促す。
	ここで譲歩した場合また別の場合でも同様な譲歩が必要となるためだ。
*/
public static function readString($csvPath,$key,$column=1){
	//キーに該当する行を取得
	$row	= self::readRow($csvPath,$key);
	//行から値を取得
	$str	= ByRefCruds::tryRead($row,$column);
	//値を検証して返す
	$result	= self::validateString($str);
	return $result;
}
/*==============================
Desc:
	getStringで取得する文字列の検証用関数
Param:
	String str		検証文字列
Return:
	String	result	検証済み文字列
Note:
	文字列がNULLであればそのままNULLを得る
	文字列がUTF8でない場合は例外を出力する
*/
protected static function validateString($str){
	$isNull	 = is_null($str);
	$notUtf8 = mb_detect_encoding($str, "UTF-8")==false;
	switch(true){
		case $isNull:
			$result = NULL;
		break;
		case $notUtf8:
			throw new DomainException("文字コードが非UTF8です");
		break;
		default:
			$result = $str;
		break;
	}
	return $result;
}
//==============================
}

?>