<?php
namespace qiita\economist\document;
use qiita\economist\document\document;
//Split From document.php 2020/11/06 https://qiita.com/economist/items/aefccb2f073ed9429607
class HTMLElement extends \DOMElement{ // https://www.php.net/manual/ja/class.domelement.php

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
            return $this->getAttribute($name);
        }
    }


    function __set($name, $value){
        if($name === 'innerHTML'){
            $fragment = document::createHTMLFragment($this->ownerDocument, $value);
            $this->textContent = '';
            $this->appendChild($fragment);
        }
        else if($name === 'outerHTML'){
            $fragment = document::createHTMLFragment($this->ownerDocument, $value);
            $this->parentNode->replaceChild($fragment, $this);
        }
        else{
            $this->setAttribute($name, $value);
        }
    }


    function __unset($name){
        $this->removeAttribute($name);
    }


    function __isset($name){
        return $this->hasAttribute($name);
    }


    function __toString(){
        return $this->ownerDocument->saveHTML($this);
    }


    function querySelector($selector){
        return document::searchElement($selector, $this, $this->ownerDocument);
    }


    function querySelectorAll($selector){
        return document::searchElement($selector, $this, $this->ownerDocument, true);
    }
}

?>