<?php

include_once "Elem.php";
include_once "TemplateEngine.php";

$elem = new Elem('html');
$body = new Elem('body');
$br = new Elem('br');
$body->pushElement(new Elem('p', 'Lorem ipsum'));
$elem->pushElement($body);

$fileName = "file.html";
$tmp = new TemplateEngine($elem);
$tmp->createFile($fileName);
?>