<?php

include_once "MyException.php";

class Elem {
    private $allowedTags = ['meta', 'img', 'hr', 'br', 
    'html', 'head', 'body', 'title', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div',
    'table', 'tr', 'th', 'td', 'ul', 'ol', 'li'];
    private string $elem;
    private string $content;
    private $attributes;

    public function __construct($elem, $content="", $attributes="") {
        if (!in_array($elem,$this->allowedTags)) {
            throw new MyException("MyException: An unauthorized tag is used.");
        }
        $this->elem = $elem;
        $this->content = $content;
        $this->attributes = $attributes;
    }

    public function __destruct() {}

    public function pushElement(Elem $elm){
        $this->content .= $elm->getHTML();
    }

    public function getHTML(){
        $attr_key = "";
        $attr_val = "";
        $attributeTag = "";

        if($this->attributes){
            foreach($this->attributes as $key => $value){
                $attr_key = $key;
                $attr_val = $value;
                $attributeTag  .= $attr_key . "=\"$attr_val\"";
            }
        }
        $selfClosingTags = ['meta', 'img', 'hr', 'br'];

        if (in_array($this->elem, $selfClosingTags)) {
            return "<$this->elem $attributeTag/>\n$this->content\n";
        }
        return "<$this->elem $attributeTag>\n$this->content\n</$this->elem>\n";
    }
    

}


?>