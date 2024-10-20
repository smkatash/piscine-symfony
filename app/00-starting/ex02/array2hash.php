<?php

function array2hash($params)
{
	$hash = array();

    foreach($params as $pair) {
        if (!empty($pair[0]) && !empty($pair[1])) {
            $hash[$pair[1]] = $pair[0];
        }
    }
    return $hash;
}

?>