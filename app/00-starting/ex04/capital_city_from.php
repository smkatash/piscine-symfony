<?php

$states = [
    'Oregon' => 'OR',
    'Alabama' => 'AL',
    'New Jersey' => 'NJ',
    'Colorado' => 'CO',
];

$capitals = [
    'OR' => 'Salem',
    'AL' =>  'Montgomery',
    'NJ' => 'trenton',
    'KS' => 'Topeka',
];

function capital_city_from($state) 
{
    if (!empty($state)) {
        global $states;
        if (array_key_exists($state, $states)) {
            $stateabbr = $states[$state];
            if (!empty($stateabbr)) {
                global $capitals;
                if (array_key_exists($stateabbr, $capitals)) {
                    return $capitals[$stateabbr] . "\n";
                }
            }
        }
    }
    return "Unknown\n";
}

?>