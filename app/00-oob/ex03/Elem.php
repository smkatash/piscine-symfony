<?php

class Elem {
    private string $elem;
    private string $content;

    public function __construct($elem, $content="") {
        $this->elem = $elem;
        $this->content = $content;
    }

    public function __destruct() {}

    public function pushElement(Elem $elm){
        $this->content .= $elm->getHTML();
    }

    public function getHTML(){
        $selfClosingTags = ['meta', 'img', 'hr', 'br'];

        if (in_array($this->elem, $selfClosingTags)) {
            return "<" . $this->elem . "/>\n" . $this->content . "\n";
        }
        return "<" . $this->elem . ">\n" . $this->content . "\n</" . $this->elem . ">\n";
    }
    

}


?>