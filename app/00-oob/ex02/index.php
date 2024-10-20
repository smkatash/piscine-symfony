<?php

include_once "TemplateEngine.php";
include_once "Coffee.php";
include_once "Tea.php";

$template = new TemplateEngine();
$coffee = new Coffee();
$tea = new Tea();

$template->createFile("coffee.html", $coffee);
$template->createFile("tea.html", $tea);

?>