<?php
namespace YawnGoat\Array_\Operation;
use YawnGoat\Array_\Operation\Prototype;
/*==============================
use YawnGoat\Array_\Operation\Prototype2D;
Desc:
	二次元配列に対する操作結果を得るクラス
Note:
	このクラスは静的クラスである。
*/
class Prototype2D{ protected function __construct(){}
/*==============================
Prototype2D::keys($arr2D)
Desc:
	配列中配列の全キーを文字列の配列として取得する
Param:
	Array	arr2D	二次元配列
Return:
	Array	result	文字列データの配列
Example:
	[ ["aaa"=>0, "bbb"=>"@", "ccc"=>true ],
	  ["aaa"=>0, "bbb"=>"@", "ddd"=>false] ]
	return		["aaa", "bbb", "ccc","ddd"]
Note:
	キーは数値・文字列を問わず重複を容認して収集される
	重複のない値を求める場合はgetAllUniqueKeysを用いる
	
	名称変更
	旧：getAllKeys
	新：keys
*/
public static function keys( Array $arr2D ){
	//配列のキーを文字列の配列にして前回の配列と結合
	$collect = function($carry,$item){
		$keys	= array_keys($item);
		$fruit	= array_merge($carry, $keys);
		return $fruit;
	};
	//重複込みで全てのキーを列挙
	$result = array_reduce($arr2D,$collect,[]);
	return $result;
}
/*==============================
Prototype2D::uniqueKeys($arr2D)
Desc:
	配列に含まれる配列の全キーを
	文字列のユニーク値配列として取得する
Param:
	Array	arr2D	操作する二次元配列
Return:
	Array	result	文字列を値とする一次元配列
Note:
	名称変更
	旧：getAllUniqueKeys
	新：uniqueKeys
*/
public static function uniqueKeys( Array $arr2D ){
	//全ての配列のキーを取得
	$keys	= self::keys($arr2D);
	//上記中のユニークな値を連番で取得
	$result	= Prototype::unique($keys);
	return $result;
}
/*==============================
Prototype2D::assignKeys($arr2D,$keys)
Desc:
	配列中の各文字列を配列中にある配列へキーとして追加する
Param:
	Array	arr2D	操作する二次元配列
	Array	keys	キーとして追加する文字列の配列
Return:
	Array	result	全ての子配列に対してキーが追加された新規二次元配列
Note:
	名称変更
	旧：assignKeysAll
	新：assignKeys
*/
public static function assignKeys( Array $arr2D, Array $keys ){
	//配列へキーを追加する
	$assign = function($arr)use($keys){
		$fruit = Prototype::assignKeys($arr, $keys);
		return $fruit;
	};
	//上記を全ての子配列に適用
	$result = array_map($assign,$arr2D);
	return $result;
}
/*==============================
Prototype2D::mutualizeKey($arr2D)
Desc:
	親配列中にある子配列のキーが
	相互に補完された状態の新規二次元配列を得る
Param:
	Array	arr2D	操作する二次元配列
Return:
	Array	result	子配列のキーが相互補完された新規二次元配列
Note:
	名称変更
	旧：getMutualizedKeys
	新：mutualizeKey
*/
public static function mutualizeKey(Array $arr2D){
	//子配列における全てのキーを取得
	$keys	= self::uniqueKeys($arr2D);
	//全ての子配列に上記のキーを追加
	$result	= self::assignKeys($arr2D,$keys);
	return $result;
}
/*==============================
Prototype2D::types($arr2D)
Desc:
	親配列に対する子配列において
	[キー：値]を[キー：値のデータ型文字列]に置き換えた
	新規二次元配列を取得する
Param:
	Array	arr2D	操作する二次元配列
Return:
	Array	result	子配列の各値がデータ型文字列である新規二次元配列
Note:
	名称変更
	旧：getTypeOfValuesAll
	新：types
*/
public static function types($arr2D){
	
	/*
	//指定された配列のキーをそのデータ型文字列で上書き
	$callback = function($item){
		$typeOfValue = Prototype::types($item);
		return $typeOfValue;
	};
	*/
	//指定された配列の各値を対応するデータ型文字列に置換
	//コールバック時はフルネーム指定が必要
	$callback = 'YawnGoat\Array_\Operation\Prototype::types';
	//上記を全配列に適用
	$types = array_map($callback,$arr2D);
	return $types;
}
/*==============================
Prototype2D::isEvenValues($arr2D)
Desc:
	配列内の配列の各値が全て等しいかを取得する
Param:
	Array	arr2D	操作する二次元配列
Note:
	改良したのでそちらを用いてください
		Prototype2D::isEvenValues($arr2D)
*/
public static function isEvenAllObject($arr2D){
	throw new Exception("非推奨処理です [Note参照]");
	//配列の最初の配列を比較サンプルとする
	$sumple = array_shift($arr2D);
	//一連の比較が全て一致している場合に真を得る
	$equality = function($carry,$item)use($sumple){
		$fruit = ($carry and ArrayFormat::isEvenObject($sumple,$item));
		return $fruit;
	};
	//上記を全てに適用する
	$isEven = array_reduce($arr2D,$equality,true);	
	return $isEven;
}
/*==============================
Prototype2D::isEvenKeysType($arr2D)
Desc:
	親配列に対する子配列において
	キーに対応するデータ型は全て等しいかを取得
Param:
	Array	arr2D	操作する二次元配列
Note:
	名称変更
	旧：isEvenKeysTypeAll
	新：isEvenKeysType
*/
public static function isEvenKeysType($arr2D){
	//子配列のキーが相互補完された状態の親配列を取得
	$mutualize	= self::mutualizeKey($arr2D);
	//子配列の値を値のデータ型に置換した親配列を取得
	$types		= self::types($mutualize);
	//子配列のキーに対応する値同士が全て等しいならば真
	$result		= self::isEvenValues($types);

	return $result;
}
/*==============================
Prototype2D::isEvenValues($arr2D)
Desc:
	親配列中にある子配列について
	キーが同一であれば同じ値であるかを取得
Param:
	Array	arr2D	二次元配列
Return:
	Bool	result	関係性を満たしているならば真
Note:
	https://www.php.net/manual/ja/language.operators.array.php
	＞	$a == $b	同等	$a および $b のキー/値のペアが等しい場合に TRUE。
Example:
	[ [0,1], [0,1] ] => true
	[ [0,1], [2,3] ] => false
*/
public static function isEvenValues(Array $arr2D){
	/*
	//配列からサンプルを1個取り出す
	$sample = array_shift($arr2D);
	//配列内の各値がサンプルに等しければ真
	$result = Prototype::isAllIt($i,$sample);
	*/
	$result = ( $arr2D[0] == $arr2D[1] );
	return $result;
}
//==============================
}
?>