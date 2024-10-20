<?php

// • a method readData to return all strings in HTML, each embedded in a <p> tag.

class Text {
    private $parameters = [];

    public function __construct($str) {
        echo "construct is called.\n";
        $this->parameters = $str;
    }
    public function __destruct() {
        echo "destruct is called.\n"; 
    }

    public function append(...$str){
        array_push($this->parameters, ...$str);
    }

    public function readData() {
        $htmlObj = null;
        foreach($this->parameters as $str) {
            $htmlObj .= "<p>" . $str . "</p>";
        }
        return $htmlObj;
    }
}

?>