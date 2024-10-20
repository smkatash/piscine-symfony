<?php

$input = fopen("ex01.txt", "r");
while(! feof($input)) {
    $line = fgets($input);
    $trline = rtrim($line);
    if (!empty($trline)) {
        $words = explode(",", $trline);
        foreach ($words as $word) {
            echo $word . "\n";
        }
    }
}

fclose($input);

?>