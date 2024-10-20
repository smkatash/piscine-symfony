<?php

function array2hash_sorted($params)
{
    $hash = array();

    foreach($params as $pair) {
        if (!empty($pair[0]) && !empty($pair[1])) {
            $hash[$pair[0]] = $pair[1];
        }
    }
    krsort($hash);
    return $hash;
}

?>