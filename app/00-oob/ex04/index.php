<?php

include_once "Elem.php";
include_once "TemplateEngine.php";

$elem = new Elem('html');
$body = new Elem('body');
$body->pushElement(new Elem('p', 'Lorem ipsum', ['class' => 'text-muted']));
$elem->pushElement($body);
echo $elem->getHTML();

try {
    $elem = new Elem('undefined');  // Throws MyException
} catch (Exception $ex) {
    echo 'Caught exception: ', $ex->getMessage(), "\n";
	exit;
}

?>