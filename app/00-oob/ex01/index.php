<?php

include "TemplateEngine.php";
include "Text.php";

$fileName = "template.html";
$params = array('apple', 'avocado');
$text = new Text($params);
$templateEngine = new TemplateEngine();
$text->append('banana', 'cherry');
$templateEngine->createFile($fileName, $text);

?>