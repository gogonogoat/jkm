<?php
namespace YawnGoat\Array_\Operation;
use YawnGoat\Array_\Operation\Prototype2D;
/*==============================
use YawnGoat\Array_\Operation\Prototype;
Desc:
	配列に対する操作結果を得るクラス
Note:
	このクラスは静的クラスである。
Scrap:
*/
class Prototype{ protected function __construct(){}
/*==============================
Prototype::unique($arr)
Desc:
	配列に含まれるユニーク値を取得する
Param:
	Array 	arr		調べる対象の配列
Return
	Array 	result	全てがユニークな値の配列
Note:
	array_unique単体の場合キーが不揃いなので
	シリアライズしてから取得する
	
	名称変更
	旧：getSerialUnique
	新：unique
*/
public static function unique( Array $arr){
	//配列から重複する値を取り除く
	$keys	= array_unique($arr);
	//インデックスを連番化して整形
	$result	= array_values($keys);
	return $result;
}

/*==============================
Prototype::valueToKeys($keys)
Desc:
	配列中の文字列をキーとする、全値がNULLのオブジェクトを取得
Param:
	Array 	keys	キーにする文字列の配列
Return:
	Array 	result	全ての値がNULLである新規配列
Note:
	旧：getKeyToBlankObject
*/
public static function valueToKeys(Array $keys){
	$result = array_fill_keys($keys,NULL);
	return $result;
}

/*==============================
Prototype::assignKeys($target, $keys)
Desc:
	対象の配列に対して文字列配列中の値をキーとして追加する
Param:
	Array 	target	キー追加対象の配列
	Array 	keys	文字列の配列
Return:
	Array 	result	新規配列
Note:
	PHPにおいて A + B によるオブジェクト合成では
	重複するキーにおいてAの値が残る仕様
*/
public static function assignKeys(Array $target, Array $keys){
	//配列中の文字列をキーとする全値がNULLのオブジェクトを取得
	$append	= self::valueToKeys($keys);
	//全NULLオブジェクトを対象オブジェクト各値で上書き
	$result	= $target + $append;
	return $result;
}

/*==============================
Prototype::types($arr)
Desc:
	配列の[キー：データ]を[キー：データ型]に変換する
Param:
	Array 	arr		対象となる配列
Return:
	Array 	result	新規配列
Note:
	Fixしていたら1命令にできたが糖衣構文として残す
	名称変更
	旧：getTypeOfValues
	新：types
*/
public static function types($arr){
	//全てのキーの値をデータ型文字列に置き換える
	$result = array_map('gettype',$arr);
	return $result;
}

/*==============================
Prototype::isEvenObject($objA,$objB)
Desc:
	2つのオブジェクトの値の等しさを取得する
Param:
	objA	比較対象A
	objB	比較対象B
	return	Bool値
Note:
	https://www.php.net/manual/ja/language.operators.array.php
	＞	$a == $b	同等	$a および $b のキー/値のペアが等しい場合に TRUE。
	
	糖衣構文。
	
	★これは2つの値を取らずに二次元配列をパラメータとして
	二次元配列を操作するクラスに移動すべきだと思う
		いつやるか？
			名前空間のマッピング終わったら改修する
*/
public static function isEvenObject($objA,$objB){
	//キー：値が全て等しいか？
	$result = $objA == $objB;
	return $result;
}

/*==============================
Prototype::isEvenKeyType( $objA, $objB, $key )
Desc:
	2つのオブジェクトに対して指定したキーの値は同一であるかを取得
Param:
	objA	比較対象オブジェクト1
	objB	比較対象オブジェクト2
	key		オブジェクトのキー
	return	Bool値
Note:
	★これは2つの値を取らずに配列の配列をパラメータとして
	配列の配列を操作するクラスに移動すべきだと思う
		いつやるか？
			名前空間のマッピング終わったら改修する
*/
public static function isEvenKeyType( $objA, $objB, $key ){
	//指定したキーの値は両者ともに同一か？
	try{return $objA[$key] == $objB[$key];}
	//エラーの場合は偽を返す(例：指定されたキーは未定義だ)
	catch(Exception $e){return false;}
}

/*==============================
Prototype::types($arr)
Desc:
	配列の各キーのデータ型文字列の配列を取得する
Param:
	arr		対象配列
	return	値が全て文字列である配列
Note:
	#1 同じ動作のメソッドがあった。
	self::types($arr)
*/
public static function getKeysType($arr){
	throw new Exception("非推奨処理が検出されました [Note参照 #1]");
	//値をデータ型の文字列に変換を行うコールバック関数
	$valToType = function($v){return gettype($v);};
	//オブジェクトの全要素に上記を適用する
	$result = array_map( $valToType, $arr );
	return $result;
}

/*==============================
Desc:
	配列の値が全てサンプルと等しい値であるかを取得
Param:
	Array 	arr		値の配列
	any		sample	判定に用いるサンプル値
Return:
	Bool	result	arr内の値が全てsampleと等しいならば真
*/
public static function isAllIt($arr,$sample){
	$s = $sample;
	$f = function($c,$i)use($s){return $c and ($i==$s);};
	$result = array_reduce($arr,$f,true);
	return $result;
}
/*==============================
Prototype::isAllTrue($arr)
Desc:
	配列の値が全てTrueであるかを取得
Param:
	Array 	arr		ブール値の配列
Return:
	Bool	return	全てTrueならば真
*/
public static function isAllTrue($arr){return self::isAllIt($arr,true);}
/*==============================
Prototype::isAllTrue($arr)
Desc:
	配列の値が全てFalseであるかを取得
Param:
	Array 	arr		ブール値の配列
Return:
	Bool	return	全てFalseならば真
*/
public static function isAllFalse($arr){return self::isAllIt($arr,false);}
/*==============================
Prototype::isAllThat($arr,$that)
Desc:
	配列の値が全て関数を満たす値かを取得
Param:
	Array 	arr		値の配列、値の型は不問
	String	that	callableな文字列
Return:
	Bool	result	全て関数を満たす値ならば真
*/
public static function isAllThat($arr,$that){
	$f = function($c,$i)use($that){return $c and $that($i);};
	$result = array_reduce($arr,$f,true);
	return $result;
}
/*==============================
Prototype::isAllNull($arr)
Desc:
	配列の値が全てNULLであるかを取得
Param:
	Array 	arr		値の配列
Return:
	Bool	result	全てNULLならば真
*/
public static function isAllNull($arr){
	/*
	$f = function($c,$i){return $c and is_null($i);};
	$result = array_reduce($arr,$f,true);
	*/
	$that	= 'is_null';
	$result = self::isAllThat($arr,$that);
	return $result;
}
/*==============================
Prototype::isAllEmpty($arr)
Desc:
	配列の値が全てemptyであるかを取得
Param:
	Array 	arr		値の配列
Return:
	Bool	result	全てemptyならば真
*/
public static function isAllEmpty($arr){
	/*
	$f = function($c,$i){return $c and empty($i);};
	$result = array_reduce($arr,$f,true);
	*/
	$that	= 'empty';
	$result = self::isAllThat($arr,$that);
	return $result;
}
/*==============================
Prototype::isContainIt($arr,$sample)
Desc:
	配列の値の一部にサンプルに等しい値が含まれるかを取得
Param:
	Array 	arr		値の配列 値の型は不問
	any		sample	サンプルに用いる値
Return:
	Bool	result	サンプルに等しい値を含むならば真
*/
public static function isContainIt($arr,$sample){
	$s = $sample;
	$f = function($c,$i)use($s){return $c or ($i==$s);};
	$result = array_reduce($arr,$f,false);
	return $result;
}
/*==============================
Prototype::isContainThat($arr,$that)
Desc:
	配列の値の一部に関数を満たす値を含むかを取得
Param:
	Array 	arr		値の配列 値の型は不問
	String	that	callableな文字列
Return:
	Bool	result	「あれ」を含むならば真
*/
public static function isContainThat($arr,$that){
	$f = function($c,$i)use($that){return $c or $that($i);};
	$result = array_reduce($arr,$f,false);
	return $result;
}
/*==============================
Prototype::isContainNull($arr)
Desc:
	配列の値の一部にNULLを含むかを取得
Param:
	Array 	arr		値の配列
Return:
	Bool	result	NULLを含むならば真
*/
public static function isContainNull($arr){
	$that	= 'is_null';
	$result = self::isContainThat($arr,$that);
	return $result;
}
/*==============================
Prototype::isContainEmpty($arr)
Desc:
	配列の値の一部にemptyを含むかを取得
Param:
	arr		Array 	値の配列
	return	Bool	emptyを含むならば真
*/
public static function isContainEmpty($arr){
	/*
	$f = function($c,$i){return $c or empty($i);};
	$result = array_reduce($arr,$f,false);
	*/
	$that	= 'empty';
	$result = self::isContainThat($arr,$that);
	return $result;
}
/*==============================
Prototype::joinStringAnyGlue($arrStrA,$arrStrB){
Desc:
	文字列の入った配列A・Bにおいて
	A・B交互に挟みながら結合した文字列を得る
Param
	Array 	arrStrA	文字列を値とする配列
	Array 	arrStrB	文字列を値とする配列
Return:
	String	result	全てを結合した文字列
Note:
	出力イメージ：return A[0].B[0].A[1].B[1].A[2].B[2] ...
	
	キーが共通していなくとも抜けはなく全て結合される
	数値インデックス以外の結合順序の安定性は考慮していない
	
	★これは2つの値を取らずに配列の配列をパラメータとして
	配列の配列を操作するクラスに移動すべきだと思う
		いつやるか？
			名前空間のマッピング終わったら改修する
*/
public static function joinStringAnyGlue($arrStrA,$arrStrB){
	//A・Bのキーを相互補完する
	$mutual	= Prototype2D::getMutualizedKeys([$arrStrA,$arrStrB]);
	$a		= $mutual[0];
	$b		= $mutual[1];
	//前回の文字列にA・Bの順で文字列を結合するコールバック関数
	$func	= function($c,$i)use($a,$b){return $c.$a[$i].$b[$i];};
	//上記を全てのキーにおいて実行する
	$result = array_reduce(array_keys($a),$func,'');
	return $result;
}
//==============================
}
?>