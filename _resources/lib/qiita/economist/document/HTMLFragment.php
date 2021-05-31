<?php
namespace qiita\economist\document;
use qiita\economist\document\document;

//Split From document.php 2020/11/06 https://qiita.com/economist/items/aefccb2f073ed9429607
class HTMLFragment extends \DOMDocumentFragment{ // https://www.php.net/manual/ja/class.domdocumentfragment.php

    function __construct() {
        parent::__construct();
    }


    function __get($name){
        if($name === 'innerHTML'){
            $result = '';
            foreach($this->childNodes as $child){
                $result .= $this->ownerDocument->saveHTML($child);
            }
            return $result;
        }
        else if($name === 'outerHTML'){
            return $this->ownerDocument->saveHTML($this);
        }
        else if($name === 'children'){
            $children = [];
            foreach($this->childNodes as $v){
                if($v->nodeType === XML_ELEMENT_NODE){
                    $children[] = $v;
                }
            }
            return $children;
        }
        else{
            return document::searchElement("#$name", $this, $this->ownerDocument);
        }
    }


    function querySelector($selector){
        return document::searchElement($selector, $this, $this->ownerDocument);
    }


    function querySelectorAll($selector){
        return document::searchElement($selector, $this, $this->ownerDocument, true);
    }


    function __toString(){
        return $this->ownerDocument->saveHTML($this);
    }
}
?>