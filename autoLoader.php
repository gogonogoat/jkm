<?php
ini_set('display_errors', 1);
//From: https://note.kiriukun.com/entry/20191103-php-autoloading-with-namespace
spl_autoload_register(function($class){
	/*==============================
	OS間のパス解決クラスを先行読込
	*/
	$thisPath = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."jkm/";
	$normalize = function($subject){
		$osIsWin = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
		$search  = $osIsWin ? '/':'\\';
		$replace = DIRECTORY_SEPARATOR;
		$result  = str_replace($search, $replace, $subject);
		return $result;
	};
	$reqPath = function()use($thisPath,$normalize){
		$relPath  = "_resources/src/YawnGoat/Document/Path/Operator.php";
		$result   = $normalize( $thisPath.$relPath );
		return $result;
	};
	require_once $reqPath();
	//==============================
	// 先頭のバックスラッシュ除去
	$class = ltrim($class, '\\');
	// バックスラッシュを区切り文字に置換して.php付与
	$classFile = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
	// src, lib以下を探す
	$dirNameList = [
		'_resources'.DIRECTORY_SEPARATOR.'src',
		'_resources'.DIRECTORY_SEPARATOR.'lib'
	];
	// クラスファイルがあれば読み込んで抜ける
	foreach ($dirNameList as $dirName) {
		//$classFilePath = __DIR__.DIRECTORY_SEPARATOR.$dirName.DIRECTORY_SEPARATOR.$classFile;
		$classFilePath = $thisPath.DIRECTORY_SEPARATOR.$dirName.DIRECTORY_SEPARATOR.$classFile;
		/*
		参照がうまく行かない時の検証用
		$line = '<br>********************<br>';
		echo($line);
		echo($classFilePath);
		echo("<br>");
		*/
		if (is_file($classFilePath) && is_readable($classFilePath)) {
			require_once $classFilePath;
			return true;
		}
	}
	return false;
});