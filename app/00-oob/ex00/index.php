<?php

include "TemplateEngine.php";

$fileName = "template.html";
$templateName = "book_description.html";
$parameters = ["nom" => "Name", "auteur" => "Author", "description" => "Some description", "prix" => "price"];

$templateEngine = new TemplateEngine();
$templateEngine->createFile($fileName, $templateName, $parameters);

?>