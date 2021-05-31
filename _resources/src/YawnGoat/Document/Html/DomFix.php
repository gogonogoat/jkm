<?php
namespace YawnGoat\Document\Html;
use YawnGoat\Document\Encode\AntiBom;
use qiita\economist\document\document;
/*==============================
use YawnGoat\Document\Html\DomFix;
Desc:DOM操作を簡略化
*/
class DomFix{
//==============================

public static function insert(String $html, Array $dict){
	//アンチボム入れる(BOM有りHTML読むと不具合起こすことが判明した)
	$html = AntiBom::deleteBom($html);
	//指定された文書をDOM化
	$doc = new document($html);
	//配列のキーをセレクタとして文書内を参照した先に値を挿入
	$callback = function($item,$key)use($doc){
		//NULL入力の類を0幅スペースに変換
		$item	= empty($item) ? "&#8203;":$item;
		//セレクタからエレメントを取得
		$elm	= $doc->querySelector($key);
		//置換対象と取得されたエレメントをひとまとめにする
		$box	= [$key, $elm->innerHTML];
		//上記にEmpty要素があるならば不適格とする
		$disable = ArrayFormat::isContainEmpty($box);
		if($disable)return;
		//内部HTMLを変更
		$elm->innerHTML = $item;
	};
	array_walk($dict,$callback);
	return $doc;
}

//==============================
?>