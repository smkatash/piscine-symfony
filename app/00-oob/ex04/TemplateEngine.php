<?php

require_once "Elem.php";

class TemplateEngine {
  private Elem $elem;

  public function __construct(Elem $elem){
    $this->elem = $elem;
  }
  public function __destruct(){}

  public function createFile($fileName){
    file_put_contents($fileName, $this->elem->getHTML(), FILE_APPEND);
  }
}

?>