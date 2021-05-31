<?php
namespace qiita\economist\document;
use qiita\economist\document\HTMLElement;
use qiita\economist\document\HTMLFragment;

/*
use qiita\economist\document\document;
Desc:

Note:
	Split From document.php 2020/11/06 https://qiita.com/economist/items/aefccb2f073ed9429607
	
	*Fix[1]
		クラス名は名前空間の中のフルパスを入力する必要があるので
		名前空間上に配置＆オートローダーとの併用の関係上から
		クラス名→パス付きクラス名に変更した
			2021年3月24日　author大和 玄騎
			
	*Fix[2]
		読み込んだHTMLエレメント内に"@"を持つ属性が存在した場合に
		@移行の文字が全て無視される問題があるので
		文字列の読み込みに@をエスケープして＆ToString時に復元する事にした
		
*/
class document extends \DOMDocument{ // https://www.php.net/manual/ja/class.domdocument.php

	// *Fix[1]
    const HTMLELEMENT = "qiita\\economist\\document\\HTMLElement";
    const HTMLFRAGMENT = "qiita\\economist\\document\\HTMLFragment";
    
    function __construct($str = '<!DOCTYPE html><html lang="ja"><head><meta charset="utf-8"><title></title></head><body></body></html>'){
    	$str = document::escapeAt($str);
        parent::__construct();
        
		// *Fix[1]
        $this->registerNodeClass('\DOMElement',document::HTMLELEMENT);
        $this->registerNodeClass('\DOMDocumentFragment',document::HTMLFRAGMENT);
        libxml_use_internal_errors(true);

        $pos = strpos($str, '<');

        if($pos >= 0 and $str[$pos+1] === '!'){
            $this->contentsType = 'html';
            $this->loadHTML(substr($str, $pos), LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED | LIBXML_NONET | LIBXML_COMPACT);
        }
        else if($pos >= 0 and $str[$pos+1] === '?'){
            $this->contentsType = 'xml';
            $this->loadXML(substr($str, $pos), LIBXML_NONET | LIBXML_COMPACT); // https://www.php.net/manual/ja/libxml.constants.php
        }
        else{
            $this->contentsType = 'fragment';
            $this->loadHTML('<?xml encoding="utf-8">'.$str, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED | LIBXML_NONET | LIBXML_COMPACT);
        }
    }

	/* ==============================
	Desc:HTMLとして読み込む前に@をエスケープする
	Note:
		Fix[2]参照
	*/
	private static function escapeAt($subject){
		$search = '@';
		$replace = '_AT_ESCAPE_';
		$result = str_replace($search,$replace,$subject);
		return $result;
	}
	/* ==============================
	Desc:toStringする前に@を復元する
	Note:
		Fix[2]参照
	*/
	private static function unescapeAt($subject){
		$search = '_AT_ESCAPE_';
		$replace = '@';
		$subject2 = str_replace($search,$replace,$subject);
		$search = '_at_escape_';
		$replace = '@';
		$result = str_replace($search,$replace,$subject2);
		return $result;
	}
	
    function __get($name){
        if(in_array($name, ['html','head','body','title'], true)){
            return $this->getElementsByTagName($name)[0];
        }
        else{
            return $this->getElementById($name);
        }
    }


    function __invoke($selector, $text = null, $attr = []){
        if($selector instanceof self){
            return $this->importNode($selector->documentElement, true);
        }
        else if($selector instanceof \DOMNode){
            return $this->importNode($selector, true);
        }
        else if(preg_match('/</', $selector)){
            if(preg_match('/^<([\w\-]+)>$/', $selector, $m)){
                return $this->createHTMLElement($m[1], $text, $attr);
            }
            else{
                return self::createHTMLFragment($this, $selector);
            }
        }
        else if($selector[0] === '*'){
            if(strlen($selector) > 1){
                $selector = substr($selector, 1);
            }
            return self::searchElement($selector, $text, $this, true);
        }
        else{
            return self::searchElement($selector, $text, $this);
        }
    }

	/*
    function __toString(){
        $this->formatOutput = true;

        if($this->contentsType === 'html'){
            return $this->saveXML($this->doctype) . "\n" . $this->saveHTML($this->documentElement);
        }
        else if($this->contentsType === 'xml'){
            return $this->saveXML($this->doctype) . "\n" . $this->saveXML($this->documentElement);
        }
        else{
            return $this->saveHTML($this->documentElement);
        }
    }
    */

	/* ==============================
	Desc:toString処理の置き換え
	Note:
		Fix[2]参照
	*/
    function __toString(){
        $this->formatOutput = true;
    	$contentsType = $this->contentsType;
    	switch(true){
    		case ($contentsType === 'html'):
    			$subject = $this->saveXML($this->doctype) . "\n" . $this->saveHTML($this->documentElement);
    			break;
    		case ($contentsType === 'xml'):
    			$subject = $this->saveXML($this->doctype) . "\n" . $this->saveXML($this->documentElement);
    			break;
    		default:
    			$subject = $this->saveHTML($this->documentElement);
    	}
    	$result = document::unescapeAt($subject);
    	return $result;
	}


    function querySelector($selector, $context = null){
        return self::searchElement($selector, $context, $this);
    }


    function querySelectorAll($selector, $context = null){
        return self::searchElement($selector, $context, $this, true);
    }


    private function createHTMLElement($tagName, $text = '', $attr = []){
        $el = $this->createElement($tagName);
        foreach($attr as $k => $v){
            $el->setAttribute($k, $v);
        }

        if(is_array($text)){
            if($tagName === 'table'){
                $el = $this->createTableElement($el, $text);
            }
            else if($tagName === 'select'){
                $el = $this->createSelectElement($el, $text);
            }
            else if($tagName === 'ol' or $tagName === 'ul'){
                $el = $this->createListElement($el, $text);
            }
        }
        else{
            $el->textContent = $text;
        }

        return $el;
    }


    private function createListElement($el, array $contents){
        foreach($contents as $v){
            $child = $this->createElement('li', $v);
            $el->appendChild($child);
        }
        return $el;
    }


    private function createSelectElement($el, array $contents){
        foreach($contents as $v){
            $child = $this->createElement('option', $v);
            $child->setAttribute('value', $v);
            $el->appendChild($child);
        }
        return $el;
    }


    private function createTableElement($el, array $contents){
        foreach($contents as $row){
            $tr = $this->createElement('tr');
            $el->appendChild($tr);
            foreach((array)$row as $cell){
                $td = $this->createElement('td', $cell);
                $tr->appendChild($td);
            }
        }
        return $el;
    }


    static function createHTMLFragment($document, $str){
        $fragment = $document->createDocumentFragment();
        //$fragment->appendXML($str);
        $dummy    = new self("<dummy>$str</dummy>");
        foreach($dummy->documentElement->childNodes as $child){
            $fragment->appendChild($document->importNode($child, true));
        }
        return $fragment;
    }


    static function searchElement($selector, $context, $document, $all = false){
        $selector = self::selector2xpath($selector, $context);
        $result   = (new \DOMXPath($document))->query($selector, $context);
        return $all ? iterator_to_array($result) : $result[0];
    }


    static function selector2xpath($input_selector, $context = null){
        $selector = trim($input_selector);
        $last     = '';
        $element  = true;
        $parts[]  = $context ? './/' : '//';
        $regex    = [
            'element'    => '/^(\*|[a-z_][a-z0-9_-]*|(?=[#.\[]))/i',
            'id_class'   => '/^([#.])([a-z0-9*_-]*)/i',
            'attribute'  => '/^\[\s*([^~|=\s]+)\s*([~|]?=)\s*"([^"]+)"\s*\]/',
            'attr_box'   => '/^\[([^\]]*)\]/',
            'combinator' => '/^(\s*[>+~\s,])/i',
        ];

        $pregMatchDelete = function ($pattern, &$subject, &$matches){ // 正規表現でマッチをしつつ、マッチ部分を削除
            if (preg_match($pattern, $subject, $matches)) {
                $subject = substr($subject, strlen($matches[0]));
                return true;
            }
        };

        while (strlen(trim($selector)) && ($last !== $selector)){
            $selector = $last = trim($selector);

            // Elementを取得
            if($element){
                if ($pregMatchDelete($regex['element'], $selector, $e)){
                    $parts[] = ($e[1] === '') ? '*' : $e[1];
                }
                $element = false;
            }

            // IDとClassの指定を取得
            if($pregMatchDelete($regex['id_class'], $selector, $e)) {
                switch ($e[1]){
                    case '.':
                        $parts[] = '[contains(concat( " ", @class, " "), " ' . $e[2] . ' ")]';
                        break;
                    case '#':
                        $parts[] = '[@id="' . $e[2] . '"]';
                        break;
                }
            }

            // atribauteを取得
            if($pregMatchDelete($regex['attribute'], $selector, $e)) {
                switch ($e[2]){ // 二項(比較)
                    case '!=':
                        $parts[] = '[@' . $e[1] . '!=' . $e[3] . ']';
                        break;
                    case '~=':
                        $parts[] = '[contains(concat( " ", @' . $e[1] . ', " "), " ' . $e[3] . ' ")]';
                        break;
                    case '|=':
                        $parts[] = '[@' . $e[1] . '="' . $e[3] . '" or starts-with(@' . $e[1] . ', concat( "' . $e[3] . '", "-"))]';
                        break;
                    default:
                        $parts[] = '[@' . $e[1] . '="' . $e[3] . '"]';
                        break;
                }
            }
            else if ($pregMatchDelete($regex['attr_box'], $selector, $e)) {
                $parts[] = '[@' . $e[1] . ']';  // 単項(存在性)
            }

             // combinatorとカンマがあったら、区切りを追加。また、次は型選択子又は汎用選択子でなければならない
            if ($pregMatchDelete($regex['combinator'], $selector, $e)) {
                switch (trim($e[1])) {
                    case ',':
                        $parts[] = ' | //*';
                        break;
                    case '>':
                        $parts[] = '/';
                        break;
                    case '+':
                        $parts[] = '/following-sibling::*[1]/self::';
                        break;
                    case '~': // CSS3
                        $parts[] = '/following-sibling::';
                        break;
                    default:
                        $parts[] = '//';
                        break;
                }
                $element = true;
            }
        }
        return implode('', $parts);
    }
}

?>