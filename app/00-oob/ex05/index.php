<?php

include_once "Elem.php";
include_once "TemplateEngine.php";

$elem = new Elem('html');
echo $elem->validPage("file.html") ? 'true' : 'false';
echo $elem->validPage("invalid.html") ? 'true' : 'false';


?>