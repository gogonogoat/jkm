<?php
namespace YawnGoat\DataBase\MySql;
use YawnGoat\Array_\Operation\Prototype;
use YawnGoat\DataBase\MySql\ValidateParams;
use PDO;
/*==============================
use YawnGoat\DataBase\MySql\SendRequest;
Desc:
	DB(MySQL)への命令を行うクラス
Note:
	

*/
class SendRequest{
/*==============================
Desc:
	値をバインドしたステートメントの実行結果を取得して返す
Param:
	dbh		PDO		データベースハンドル
	prpr	String	プリペアドステートメントにするSQL文
	params	Array	上記にバインドするデータ郡
Return:
	Statement		データバインドされたステートメント
Exception:

Exsample:
	$prpr = "SELECT * FROM testtable where name=:name;";
	$params = [NULL,":name"=>"しろやぎさん"];
	$sample = [NULL,":name"=>""];
	$stmt = SendRequest::request($dbh, $prpr, $params, $sample);
	$result = $stmt->fetch();
	print_r($result);
Note:
*/
public static function request($dbh, $prpr, $params, $sample){
	//入力値を検証
	$noValid = ValidateParams::isValid($params,$sample)==false;
	//検証不適格にはNULLを返す
	if($noValid)return NULL;
	//プリペアドステートメントへ値をバインドする
	$order		= self::bindValue($dbh,$prpr,$params);
	//値をバインドしたステートメントを実行
	$executed	= $order->execute();
	//実行された状態のステートメントを返す
	$result		= $order;
	return $result;
}
/*==============================
Desc:
	パラメータ連想配列からSQL文へ値をバインドする
Param:
	dbh			PDO			データベースハンドル
	prepare		String		用意されたSQL文
	paramDict	Dictionary	バインドするキー・値
Return
	Statement	バインド実行結果
Note:
	キーが数値である場合は疑問符パラメータとみなす
*/
protected static function bindValue($dbh, $prepare, $param){
	//SQL文をセット
	$stmt	 = $dbh->prepare($prepare);

	/*★　直下のコードにバグが発生したので暫定でここに書いておく
	動作上問題なければ暫定解除でOK
	*/
	$param_ = $param;

	//上記のキーのみを取得
	$keys	 = array_keys($param_);
	//キーと値をバインドするコールバック関数
	$bind	 = function($carry,$key)use($param_,$stmt){
		return self::bindValueCB($carry,$key,$param_,$stmt);
	};
	//キー毎にバインドを行い、バインド成否を得る
	$isBind	 = array_reduce($keys,$bind,[]);
	//バインドされなかった値が存在するか
	$noValid = Prototype::isAllTrue($isBind)==false;
	//上記であるならば例外を投げる
	if($noValid) throw new Exception("ステートメントへのデータバインドに失敗しました");
	//値がバインドされたステートメントを返す
	return $stmt;
}
/*==============================
Desc:
	指定したステートメントに対して
	キーと値をバインドする
	但し、キーが0である場合は無視される
Param:
	carry		Array		array_reduceにおける前回の結果
	key			Any			配列のキー
	arr			Array		参照する配列
	statement	Statement	操作するステートメント
Return:
	Array		最終的にarrに含まれるキーに対して
				バインドの成否が代入された配列が得られる
Note:
	コールバック関数が長くなったので
	分離することにした
*/
protected static function bindValueCB($carry,$key,$arr,$statement){
	//キーが0の場合は何もせずに次の処理へ
	if($key===0)return $carry;
	//配列とキーから値を取得
	$val = $arr[$key];
	//ステートメントに書き込むデータ型を得る
	$type = self::gettype($val);
	//バインドを行い、その成否を取得
	$eval = $statement->bindValue($key,$val,$type);
	//バインド結果を更新
	$carry[$key] = $eval;
	return $carry;
}
/*==============================
Desc:
	PHPの値が持つ基本データ型に対応する
	MySQLデータ型定数を得る
Param:
	val		Any		対応させるデータ
Return:
	Int		PDO::PARAM_XXX 形式で記述されるMySQLデータ型定数
Exception:
	IsNotBasicValueException
		引数が基本型ではない
Note:
	#1 double型に対応するのは文字列型である件について
	結論： 数値を文字列にキャストして格納する
	理由： MySQL側のデータ型には浮動小数点型は存在しない
	
	追記： 小数値の格納は少なからず強引な手法が必要となる
		故に初めから小数値をDBに格納しない設計を行うことが望ましい
	
	#2 基本データ型以外への対応について
	結論： 当クラスが受け取る値は基本的なデータ型だけで良い
	理由： 細かいデータ型に関する事情に配慮するのは
		当クラスの仕事ではない
	追記： 当クラスに値を受け渡す前に
		各値はオブジェクトとしての性質が
		事前に破棄されるべきである
*/
protected static function gettype($val){
	$type = gettype($val);
	switch($type){
		case 'boolean'	:return PDO::PARAM_BOOL;
		case 'integer'	:return PDO::PARAM_INT;
		case 'string'	:return PDO::PARAM_STR;
		case 'NULL'	 	:return PDO::PARAM_NULL;

		//実数は文字列に変換 [Note参照 #1]
		case 'double'	:return PDO::PARAM_STR;
		
		//予期しないデータ型の場合は例外出力
		default:throw new Exception("入力されたデータ型が変です");
	}
}
//==============================
}

?>